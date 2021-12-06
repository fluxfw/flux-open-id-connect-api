<?php

namespace FluxOpenIdConnectApi\Adapter\Config;

class ServerConfigDto
{

    public readonly ?string $https_cert;
    public readonly ?string $https_key;
    public readonly string $listen;
    public readonly int $port;


    public static function new(?string $https_cert = null, ?string $https_key = null, ?string $listen = null, ?int $port = null) : static
    {
        $dto = new static();

        $dto->https_cert = $https_cert;
        $dto->https_key = $https_key;
        $dto->listen = $listen ?? "0.0.0.0";
        $dto->port = $port ?? 9501;

        return $dto;
    }
}
