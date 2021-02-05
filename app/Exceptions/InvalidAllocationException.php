<?php

namespace App\Exceptions;

use Exception;
use Throwable;

/**
 * Class InvalidAllocationException
 * @package App\Exceptions
 */
class InvalidAllocationException extends Exception
{
    /**
     * @var
     */
    public $message;

    /**
     * @var
     */
    public $allocation;

    /**
     * MissingConfigFileException constructor.
     *
     * @param string         $allocation
     * @param string         $message
     * @param int            $code
     * @param Throwable|null $previous
     */
    public function __construct($allocation, $message = '', $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
