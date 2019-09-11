<?php

namespace App\Http\Middleware;

use Closure;
use Monolog\Logger;

class LogAfterRequest
{
    protected static $logger;

    public function __construct()
    {
        self::$logger = new Logger('app.requests');
    }

    public function handle($request, Closure $next)
    {
        return $next($request);
    }

    public function terminate($request, $response)
    {
        self::$logger->debug('app.requests', [
            'request' => $request->all(),
            'response' => $response
        ]);
    }
}
