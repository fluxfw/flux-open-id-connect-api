<?php

namespace FluxOpenIdConnectApi\Channel\OpenIdConnect\Command;

use FluxOpenIdConnectApi\Adapter\Api\UserInfosDto;
use FluxOpenIdConnectApi\Adapter\Config\OpenIdConfigDto;
use FluxOpenIdConnectApi\Adapter\SessionCrypt\SessionCrypt;
use FluxOpenIdConnectApi\Channel\Request\Port\RequestService;
use Throwable;

class GetUserInfosCommand
{

    private function __construct(
        private readonly OpenIdConfigDto $open_id_config,
        private readonly SessionCrypt $session_crypt,
        private readonly RequestService $request_service
    ) {

    }


    public static function new(
        OpenIdConfigDto $open_id_config,
        SessionCrypt $session_crypt,
        RequestService $request_service
    ) : static {
        return new static(
            $open_id_config,
            $session_crypt,
            $request_service
        );
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
                $user_infos = $this->request_service->request(
                    $this->open_id_config->user_info_endpoint,
                    $authorization,
                    null,
                    $this->open_id_config->provider_config->trust_self_signed_certificate
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
