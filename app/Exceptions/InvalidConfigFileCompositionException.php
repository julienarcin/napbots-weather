<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Support\Facades\Log;
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
