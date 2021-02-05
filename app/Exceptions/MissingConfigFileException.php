<?php

namespace App\Exceptions;

use Exception;
use Throwable;

/**
 * Class MissingConfigFileException
 * @package App\Exceptions
 */
class MissingConfigFileException extends Exception
{
    /**
     * MissingConfigFileException constructor.
     *
     * @param int            $code
     * @param Throwable|null $previous
     */
    public function __construct($code = 0, Throwable $previous = null)
    {
        parent::__construct('❌  Missing config file. Please copy config.json.example to config.json and set-up configuration.', $code, $previous);
    }
}
