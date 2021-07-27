<?php

namespace Fluxlabs\FluxOpenIdConnectApi\Adapter\Config;

class SessionCryptConfigDto
{

    private string $method;
    private string $secret;


    public static function new(string $secret, ?string $method = null) : static
    {
        $dto = new static();

        $dto->secret = $secret;
        $dto->method = $method ?? "aes-256-cbc";

        return $dto;
    }


    public function getMethod() : string
    {
        return $this->method;
    }


    public function getSecret() : string
    {
        return $this->secret;
    }
}
