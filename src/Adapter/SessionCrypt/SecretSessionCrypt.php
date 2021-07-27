<?php

namespace Fluxlabs\FluxOpenIdConnectApi\Adapter\SessionCrypt;

use Exception;
use Fluxlabs\FluxOpenIdConnectApi\Adapter\Config\SessionCryptConfigDto;

class SecretSessionCrypt implements SessionCrypt
{

    use JsonSessionCrypt;

    private const SEPARATOR = "::";
    private SessionCryptConfigDto $config;


    public static function new(SessionCryptConfigDto $config) : static
    {
        $session_crypt = new static();

        $session_crypt->config = $config;

        return $session_crypt;
    }


    public function decrypt(string $encrypted_session) : string
    {
        $encrypted_session = base64_decode($encrypted_session);

        if (empty($encrypted_session) || substr_count($encrypted_session, self::SEPARATOR) !== 1) {
            throw new Exception("Invalid encrypted session");
        }

        [$value, $iv] = array_map("hex2bin", explode(self::SEPARATOR, $encrypted_session));

        if (empty($value) || empty($iv)) {
            throw new Exception("Invalid encrypted session");
        }

        $session = openssl_decrypt(json_encode($value, JSON_UNESCAPED_SLASHES), $this->config->getMethod(), hash("sha256", $this->config->getSecret()), 0, $iv);

        if (empty($session)) {
            throw new Exception("OpenSSL error: " . openssl_error_string());
        }

        return $session;
    }


    public function encrypt(string $session) : string
    {
        $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length($this->config->getMethod()));

        $value = openssl_encrypt($session, $this->config->getMethod(), hash("sha256", $this->config->getSecret()), 0, $iv);

        if (empty($value)) {
            throw new Exception("OpenSSL error: " . openssl_error_string());
        }

        return base64_encode(implode(self::SEPARATOR, array_map("bin2hex", [$value, $iv])));
    }
}
