<?php

namespace App\Http\Middleware;

use App\Http\Middleware\Exceptions\AddOnFailureException;
use Closure;
use Exception;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Arr;
use Monolog\Logger;
use Twilio\TwiML\VoiceResponse;


class ValidateVoiceRequest
{
    protected static $logger;

    public function __construct()
    {
        self::$logger = new Logger('ValidateVoiceRequest');
    }

    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @return mixed
     * @throws Exception
     */
    public function handle(Request $request, Closure $next)
    {
        $addOnsData = $this->objectToArray(
            $request->input('AddOns')
        );

        try {
            if (Arr::get($addOnsData, 'results.marchex_cleancall')){
                if ($this->isMarchexSpam($addOnsData)) {
                    return $this->rejectIncomingCall();
                }
            } elseif (Arr::get($addOnsData, 'results.nomorobo_spamscore')){
                if ($this->isNomoroboSpam($addOnsData)) {
                    return $this->rejectIncomingCall();
                }
            } elseif (Arr::get($addOnsData, 'results.ekata_pro_phone_rep')){
                if ($this->isEkataSpam($addOnsData)) {
                    return $this->rejectIncomingCall();
                }
            } else {
                self::$logger->error('No Twilio AddOns configured.');
                return $this->rejectIncomingCall();
            }

        } catch (AddOnFailureException $e) {
            self::$logger->error($e->getMessage());
        }

        return $next($request);
    }

    /**
     * @param $object
     * @return mixed
     */
    private function objectToArray($object)
    {
        return @json_decode(json_encode($object), true);
    }

    /**
     * @param $string
     * @return ResponseFactory|Response
     */
    private function rejectIncomingCall() {
        $twiml = new VoiceResponse();
        $twiml->reject();
        return response($twiml)->header('Content-Type', 'text/xml');
    }

    /**
     * @param array $addOnsData
     * @return bool
     * @throws AddOnFailureException
     */
    private function isMarchexSpam(array $addOnsData) {
        $marchexData = Arr::get($addOnsData, 'results.marchex_cleancall');

        $recommendation = Arr::get(
            $marchexData,
            'result.result.recommendation'
        );

        if (Arr::get($marchexData, 'status') == 'failed') {
            throw new AddOnFailureException(Arr::get($marchexData, 'message'));
        }

        return $recommendation == 'BLOCK';
    }

    /**
     * @param $addOnsData
     * @return bool
     * @throws AddOnFailureException
     */
    private function isNomoroboSpam($addOnsData)
    {
        $spamscoreData = Arr::get(
            $addOnsData,
            'results.nomorobo_spamscore'
        );

        if (Arr::get($spamscoreData, 'status') == 'failed') {
            throw new AddOnFailureException(Arr::get($spamscoreData, 'message'));
        }

        $spamScore = (int)Arr::get($spamscoreData, 'result.score');

        return $spamScore >= 1;
    }

    /**
     * @param $addOnsData
     * @return bool
     * @throws AddOnFailureException
     */
    private function isEkataSpam($addOnsData)
    {
        $ekataReputationData = Arr::get(
            $addOnsData,
            'results.ekata_pro_phone_rep'
        );

        if (Arr::get($ekataReputationData, 'status') == 'failed') {
            throw new AddOnFailureException(Arr::get($ekataReputationData, 'message'));
        }

        $reputationScore = (int)Arr::get(
            $ekataReputationData,
            'result.reputation_details.score'
        );

        return $reputationScore >= 50;
    }
}
