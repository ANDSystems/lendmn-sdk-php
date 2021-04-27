<?php

namespace AndSystems\Lendmn\Exceptions;

class LogicException extends LendmnException
{
    public function __construct($message, $code = 0, \Throwable $previous = null)
    {
        parent::__construct($message, $code + 10000, $previous);
    }
}
