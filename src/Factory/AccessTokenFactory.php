<?php

namespace AndSystems\Lendmn\Factory;

use AndSystems\Lendmn\Model\AccessToken;

class AccessTokenFactory
{
    public static function createAccessTokenFromArray($arr)
    {
        self::validateAccessTokenArray($arr);

        $accessToken = new AccessToken();
        $accessToken->setToken($arr['accessToken']);
        $accessToken->setExpiresIn($arr['expiresIn']);
        $accessToken->setScopes(preg_split('/\s*,\s*/', trim($arr['scope']), -1, PREG_SPLIT_NO_EMPTY));

        return $accessToken;
    }

    private static function validateAccessTokenArray($arr): void
    {
        Factory::checkMalformedParam($arr, 'accessToken');
        Factory::checkMalformedParam($arr, 'expiresIn');
        Factory::checkMalformedParam($arr, 'scope');
    }
}
