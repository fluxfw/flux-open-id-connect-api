<?php

namespace Fluxlabs\FluxOpenIdConnectApi\Adapter\Api;

use Fluxlabs\FluxOpenIdConnectApi\Adapter\Config\Config;
use Fluxlabs\FluxOpenIdConnectApi\Adapter\Config\EnvConfig;
use Fluxlabs\FluxOpenIdConnectApi\Channel\OpenIdConnect\Port\OpenIdConnectService;
use Fluxlabs\FluxOpenIdConnectApi\Channel\Request\Port\RequestService;

class Api
{

    private Config $config;
    private ?OpenIdConfigDto $open_id_config = null;
    private ?RequestService $request = null;


    public static function new(?Config $config = null) : static
    {
        $api = new static();

        $api->config = $config ?? EnvConfig::new();

        return $api;
    }


    public function callback(?string $encrypted_session, array $query) : array
    {
        return OpenIdConnectService::new(
            $this->getOpenIdConfig(),
            $this->config->getRouteConfig(),
            $this->config->getSessionCrypt(),
            $this->getRequest()
        )
            ->callback(
                $encrypted_session,
                $query
            );
    }


    public function getUserInfos(?string $encrypted_session) : ?UserInfosDto
    {
        return OpenIdConnectService::new(
            $this->getOpenIdConfig(),
            $this->config->getRouteConfig(),
            $this->config->getSessionCrypt(),
            $this->getRequest()
        )
            ->getUserInfos(
                $encrypted_session
            );
    }


    public function login() : array
    {
        return OpenIdConnectService::new(
            $this->getOpenIdConfig(),
            $this->config->getRouteConfig(),
            $this->config->getSessionCrypt(),
            $this->getRequest()
        )
            ->login();
    }


    public function logout() : string
    {
        return OpenIdConnectService::new(
            $this->getOpenIdConfig(),
            $this->config->getRouteConfig(),
            $this->config->getSessionCrypt(),
            $this->getRequest()
        )
            ->logout();
    }


    private function getOpenIdConfig() : OpenIdConfigDto
    {
        $this->open_id_config ??= OpenIdConnectService::new(
            OpenIdConfigDto::new(
                $this->config->getProviderConfig()
            ),
            $this->config->getRouteConfig(),
            $this->config->getSessionCrypt(),
            $this->getRequest()
        )
            ->getOpenIdConfig();

        return $this->open_id_config;
    }


    private function getRequest() : RequestService
    {
        $this->request ??= RequestService::new();

        return $this->request;
    }
}
