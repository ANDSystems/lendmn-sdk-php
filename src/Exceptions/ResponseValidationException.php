<?php

namespace AndSystems\Lendmn\Exceptions;

class ResponseValidationException extends ValidationException
{
    public function __construct($message, $code = 0, \Throwable $previous = null)
    {
        parent::__construct($message, $code + 1000, $previous);
    }
}
