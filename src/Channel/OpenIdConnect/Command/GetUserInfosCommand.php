<?php

namespace FluxOpenIdConnectApi\Channel\OpenIdConnect\Command;

use FluxOpenIdConnectApi\Adapter\Api\OpenIdConfigDto;
use FluxOpenIdConnectApi\Adapter\Api\UserInfosDto;
use FluxOpenIdConnectApi\Adapter\SessionCrypt\SessionCrypt;
use FluxOpenIdConnectApi\Channel\Request\Port\RequestService;
use Throwable;

class GetUserInfosCommand
{

    private OpenIdConfigDto $open_id_config;
    private RequestService $request;
    private SessionCrypt $session_crypt;


    public static function new(OpenIdConfigDto $open_id_config, SessionCrypt $session_crypt, RequestService $request) : static
    {
        $command = new static();

        $command->open_id_config = $open_id_config;
        $command->session_crypt = $session_crypt;
        $command->request = $request;

        return $command;
    }


    public function getUserInfos(?string $encrypted_session) : ?UserInfosDto
    {
        $user_infos = null;
        try {
            $session = $this->session_crypt->decryptAsJson(
                $encrypted_session
            );

            $authorization = $session["authorization"] ?? null;

            if (!empty($authorization)) {
                $user_infos = $this->request->request(
                    $this->open_id_config->getUserInfoEndpoint(),
                    $authorization,
                    null,
                    $this->open_id_config->getProviderConfig()->isTrustSelfSignedCertificate()
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

        return $user_infos;
    }
}
