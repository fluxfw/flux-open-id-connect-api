<?php

namespace FluxOpenIdConnectApi\Adapter\Config;

use FluxOpenIdConnectApi\Adapter\SessionCrypt\PlainSessionCrypt;
use FluxOpenIdConnectApi\Channel\OpenIdConnect\Port\OpenIdConnectService;
use FluxOpenIdConnectApi\Channel\Request\Port\RequestService;

class OpenIdConfigDto
{

    private function __construct(
        public readonly ProviderConfigDto $provider_config,
        public readonly ?string $authorization_endpoint,
        public readonly ?string $token_endpoint,
        public readonly ?string $user_info_endpoint
    ) {

    }


    public static function new(
        ProviderConfigDto $provider_config,
        ?string $authorization_endpoint = null,
        ?string $token_endpoint = null,
        ?string $user_info_endpoint = null
    ) : static {
        return new static(
            $provider_config,
            $authorization_endpoint,
            $token_endpoint,
            $user_info_endpoint
        );
    }


    public static function newFromProvider(
        ProviderConfigDto $provider_config
    ) : static {
        return OpenIdConnectService::new(
            static::new(
                $provider_config
            ),
            RouteConfigDto::new(),
            PlainSessionCrypt::new(),
            RequestService::new()
        )
            ->getOpenIdConfig();
    }
}
