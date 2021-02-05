<?php

namespace App\Exceptions;

use Exception;
use Throwable;

/**
 * Class InvalidConfigFileException
 * @package App\Exceptions
 */
class InvalidConfigFileException extends Exception
{
    /**
     * InvalidConfigFileException constructor.
     *
     * @param int            $code
     * @param Throwable|null $previous
     */
    public function __construct($code = 0, Throwable $previous = null)
    {
        parent::__construct('❌  Invalid config file. Please check the content is well formatted JSON.', $code, $previous);
    }
}
