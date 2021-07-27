<?php

namespace Fluxlabs\FluxOpenIdConnectApi\Channel\OpenIdConnect\Command\Callback;

use Exception;
use Fluxlabs\FluxOpenIdConnectApi\Adapter\Api\ResponseDto;
use Fluxlabs\FluxOpenIdConnectApi\Adapter\Config\ProviderConfigDto;
use Fluxlabs\FluxOpenIdConnectApi\Adapter\Config\RouteConfigDto;
use Fluxlabs\FluxOpenIdConnectApi\Adapter\SessionCrypt\SessionCrypt;
use Fluxlabs\FluxOpenIdConnectApi\Channel\Request\Port\RequestService;
use Throwable;

class CallbackCommandHandler
{

    private ProviderConfigDto $provider_config;
    private RequestService $request;
    private RouteConfigDto $route_config;
    private SessionCrypt $session_crypt;


    public static function new(ProviderConfigDto $provider_config, RouteConfigDto $route_config, SessionCrypt $session_crypt, RequestService $request) : static
    {
        $handler = new static();

        $handler->provider_config = $provider_config;
        $handler->route_config = $route_config;
        $handler->session_crypt = $session_crypt;
        $handler->request = $request;

        return $handler;
    }


    public function handle(CallbackCommand $command) : ResponseDto
    {
        try {
            $session = $this->session_crypt->decryptAsJson(
                $command->getRequest()->getEncryptedSession()
            );
            $get = $command->getRequest()->getGet();

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
                "client_id"     => $this->provider_config->getClientId(),
                "client_secret" => $this->provider_config->getClientSecret(),
                "code"          => $code,
                "grant_type"    => "authorization_code",
                "redirect_uri"  => $this->provider_config->getRedirectUri()
            ];

            if ($this->provider_config->isSupportsPkce()) {
                if (empty($code_verifier)) {
                    throw new Exception("Invalid code verifier");
                }

                $data["code_verifier"] = $code_verifier;
            }

            $token_url = $this->provider_config->getUrl()
                . "/oauth/token?"
                . implode("&", array_map(fn(string $key, string $value) : string => $key . "=" . rawurlencode($value), array_keys($data), $data));

            $token = $this->request->request(
                $token_url,
                null,
                $data,
                $this->provider_config->isTrustSelfSignedCertificate()
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

        return ResponseDto::new(
            $this->session_crypt->encryptAsJson(
                $session
            ),
            $redirect_url
        );
    }
}