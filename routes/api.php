<?php
use Illuminate\Http\Request;
use Flow\JSONPath\JSONPath;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

// Webhook for screening incoming calls to a Twilio Phone Number
Route::post('/voice', function (Request $request) {
    $twiml = new Twilio\Twiml();
    $requestJson = new JSONPath($request->json()->all());

    $rejectIncomingCall = function () use ($twiml)
    {
        $twiml->reject();
        return response($twiml)->header('Content-Type', 'text/xml');
    };

    $hasAddOns = $requestJson->find('$.AddOns')->valid();
    if (!$hasAddOns) {
        return $rejectIncomingCall();
    }

    $isWhitePagesSpam = $requestJson->find('$.*.*.whitepages_pro_phone_rep..[?(@.level==4)]')->valid();
    if ($isWhitePagesSpam) {
        return $rejectIncomingCall();
    }

    $isNomoroboSpam = $requestJson->find('$.*.*.nomorobo_spamscore..[?(@.score==1)]')->valid();
    $nomoroboFailed = $requestJson->find('$.*.*.[?(@.status=="failed")]')->valid();
    if ($isNomoroboSpam || $nomoroboFailed) {
        return $rejectIncomingCall();
    }

    $isMarchexSpam = $requestJson->find('$.*.*.marchex_cleancall..[?(@.recommendation!="PASS")]')->valid();
    if ($isMarchexSpam) {
        return $rejectIncomingCall();
    }

    //Call has successfully passed spam screening.
    $twiml->say('Welcome to the jungle.');
    $twiml->hangup();
    return response($twiml)->header('Content-Type', 'text/xml');
});
