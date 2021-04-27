<?php

namespace AndSystems\Lendmn\Exceptions;

class LendmnException extends \Exception
{
    public function __construct($message, $code = 0, \Throwable $previous = null)
    {
        /*
         * @todo change $code to include Guzzle domain, Bi, remote errors
         *
         * remote errors as is харуулах хэрэгтэй, doc дээр ямар алдааны код байна тэрийг нь харуулна
         * бусадыг нь i * 10000 + болгож солих хэрэгтэй, үүнд:
         *
         * 0 - 9999 backend-ээс ирсэн тэр чигтээ
         * 10000 - 19999 цэвэр logic exception-ууд
         * 20000 - 29999 validation sanitization алдаанууд
         *
         */

        parent::__construct($message, $code, $previous);
    }
}
