<?php
use Illuminate\Http\Request;

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/voice', function (Request $request) {
    //Call has successfully passed spam screening.
    $twiml = new Twilio\Twiml();
    $twiml->say('Welcome to the jungle.');
    $twiml->hangup();
    return response($twiml)->header('Content-Type', 'text/xml');
})->middleware('validate.voice');
