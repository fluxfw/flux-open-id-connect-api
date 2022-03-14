<?php

namespace FluxOpenIdConnectApi\Adapter\Config;

class SessionCryptConfigDto
{

    private function __construct(
        public readonly string $secret,
        public readonly string $method
    ) {

    }


    public static function new(
        string $secret,
        ?string $method = null
    ) : static {
        return new static(
            $secret,
            $method ?? "aes-256-cbc"
        );
    }


    public static function newFromEnv() : static
    {
        return static::new(
            ($_ENV["FLUX_OPEN_ID_CONNECT_API_SESSION_CRYPT_SECRET"] ?? null) ??
            (($secret_file = $_ENV["FLUX_OPEN_ID_CONNECT_API_SESSION_CRYPT_SECRET_FILE"] ?? null) !== null && file_exists($secret_file) ? (file_get_contents($secret_file) ?: "") : null),
            $_ENV["FLUX_OPEN_ID_CONNECT_API_SESSION_CRYPT_METHOD"] ?? null
        );
    }
}
