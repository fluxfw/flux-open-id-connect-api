<?php

namespace FluxOpenIdConnectApi\Adapter\Config;

class SessionCryptConfigDto
{

    public readonly string $method;
    public readonly string $secret;


    public static function new(string $secret, ?string $method = null) : static
    {
        $dto = new static();

        $dto->secret = $secret;
        $dto->method = $method ?? "aes-256-cbc";

        return $dto;
    }
}
