<?php

namespace App\Exceptions;

use Exception;
use Throwable;
use Illuminate\Support\Facades\Log;

/**
 * Class InvalidConfigFileFieldException.
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
        parent::__construct('❌  Invalid config file field "'.$field.'". Please check it.', $code, $previous);
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
