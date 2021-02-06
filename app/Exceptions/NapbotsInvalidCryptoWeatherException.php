<?php

namespace App\Exceptions;

use Exception;
use Throwable;
use Illuminate\Support\Facades\Log;

/**
 * Class NapbotsInvalidCryptoWeatherException.
 */
class NapbotsInvalidCryptoWeatherException extends Exception
{
    /**
     * @var
     */
    public $weather;

    /**
     * NapbotsInvalidCryptoWeatherException constructor.
     *
     * @param string         $weather
     * @param int            $code
     * @param Throwable|null $previous
     */
    public function __construct($weather = '', $code = 0, Throwable $previous = null)
    {
        parent::__construct('❌  Napbots invalid crypto weather: '.$weather.'.', $code, $previous);
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
