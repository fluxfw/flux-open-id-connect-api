<?php

namespace Fluxlabs\FluxOpenIdConnectApi\Adapter\Api;

class RequestDto
{

    private ?string $encrypted_session;
    private array $get;


    public static function new(?string $encrypted_session = null, ?array $get = null) : static
    {
        $dto = new static();

        $dto->encrypted_session = $encrypted_session;
        $dto->get = $get ?? [];

        return $dto;
    }


    public function getEncryptedSession() : ?string
    {
        return $this->encrypted_session;
    }


    public function getGet() : array
    {
        return $this->get;
    }
}
