<?php

namespace Fluxlabs\FluxOpenIdConnectApi\Channel\OpenIdConnect\Command\GetUserInfos;

use Fluxlabs\FluxOpenIdConnectApi\Adapter\Api\ResponseDto;
use Fluxlabs\FluxOpenIdConnectApi\Adapter\Api\UserInfosDto;
use Fluxlabs\FluxOpenIdConnectApi\Adapter\Config\ProviderConfigDto;
use Fluxlabs\FluxOpenIdConnectApi\Adapter\SessionCrypt\SessionCrypt;
use Fluxlabs\FluxOpenIdConnectApi\Channel\Request\Port\RequestService;
use Throwable;

class GetUserInfosCommandHandler
{

    private ProviderConfigDto $provider_config;
    private RequestService $request;
    private SessionCrypt $session_crypt;


    public static function new(ProviderConfigDto $provider_config, SessionCrypt $session_crypt, RequestService $request) : static
    {
        $handler = new static();

        $handler->provider_config = $provider_config;
        $handler->session_crypt = $session_crypt;
        $handler->request = $request;

        return $handler;
    }


    public function handle(GetUserInfosCommand $command) : ResponseDto
    {
        $user_infos = null;
        try {
            $session = $this->session_crypt->decryptAsJson(
                $command->getRequest()->getEncryptedSession()
            );

            $authorization = $session["authorization"] ?? null;

            if (!empty($authorization)) {
                $user_infos = $this->request->request(
                    $this->provider_config->getUrl() . "/oauth/userinfo",
                    $authorization,
                    null,
                    $this->provider_config->isTrustSelfSignedCertificate()
                );

                if (!empty($user_infos)) {
                    $user_infos = UserInfosDto::new(
                        $user_infos["sub"] ?? null,
                        $user_infos["name"] ?? null,
                        $user_infos["nickname"] ?? null,
                        $user_infos["profile"] ?? null,
                        $user_infos["picture"] ?? null,
                        $user_infos["email"] ?? null
                    );
                }
            }
        } catch (Throwable $ex) {
            echo "Get user infos error: " . $ex . "\n";

            $user_infos = null;
        }

        return ResponseDto::new(
            $command->getRequest()->getEncryptedSession(),
            null,
            $user_infos
        );
    }
}
