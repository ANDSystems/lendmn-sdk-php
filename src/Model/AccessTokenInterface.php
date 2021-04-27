<?php

namespace AndSystems\Lendmn\Model;

interface AccessTokenInterface
{
    public function getToken(): string;

    public function getExpiresIn();

    public function getScopes();
}
