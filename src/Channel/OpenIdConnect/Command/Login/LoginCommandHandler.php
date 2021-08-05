<?php

namespace Fluxlabs\FluxOpenIdConnectApi\Channel\OpenIdConnect\Command\Login;

use Fluxlabs\FluxOpenIdConnectApi\Adapter\Api\OpenIdConfigDto;
use Fluxlabs\FluxOpenIdConnectApi\Adapter\Api\ResponseDto;
use Fluxlabs\FluxOpenIdConnectApi\Adapter\SessionCrypt\SessionCrypt;

class LoginCommandHandler
{

    private OpenIdConfigDto $open_id_config;
    private SessionCrypt $session_crypt;


    public static function new(OpenIdConfigDto $open_id_config, SessionCrypt $session_crypt) : static
    {
        $handler = new static();

        $handler->open_id_config = $open_id_config;
        $handler->session_crypt = $session_crypt;

        return $handler;
    }


    public function handle(LoginCommand $command) : ResponseDto
    {
        $session = [];

        $session["state"] = $state = hash("sha256", rand() . microtime(true));

        $parameters = [
            "client_id"     => $this->open_id_config->getProviderConfig()->getClientId(),
            "redirect_uri"  => $this->open_id_config->getProviderConfig()->getRedirectUri(),
            "response_type" => "code",
            "state"         => $state,
            "scope"         => $this->open_id_config->getProviderConfig()->getScope()
        ];

        if ($this->open_id_config->getProviderConfig()->isSupportsPkce()) {
            $session["code_verifier"] = $code_verifier = bin2hex(random_bytes(64));

            $parameters += [
                "code_challenge"        => rtrim(strtr(base64_encode(hash("sha256", $code_verifier, true)), "+/", "-_"), "="),
                "code_challenge_method" => "S256"
            ];
        }

        $authorize_url = $this->open_id_config->getAuthorizationEndpoint();
        $authorize_url .= (str_contains($authorize_url, "?") ? "&" : "?")
            . implode("&", array_map(fn(string $key, string $value) => $key . "=" . rawurlencode($value), array_keys($parameters), $parameters));

        return ResponseDto::new(
            $this->session_crypt->encryptAsJson(
                $session
            ),
            $authorize_url
        );
    }
}
