<?php

namespace FluxOpenIdConnectApi\Channel\OpenIdConnect\Command;

use Exception;
use FluxOpenIdConnectApi\Adapter\Api\OpenIdConfigDto;
use FluxOpenIdConnectApi\Adapter\Config\RouteConfigDto;
use FluxOpenIdConnectApi\Adapter\SessionCrypt\SessionCrypt;
use FluxOpenIdConnectApi\Channel\Request\Port\RequestService;
use Throwable;

class CallbackCommand
{

    private OpenIdConfigDto $open_id_config;
    private RequestService $request;
    private RouteConfigDto $route_config;
    private SessionCrypt $session_crypt;


    public static function new(OpenIdConfigDto $open_id_config, RouteConfigDto $route_config, SessionCrypt $session_crypt, RequestService $request) : static
    {
        $command = new static();

        $command->open_id_config = $open_id_config;
        $command->route_config = $route_config;
        $command->session_crypt = $session_crypt;
        $command->request = $request;

        return $command;
    }


    public function callback(?string $encrypted_session, array $query_params) : array
    {
        try {
            $session = $this->session_crypt->decryptAsJson(
                $encrypted_session
            );

            $session_state = $session["state"] ?? null;
            unset($session["state"]);

            $code_verifier = $session["code_verifier"] ?? null;
            unset($session["code_verifier"]);

            if (!empty($error_description = $query_params["error_description"] ?? null)) {
                throw new Exception("Get error description: " . $error_description);
            }

            if (!empty($error = $query_params["error"] ?? null)) {
                throw new Exception("Get error: " . $error);
            }

            if (empty($code = $query_params["code"] ?? null)) {
                throw new Exception("Invalid code");
            }

            if (empty($query_state = $query_params["state"] ?? null) || empty($session_state) || $session_state !== $query_state) {
                throw new Exception("Invalid state");
            }

            $data = [
                "client_id"     => $this->open_id_config->getProviderConfig()->getClientId(),
                "client_secret" => $this->open_id_config->getProviderConfig()->getClientSecret(),
                "code"          => $code,
                "grant_type"    => "authorization_code",
                "redirect_uri"  => $this->open_id_config->getProviderConfig()->getRedirectUri()
            ];

            if ($this->open_id_config->getProviderConfig()->isSupportsPkce()) {
                if (empty($code_verifier)) {
                    throw new Exception("Invalid code verifier");
                }

                $data["code_verifier"] = $code_verifier;
            }

            $token_url = $this->open_id_config->getTokenEndpoint();
            $token_url .= (str_contains($token_url, "?") ? "&" : "?")
                . implode("&", array_map(fn(string $key, string $value) : string => $key . "=" . rawurlencode($value), array_keys($data), $data));

            $token = $this->request->request(
                $token_url,
                null,
                $data,
                $this->open_id_config->getProviderConfig()->isTrustSelfSignedCertificate()
            );

            if (empty($token_type = $token["token_type"]) || empty($access_token = $token["access_token"])) {
                throw new Exception("Invalid access token");
            }

            $session["authorization"] = $token_type . " " . $access_token;

            $redirect_url = $this->route_config->getAfterLoginUrl();
        } catch (Throwable $ex) {
            echo "Callback error: " . $ex . "\n";

            $session = null;
            $redirect_url = null;
        }

        return [
            $this->session_crypt->encryptAsJson(
                $session
            ),
            $redirect_url
        ];
    }
}
