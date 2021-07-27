<?php

namespace Fluxlabs\FluxOpenIdConnectApi\Adapter\Api;

class ResponseDto
{

    private ?object $body;
    private ?string $encrypted_session;
    private ?string $redirect_url;


    public static function new(?string $encrypted_session = null, ?string $redirect_url = null, ?object $body = null) : static
    {
        $dto = new static();

        $dto->encrypted_session = $encrypted_session;
        $dto->redirect_url = $redirect_url;
        $dto->body = $body;

        return $dto;
    }


    public function getBody() : ?object
    {
        return $this->body;
    }


    public function getEncryptedSession() : ?string
    {
        return $this->encrypted_session;
    }


    public function getRedirectUrl() : ?string
    {
        return $this->redirect_url;
    }
}
