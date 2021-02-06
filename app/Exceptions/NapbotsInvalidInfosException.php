<?php

namespace App\Exceptions;

use Exception;
use Throwable;
use Illuminate\Support\Facades\Log;

/**
 * Class NapbotsInvalidInfosException.
 */
class NapbotsInvalidInfosException extends Exception
{
    /**
     * @var
     */
    public $weather;

    /**
     * NapbotsInvalidInfosException constructor.
     *
     * @param string         $weather
     * @param int            $code
     * @param Throwable|null $previous
     */
    public function __construct($weather = '', $code = 0, Throwable $previous = null)
    {
        parent::__construct('❌  Napbots invalid response when trying to get infos. Please check your user id in config file.', $code, $previous);
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
