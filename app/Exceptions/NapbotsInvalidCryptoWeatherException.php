<?php

namespace App\Exceptions;

use Exception;
use Throwable;

/**
 * Class NapbotsInvalidCryptoWeatherException
 * @package App\Exceptions
 */
class NapbotsInvalidCryptoWeatherException extends Exception
{
    /**
     * @var
     */
    public $weather;

    /**
     * NapbotsAuthException constructor.
     *
     * @param string         $weather
     * @param int            $code
     * @param Throwable|null $previous
     */
    public function __construct($weather = '', $code = 0, Throwable $previous = null)
    {
        parent::__construct('❌  Napbots invalid crypto weather: ' . $weather . '.', $code, $previous);
    }
}
