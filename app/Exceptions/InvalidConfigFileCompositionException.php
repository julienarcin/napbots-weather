<?php

namespace App\Exceptions;

use Exception;
use Throwable;

/**
 * Class InvalidConfigFileCompositionException
 * @package App\Exceptions
 */
class InvalidConfigFileCompositionException extends Exception
{
    /**
     * @var
     */
    public $field;

    /**
     * InvalidConfigFileCompositionException constructor.
     *
     * @param string         $allocationName
     * @param int            $code
     * @param Throwable|null $previous
     */
    public function __construct($allocationName = '', $code = 0, Throwable $previous = null)
    {
        parent::__construct('❌  Invalid config file composition "' . $allocationName . '". Sum should be equal to 1.', $code, $previous);
    }
}
