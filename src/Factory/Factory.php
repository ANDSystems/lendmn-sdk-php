<?php

namespace AndSystems\Lendmn\Factory;

use AndSystems\Lendmn\Exceptions\ValidationException;

class Factory
{
    const REMOTE_DATE_FORMAT = 'Y-m-d H:i:s.v';

    public static function checkMalformedParam($arr, $key, $required = true): void
    {
        if (!$required && !array_key_exists($key, $arr) || $required && !isset($arr[$key])) {
            throw new ValidationException(sprintf('Malformed response, missing \'%s\' key', $key), ValidationException::MALFORMED_RESPONSE_MISSING_PARAM);
        }
    }

    public static function sanitizeStringToDateTime($var, $varName)
    {
        if (is_string($var)) {
            try {
                $var = new \DateTime($var);
            } catch (\Exception $e) {
                throw new ValidationException(sprintf('Invalid \'createdAt\', expected format \'%s\' \'%s\' was \'%s\'', Factory::REMOTE_DATE_FORMAT, date(Factory::REMOTE_DATE_FORMAT), $varName), ValidationException::MALFORMED_RESPONSE_MISSING_PARAM);
            }
        }

        return $var;
    }
}
