<?php

namespace App\Exceptions;

use Exception;

class ErrorException extends Exception
{
    public $errorData = [];

    public function __construct($errorData=[])
    {
        parent::__construct();
        $this->errorData = $errorData;
    }

    public function report(Throwable $exception)
    {
        parent::report($exception);
    }

    public function render($request, Throwable $exception)
    {
        return parent::render($request, $exception);
    }
}
