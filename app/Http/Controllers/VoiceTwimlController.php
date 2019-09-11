<?php


namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Monolog\Logger;
use Twilio\TwiML\VoiceResponse;

class VoiceTwimlController extends Controller
{
    private static $logger;

    public function __construct()
    {
        self::$logger = new Logger('VoiceTwimlController');
        $this->middleware('validate.voice')->only('store');
    }

    /**
     * @param Request $request
     * @return User
     */
    public function store(Request $request)
    {
        // Call has successfully passed spam screening.
        $twiml = new VoiceResponse();
        $twiml->say('Welcome to the jungle.',
            array('voice' => 'woman', 'language' => 'en-gb'));
        $twiml->hangup();
        return response($twiml)->header('Content-Type', 'text/xml');
    }
}
