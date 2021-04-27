<?php

namespace AndSystems\Lendmn\Exceptions;

class JsonException extends ResponseValidationException
{
    public function __construct($message, $code = 0, \Throwable $previous = null)
    {
        /*
         * 21100 - 21199 json errors
         */

        parent::__construct($message, $code + 100, $previous);
    }
}
