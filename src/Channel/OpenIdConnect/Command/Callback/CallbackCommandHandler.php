<?php

namespace Fluxlabs\FluxOpenIdConnectApi\Channel\OpenIdConnect\Command\Callback;

use Exception;
use Fluxlabs\FluxOpenIdConnectApi\Adapter\Api\OpenIdConfigDto;
use Fluxlabs\FluxOpenIdConnectApi\Adapter\Config\RouteConfigDto;
use Fluxlabs\FluxOpenIdConnectApi\Adapter\SessionCrypt\SessionCrypt;
use Fluxlabs\FluxOpenIdConnectApi\Channel\Request\Port\RequestService;
use Throwable;

class CallbackCommandHandler
{

    private OpenIdConfigDto $open_id_config;
    private RequestService $request;
    private RouteConfigDto $route_config;
    private SessionCrypt $session_crypt;


    public static function new(OpenIdConfigDto $open_id_config, RouteConfigDto $route_config, SessionCrypt $session_crypt, RequestService $request) : static
    {
        $handler = new static();

        $handler->open_id_config = $open_id_config;
        $handler->route_config = $route_config;
        $handler->session_crypt = $session_crypt;
        $handler->request = $request;

        return $handler;
    }


    public function handle(CallbackCommand $command) : array
    {
        try {
            $session = $this->session_crypt->decryptAsJson(
                $command->getEncryptedSession()
            );
            $get = $command->getQuery();

            $session_state = $session["state"] ?? null;
            unset($session["state"]);

            $code_verifier = $session["code_verifier"] ?? null;
            unset($session["code_verifier"]);

            if (!empty($error_description = $get["error_description"] ?? null)) {
                throw new Exception("Get error description: " . $error_description);
            }

            if (!empty($error = $get["error"] ?? null)) {
                throw new Exception("Get error: " . $error);
            }

            if (empty($code = $get["code"] ?? null)) {
                throw new Exception("Invalid code");
            }

            if (empty($get_state = $get["state"] ?? null) || empty($session_state) || $session_state !== $get_state) {
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
