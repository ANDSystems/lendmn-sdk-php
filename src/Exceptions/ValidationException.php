<?php

namespace AndSystems\Lendmn\Exceptions;

/**
 *  20000 - 20999 non response validation errors
 *  21000 - 21999 response validation errors
 *    21000 - 21099 uncategorized
 *    21100 - 21199 json errors
 */
class ValidationException extends LendmnException
{
    const MALFORMED_RESPONSE_MISSING_PARAM = 1001;
    const MISSING_PARAMETER = 2;
    const INVALID_PARAMETER = 3;
    const INVALID_PUBLIC_KEY = 4;

    public function __construct($message, $code = 0, \Throwable $previous = null)
    {
        parent::__construct($message, $code + 20000, $previous);
    }
}
