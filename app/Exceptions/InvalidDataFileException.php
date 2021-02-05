<?php

namespace App\Exceptions;

use Exception;
use Throwable;

/**
 * Class InvalidDataFileException
 * @package App\Exceptions
 */
class InvalidDataFileException extends Exception
{
    /**
     * InvalidDataFileException constructor.
     *
     * @param int            $code
     * @param Throwable|null $previous
     */
    public function __construct($code = 0, Throwable $previous = null)
    {
        parent::__construct('❌  Invalid data file. Please check the content is well formatted JSON.', $code, $previous);
    }
}
