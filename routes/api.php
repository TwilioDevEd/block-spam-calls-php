<?php
use Illuminate\Http\Request;
use Flow\JSONPath\JSONPath;

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/voice', function (Request $request) {
    $requestJson = new JSONPath($request->json()->all());

    $validateJSONPath = function ($JSONPath) use ($request, $requestJson) {
        return $requestJson->find($JSONPath)->valid();
    };

    $rejectIncomingCall = function () {
        $twiml = new Twilio\Twiml();
        $twiml->reject();
        return response($twiml)->header('Content-Type', 'text/xml');
    };

    if (!$validateJSONPath('$.AddOns')) {
        return $rejectIncomingCall();
    }

    $JSONPaths = [
        'isWhitePagesSpam' => '$.*.*.whitepages_pro_phone_rep..[?(@.level==4)]',
        'isNomoroboSpam' => '$.*.*.nomorobo_spamscore..[?(@.score==1)]',
        'nomoroboFailed' => '$.*.*.[?(@.status=="failed")]',
        'isMarchexSpam' => '$.*.*.marchex_cleancall..[?(@.recommendation!="PASS")]'
    ];

    foreach ($JSONPaths as $JSONPath) {
        if ($validateJSONPath($JSONPath)) {
            return $rejectIncomingCall();
        }
    }

    //Call has successfully passed spam screening.
    $twiml = new Twilio\Twiml();
    $twiml->say('Welcome to the jungle.');
    $twiml->hangup();
    return response($twiml)->header('Content-Type', 'text/xml');
});
