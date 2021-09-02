<?php

namespace Fluxlabs\FluxOpenIdConnectApi\Channel\OpenIdConnect\Port;

use Fluxlabs\FluxOpenIdConnectApi\Adapter\Api\OpenIdConfigDto;
use Fluxlabs\FluxOpenIdConnectApi\Adapter\Api\UserInfosDto;
use Fluxlabs\FluxOpenIdConnectApi\Adapter\Config\RouteConfigDto;
use Fluxlabs\FluxOpenIdConnectApi\Adapter\SessionCrypt\SessionCrypt;
use Fluxlabs\FluxOpenIdConnectApi\Channel\OpenIdConnect\Command\CallbackCommand;
use Fluxlabs\FluxOpenIdConnectApi\Channel\OpenIdConnect\Command\GetOpenIdConfigCommand;
use Fluxlabs\FluxOpenIdConnectApi\Channel\OpenIdConnect\Command\GetUserInfosCommand;
use Fluxlabs\FluxOpenIdConnectApi\Channel\OpenIdConnect\Command\LoginCommand;
use Fluxlabs\FluxOpenIdConnectApi\Channel\OpenIdConnect\Command\LogoutCommand;
use Fluxlabs\FluxOpenIdConnectApi\Channel\Request\Port\RequestService;

class OpenIdConnectService
{

    private OpenIdConfigDto $open_id_config;
    private RequestService $request;
    private RouteConfigDto $route_config;
    private SessionCrypt $session_crypt;


    public static function new(OpenIdConfigDto $open_id_config, RouteConfigDto $route_config, SessionCrypt $session_crypt, RequestService $request) : static
    {
        $service = new static();

        $service->open_id_config = $open_id_config;
        $service->route_config = $route_config;
        $service->session_crypt = $session_crypt;
        $service->request = $request;

        return $service;
    }


    public function callback(?string $encrypted_session, array $query_params) : array
    {
        return CallbackCommand::new(
            $this->open_id_config,
            $this->route_config,
            $this->session_crypt,
            $this->request
        )
            ->callback(
                $encrypted_session,
                $query_params
            );
    }


    public function getOpenIdConfig() : OpenIdConfigDto
    {
        return GetOpenIdConfigCommand::new(
            $this->request
        )
            ->getOpenIdConfig(
                $this->open_id_config->getProviderConfig()
            );
    }


    public function getUserInfos(?string $encrypted_session) : ?UserInfosDto
    {
        return GetUserInfosCommand::new(
            $this->open_id_config,
            $this->session_crypt,
            $this->request
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
