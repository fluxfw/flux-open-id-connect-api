<?php

namespace Fluxlabs\FluxOpenIdConnectApi\Adapter\Config;

use Fluxlabs\FluxOpenIdConnectApi\Adapter\SessionCrypt\PlainSessionCrypt;
use Fluxlabs\FluxOpenIdConnectApi\Adapter\SessionCrypt\SecretSessionCrypt;
use Fluxlabs\FluxOpenIdConnectApi\Adapter\SessionCrypt\SessionCrypt;

class EnvConfig implements Config
{

    private static ?self $instance = null;
    private ?CookieConfigDto $cookie_config = null;
    private ?ProviderConfigDto $provider_config = null;
    private ?RouteConfigDto $route_config = null;
    private ?ServerConfigDto $server_config = null;
    private ?SessionCrypt $session_crypt = null;
    private ?SessionCryptConfigDto $session_crypt_config = null;


    public static function new() : static
    {
        static::$instance ??= new static();

        return static::$instance;
    }


    public function getCookieConfig() : CookieConfigDto
    {
        $this->cookie_config ??= CookieConfigDto::new(
            $_ENV["FLUX_OPEN_ID_CONNECT_API_COOKIE_NAME"] ?? null,
            $_ENV["FLUX_OPEN_ID_CONNECT_API_COOKIE_EXPIRES"] ?? null,
            $_ENV["FLUX_OPEN_ID_CONNECT_API_COOKIE_PATH"] ?? null,
            $_ENV["FLUX_OPEN_ID_CONNECT_API_COOKIE_DOMAIN"] ?? null,
            ($secure = $_ENV["FLUX_OPEN_ID_CONNECT_API_COOKIE_SECURE"] ?? null) !== null ? in_array($secure, ["true", "1"]) : null,
            ($http_only = $_ENV["FLUX_OPEN_ID_CONNECT_API_COOKIE_HTTP_ONLY"] ?? null) !== null ? in_array($http_only, ["true", "1"]) : null,
            $_ENV["FLUX_OPEN_ID_CONNECT_API_COOKIE_SAME_SITE"] ?? null,
            $_ENV["FLUX_OPEN_ID_CONNECT_API_COOKIE_PRIORITY"] ?? null
        );

        return $this->cookie_config;
    }


    public function getProviderConfig() : ProviderConfigDto
    {
        $this->provider_config ??= ProviderConfigDto::new(
            $_ENV["FLUX_OPEN_ID_CONNECT_API_PROVIDER_URL"],
            $_ENV["FLUX_OPEN_ID_CONNECT_API_PROVIDER_CLIENT_ID"],
            $_ENV["FLUX_OPEN_ID_CONNECT_API_PROVIDER_CLIENT_SECRET"],
            $_ENV["FLUX_OPEN_ID_CONNECT_API_PROVIDER_REDIRECT_URI"],
            $_ENV["FLUX_OPEN_ID_CONNECT_API_PROVIDER_SCOPE"] ?? null,
            ($supports_proof_key_for_code_exchange = $_ENV["FLUX_OPEN_ID_CONNECT_API_PROVIDER_SUPPORTS_PKCE"] ?? null) !== null ? in_array($supports_proof_key_for_code_exchange, ["true", "1"]) : null,
            ($trust_self_signed_certificate = $_ENV["FLUX_OPEN_ID_CONNECT_API_PROVIDER_TRUST_SELF_SIGNED_CERTIFICATE"] ?? null) !== null ? in_array($trust_self_signed_certificate, ["true", "1"])
                : null
        );

        return $this->provider_config;
    }


    public function getRouteConfig() : RouteConfigDto
    {
        $this->route_config ??= RouteConfigDto::new(
            $_ENV["FLUX_OPEN_ID_CONNECT_API_ROUTE_AFTER_LOGIN_URL"] ?? null,
            $_ENV["FLUX_OPEN_ID_CONNECT_API_ROUTE_AFTER_LOGOUT_URL"] ?? null
        );

        return $this->route_config;
    }


    public function getServerConfig() : ServerConfigDto
    {
        $this->server_config ??= ServerConfigDto::new(
            $_ENV["FLUX_OPEN_ID_CONNECT_API_SERVER_HTTPS_CERT"] ?? null,
            $_ENV["FLUX_OPEN_ID_CONNECT_API_SERVER_HTTPS_KEY"] ?? null,
            $_ENV["FLUX_OPEN_ID_CONNECT_API_SERVER_LISTEN"] ?? null,
            $_ENV["FLUX_OPEN_ID_CONNECT_API_SERVER_PORT"] ?? null
        );

        return $this->server_config;
    }


    public function getSessionCrypt() : SessionCrypt
    {
        if (!(($plain = $_ENV["FLUX_OPEN_ID_CONNECT_API_SESSION_CRYPT_PLAIN"] ?? null) !== null && in_array($plain, ["true", "1"]))) {
            $this->session_crypt ??= SecretSessionCrypt::new(
                $this->getSessionCryptConfig()
            );
        } else {
            $this->session_crypt ??= PlainSessionCrypt::new();
        }

        return $this->session_crypt;
    }


    public function getSessionCryptConfig() : SessionCryptConfigDto
    {
        $this->session_crypt_config ??= SessionCryptConfigDto::new(
            $_ENV["FLUX_OPEN_ID_CONNECT_API_SESSION_CRYPT_SECRET"],
            $_ENV["FLUX_OPEN_ID_CONNECT_API_SESSION_CRYPT_METHOD"] ?? null
        );

        return $this->session_crypt_config;
    }
}
