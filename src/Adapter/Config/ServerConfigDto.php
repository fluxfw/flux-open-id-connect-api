<?php

namespace Fluxlabs\FluxOpenIdConnectApi\Adapter\Config;

class ServerConfigDto
{

    private ?string $https_cert = null;
    private ?string $https_key = null;
    private string $listen;
    private int $port;


    public static function new(?string $https_cert = null, ?string $https_key = null, ?string $listen = null, ?int $port = null) : static
    {
        $dto = new static();

        $dto->https_cert = $https_cert;
        $dto->https_key = $https_key;
        $dto->listen = $listen ?? "0.0.0.0";
        $dto->port = $port ?? 9501;

        return $dto;
    }


    public function getHttpsCert() : ?string
    {
        return $this->https_cert;
    }


    public function getHttpsKey() : ?string
    {
        return $this->https_key;
    }


    public function getListen() : string
    {
        return $this->listen;
    }


    public function getPort() : int
    {
        return $this->port;
    }
}
