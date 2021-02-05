<?php

namespace App\Exceptions;

use Exception;
use Throwable;

/**
 * Class MissingConfigFileFieldException
 * @package App\Exceptions
 */
class MissingConfigFileFieldException extends Exception
{
    /**
     * @var
     */
    public $field;

    /**
     * MissingConfigFileFieldException constructor.
     *
     * @param string         $field
     * @param int            $code
     * @param Throwable|null $previous
     */
    public function __construct($field = '', $code = 0, Throwable $previous = null)
    {
        parent::__construct('❌  Missing config file field "' . $field . '". Please update it.', $code, $previous);
    }
}
