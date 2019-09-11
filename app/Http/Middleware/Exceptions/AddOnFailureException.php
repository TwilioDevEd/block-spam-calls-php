<?php


namespace App\Http\Middleware\Exceptions;


use Exception;
use Throwable;

class AddOnFailureException extends Exception
{

    /**
     * AddOnFailureException constructor.
     * @param string $message
     * @param int $code
     * @param Throwable|null $previous
     */
    public function __construct($message = "", $code = 0, Throwable $previous = null)
    {
        $message = "Twilio AddOn Failure: $message";

        Parent::__construct($message, $code, $previous);
    }
}