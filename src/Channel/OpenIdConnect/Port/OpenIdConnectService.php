<?php

namespace Fluxlabs\FluxOpenIdConnectApi\Channel\OpenIdConnect\Port;

use Fluxlabs\FluxOpenIdConnectApi\Adapter\Api\OpenIdConfigDto;
use Fluxlabs\FluxOpenIdConnectApi\Adapter\Api\RequestDto;
use Fluxlabs\FluxOpenIdConnectApi\Adapter\Api\ResponseDto;
use Fluxlabs\FluxOpenIdConnectApi\Adapter\Config\RouteConfigDto;
use Fluxlabs\FluxOpenIdConnectApi\Adapter\SessionCrypt\SessionCrypt;
use Fluxlabs\FluxOpenIdConnectApi\Channel\OpenIdConnect\Command\Callback\CallbackCommand;
use Fluxlabs\FluxOpenIdConnectApi\Channel\OpenIdConnect\Command\Callback\CallbackCommandHandler;
use Fluxlabs\FluxOpenIdConnectApi\Channel\OpenIdConnect\Command\GetOpenIdConfig\GetOpenIdConfigCommand;
use Fluxlabs\FluxOpenIdConnectApi\Channel\OpenIdConnect\Command\GetOpenIdConfig\GetOpenIdConfigCommandHandler;
use Fluxlabs\FluxOpenIdConnectApi\Channel\OpenIdConnect\Command\GetUserInfos\GetUserInfosCommand;
use Fluxlabs\FluxOpenIdConnectApi\Channel\OpenIdConnect\Command\GetUserInfos\GetUserInfosCommandHandler;
use Fluxlabs\FluxOpenIdConnectApi\Channel\OpenIdConnect\Command\Login\LoginCommand;
use Fluxlabs\FluxOpenIdConnectApi\Channel\OpenIdConnect\Command\Login\LoginCommandHandler;
use Fluxlabs\FluxOpenIdConnectApi\Channel\OpenIdConnect\Command\Logout\LogoutCommand;
use Fluxlabs\FluxOpenIdConnectApi\Channel\OpenIdConnect\Command\Logout\LogoutCommandHandler;
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


    public function callback(RequestDto $request) : ResponseDto
    {
        return CallbackCommandHandler::new(
            $this->open_id_config,
            $this->route_config,
            $this->session_crypt,
            $this->request
        )
            ->handle(
                CallbackCommand::new(
                    $request
                )
            );
    }


    public function getOpenIdConfig() : OpenIdConfigDto
    {
        return GetOpenIdConfigCommandHandler::new(
            $this->request
        )
            ->handle(
                GetOpenIdConfigCommand::new(
                    $this->open_id_config->getProviderConfig()
                )
            );
    }


    public function getUserInfos(RequestDto $request) : ResponseDto
    {
        return GetUserInfosCommandHandler::new(
            $this->open_id_config,
            $this->session_crypt,
            $this->request
        )
            ->handle(
                GetUserInfosCommand::new(
                    $request
                )
            );
    }


    public function login() : ResponseDto
    {
        return LoginCommandHandler::new(
            $this->open_id_config,
            $this->session_crypt
        )
            ->handle(
                LoginCommand::new()
            );
    }


    public function logout() : ResponseDto
    {
        return LogoutCommandHandler::new(
            $this->route_config
        )
            ->handle(
                LogoutCommand::new()
            );
    }
}
