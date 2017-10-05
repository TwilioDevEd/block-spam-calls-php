<?php

namespace App\Http\Middleware;

use Closure;
use Flow\JSONPath\JSONPath;

class ValidateVoiceRequest
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $requestJson = new JSONPath($request->json()->all());

        $validateJSONPath = function ($JSONPath) use ($requestJson) {
            return $requestJson->find($JSONPath)->valid();
        };

        $rejectIncomingCall = function () {
            $twiml = new \Twilio\Twiml();
            $twiml->reject();
            return response($twiml)->header('Content-Type', 'text/xml');
        };

        if (!$validateJSONPath('$.AddOns')) {
          return $next($request);
        }

        $JSONPaths = [
            'isWhitePagesSpam' => '$..whitepages_pro_phone_rep..[?(@.type=="ScamOrFraud")]',
            'isNomoroboSpam' => '$.*.*.nomorobo_spamscore..[?(@.score==1)]',
            'nomoroboFailed' => '$.*.*.[?(@.status=="failed")]',
            'isMarchexSpam' => '$.*.*.marchex_cleancall..[?(@.recommendation!="PASS")]'
        ];

        foreach ($JSONPaths as $JSONPath) {
            if ($validateJSONPath($JSONPath)) {
                return $rejectIncomingCall();
            }
        }

        return $next($request);
    }
}
