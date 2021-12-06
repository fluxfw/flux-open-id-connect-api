<?php

namespace FluxOpenIdConnectApi\Adapter\Api;

use FluxOpenIdConnectApi\Adapter\Config\ProviderConfigDto;

class OpenIdConfigDto
{

    public readonly ?string $authorization_endpoint;
    public readonly ProviderConfigDto $provider_config;
    public readonly ?string $token_endpoint;
    public readonly ?string $user_info_endpoint;


    public static function new(ProviderConfigDto $provider_config, ?string $authorization_endpoint = null, ?string $token_endpoint = null, ?string $user_info_endpoint = null) : static
    {
        $dto = new static();

        $dto->provider_config = $provider_config;
        $dto->authorization_endpoint = $authorization_endpoint;
        $dto->token_endpoint = $token_endpoint;
        $dto->user_info_endpoint = $user_info_endpoint;

        return $dto;
    }
}
