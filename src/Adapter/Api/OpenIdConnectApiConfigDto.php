<?php

namespace FluxOpenIdConnectApi\Adapter\Api;

use FluxOpenIdConnectApi\Adapter\OpenId\OpenIdConfigDto;
use FluxOpenIdConnectApi\Adapter\Provider\ProviderConfigDto;
use FluxOpenIdConnectApi\Adapter\Route\RouteConfigDto;
use FluxOpenIdConnectApi\Adapter\SessionCrypt\PlainSessionCrypt;
use FluxOpenIdConnectApi\Adapter\SessionCrypt\SecretSessionCrypt;
use FluxOpenIdConnectApi\Adapter\SessionCrypt\SessionCrypt;
use FluxOpenIdConnectApi\Adapter\SessionCrypt\SessionCryptConfigDto;

class OpenIdConnectApiConfigDto
{

    private function __construct(
        public readonly OpenIdConfigDto $open_id_config,
        public readonly RouteConfigDto $route_config,
        public readonly SessionCrypt $session_crypt
    ) {

    }


    public static function new(
        OpenIdConfigDto $open_id_config,
        RouteConfigDto $route_config,
        SessionCrypt $session_crypt
    ) : static {
        return new static(
            $open_id_config,
            $route_config,
            $session_crypt
        );
    }


    public static function newFromEnv() : static
    {
        return static::new(
            OpenIdConfigDto::newFromProvider(
                ProviderConfigDto::newFromEnv()
            ),
            RouteConfigDto::newFromEnv(),
            !(($plain = $_ENV["FLUX_OPEN_ID_CONNECT_API_SESSION_CRYPT_PLAIN"] ?? null) !== null && in_array($plain, ["true", "1"])) ? SecretSessionCrypt::new(
                SessionCryptConfigDto::newFromEnv()
            ) : PlainSessionCrypt::new()
        );
    }
}
