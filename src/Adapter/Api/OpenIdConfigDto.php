<?php

namespace Fluxlabs\FluxOpenIdConnectApi\Adapter\Api;

use Fluxlabs\FluxOpenIdConnectApi\Adapter\Config\ProviderConfigDto;

class OpenIdConfigDto
{

    private ?string $authorization_endpoint;
    private ProviderConfigDto $provider_config;
    private ?string $token_endpoint;
    private ?string $user_info_endpoint;


    public static function new(ProviderConfigDto $provider_config, ?string $authorization_endpoint = null, ?string $token_endpoint = null, ?string $user_info_endpoint = null) : static
    {
        $dto = new static();

        $dto->provider_config = $provider_config;
        $dto->authorization_endpoint = $authorization_endpoint;
        $dto->token_endpoint = $token_endpoint;
        $dto->user_info_endpoint = $user_info_endpoint;

        return $dto;
    }


    public function getAuthorizationEndpoint() : ?string
    {
        return $this->authorization_endpoint;
    }


    public function getProviderConfig() : ProviderConfigDto
    {
        return $this->provider_config;
    }


    public function getTokenEndpoint() : ?string
    {
        return $this->token_endpoint;
    }


    public function getUserInfoEndpoint() : ?string
    {
        return $this->user_info_endpoint;
    }
}
