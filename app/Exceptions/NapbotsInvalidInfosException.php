<?php

namespace App\Exceptions;

use Exception;
use Throwable;

/**
 * Class NapbotsInvalidInformationsException
 * @package App\Exceptions
 */
class NapbotsInvalidInfosException extends Exception
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
        parent::__construct('❌  Napbots invalid response when trying to get infos. Please check your user id in config file.', $code, $previous);
    }
}
