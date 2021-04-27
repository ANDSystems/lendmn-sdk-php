<?php

namespace AndSystems\Lendmn\Model;

class AccessToken implements AccessTokenInterface
{
    protected $token;
    protected $expiresIn;
    protected $scopes;

    public function setToken(string $token)
    {
        $this->token = $token;
    }

    public function getToken(): string
    {
        return $this->token;
    }

    public function setExpiresIn($expiresIn)
    {
        $this->expiresIn = $expiresIn;
    }

    public function getExpiresIn()
    {
        return $this->expiresIn;
    }

    public function setScopes($scopes)
    {
        $this->scopes = $scopes;
    }

    public function getScopes()
    {
        return $this->scopes;
    }

    public function __toString()
    {
        return $this->token;
    }
}
