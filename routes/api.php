<?php
use Illuminate\Http\Request;

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware('validate.voice')->post('/voice', function (Request $request) {
    //Call has successfully passed spam screening.
    $twiml = new Twilio\Twiml();
    $twiml->say('Welcome to the jungle.',
        array('voice' => 'woman', 'language' => 'en-gb'));
    $twiml->hangup();
    return response($twiml)->header('Content-Type', 'text/xml');
});
