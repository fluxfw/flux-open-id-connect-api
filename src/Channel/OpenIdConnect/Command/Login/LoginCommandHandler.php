<?php

namespace Fluxlabs\FluxOpenIdConnectApi\Channel\OpenIdConnect\Command\Login;

use Fluxlabs\FluxOpenIdConnectApi\Adapter\Api\ResponseDto;
use Fluxlabs\FluxOpenIdConnectApi\Adapter\Config\ProviderConfigDto;
use Fluxlabs\FluxOpenIdConnectApi\Adapter\SessionCrypt\SessionCrypt;

class LoginCommandHandler
{

    private ProviderConfigDto $provider_config;
    private SessionCrypt $session_crypt;


    public static function new(ProviderConfigDto $provider_config, SessionCrypt $session_crypt) : static
    {
        $handler = new static();

        $handler->provider_config = $provider_config;
        $handler->session_crypt = $session_crypt;

        return $handler;
    }


    public function handle(LoginCommand $command) : ResponseDto
    {
        $session = [];

        $session["state"] = $state = hash("sha256", rand() . microtime(true));

        $parameters = [
            "client_id"     => $this->provider_config->getClientId(),
            "redirect_uri"  => $this->provider_config->getRedirectUri(),
            "response_type" => "code",
            "state"         => $state,
            "scope"         => $this->provider_config->getScope()
        ];

        if ($this->provider_config->isSupportsPkce()) {
            $session["code_verifier"] = $code_verifier = bin2hex(random_bytes(64));

            $parameters += [
                "code_challenge"        => rtrim(strtr(base64_encode(hash("sha256", $code_verifier, true)), "+/", "-_"), "="),
                "code_challenge_method" => "S256"
            ];
        }

        $authorize_url = $this->provider_config->getUrl()
            . "/oauth/authorize?"
            . implode("&", array_map(fn(string $key, string $value) => $key . "=" . rawurlencode($value), array_keys($parameters), $parameters));

        return ResponseDto::new(
            $this->session_crypt->encryptAsJson(
                $session
            ),
            $authorize_url
        );
    }
}
