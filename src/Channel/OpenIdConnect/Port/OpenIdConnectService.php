<?php

namespace FluxOpenIdConnectApi\Channel\OpenIdConnect\Port;

use FluxOpenIdConnectApi\Adapter\Api\UserInfosDto;
use FluxOpenIdConnectApi\Adapter\Config\OpenIdConfigDto;
use FluxOpenIdConnectApi\Adapter\Config\RouteConfigDto;
use FluxOpenIdConnectApi\Adapter\SessionCrypt\SessionCrypt;
use FluxOpenIdConnectApi\Channel\OpenIdConnect\Command\CallbackCommand;
use FluxOpenIdConnectApi\Channel\OpenIdConnect\Command\GetOpenIdConfigCommand;
use FluxOpenIdConnectApi\Channel\OpenIdConnect\Command\GetUserInfosCommand;
use FluxOpenIdConnectApi\Channel\OpenIdConnect\Command\LoginCommand;
use FluxOpenIdConnectApi\Channel\OpenIdConnect\Command\LogoutCommand;
use FluxOpenIdConnectApi\Channel\Request\Port\RequestService;

class OpenIdConnectService
{

    private function __construct(
        private readonly OpenIdConfigDto $open_id_config,
        private readonly RouteConfigDto $route_config,
        private readonly SessionCrypt $session_crypt,
        private readonly RequestService $request_service
    ) {

    }


    public static function new(
        OpenIdConfigDto $open_id_config,
        RouteConfigDto $route_config,
        SessionCrypt $session_crypt,
        RequestService $request_service
    ) : static {
        return new static(
            $open_id_config,
            $route_config,
            $session_crypt,
            $request_service
        );
    }


    public function callback(?string $encrypted_session, array $query_params) : array
    {
        return CallbackCommand::new(
            $this->open_id_config,
            $this->route_config,
            $this->session_crypt,
            $this->request_service
        )
            ->callback(
                $encrypted_session,
                $query_params
            );
    }


    public function getOpenIdConfig() : OpenIdConfigDto
    {
        return GetOpenIdConfigCommand::new(
            $this->request_service
        )
            ->getOpenIdConfig(
                $this->open_id_config->provider_config
            );
    }


    public function getUserInfos(?string $encrypted_session) : ?UserInfosDto
    {
        return GetUserInfosCommand::new(
            $this->open_id_config,
            $this->session_crypt,
            $this->request_service
        )
            ->getUserInfos(
                $encrypted_session
            );
    }


    public function login() : array
    {
        return LoginCommand::new(
            $this->open_id_config,
            $this->session_crypt
        )
            ->login();
    }


    public function logout() : string
    {
        return LogoutCommand::new(
            $this->route_config
        )
            ->logout();
    }
}
