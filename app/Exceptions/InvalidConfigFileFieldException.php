<?php

namespace App\Exceptions;

use Exception;
use Throwable;

/**
 * Class InvalidConfigFileFieldException
 * @package App\Exceptions
 */
class InvalidConfigFileFieldException extends Exception
{
    /**
     * @var
     */
    public $field;

    /**
     * InvalidConfigFileFieldException constructor.
     *
     * @param string         $field
     * @param int            $code
     * @param Throwable|null $previous
     */
    public function __construct($field = '', $code = 0, Throwable $previous = null)
    {
        parent::__construct('❌  Invalid config file field "' . $field . '". Please check it.', $code, $previous);
    }
}
