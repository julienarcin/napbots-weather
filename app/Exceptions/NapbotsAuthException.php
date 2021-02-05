<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Support\Facades\Log;
use Throwable;

/**
 * Class NapbotsAuthException
 * @package App\Exceptions
 */
class NapbotsAuthException extends Exception
{
    /**
     * NapbotsAuthException constructor.
     *
     * @param int            $code
     * @param Throwable|null $previous
     */
    public function __construct($code = 0, Throwable $previous = null)
    {
        parent::__construct('❌  Napbots credential error. Please check your username/password.', $code, $previous);
    }

    /**
     * Report or log an exception.
     *
     * @param Exception $exception
     *
     * @throws Exception
     * @return mixed|void
     */
    public function report()
    {
        Log::error($this->getMessage());
    }
}
