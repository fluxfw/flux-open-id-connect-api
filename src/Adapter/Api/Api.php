<?php

namespace Fluxlabs\FluxOpenIdConnectApi\Adapter\Api;

use Fluxlabs\FluxOpenIdConnectApi\Adapter\Config\Config;
use Fluxlabs\FluxOpenIdConnectApi\Adapter\Config\EnvConfig;
use Fluxlabs\FluxOpenIdConnectApi\Channel\OpenIdConnect\Port\OpenIdConnectService;
use Fluxlabs\FluxOpenIdConnectApi\Channel\Request\Port\RequestService;

class Api
{

    private Config $config;
    private ?RequestService $request = null;


    public static function new(?Config $config = null) : static
    {
        $api = new static();

        $api->config = $config ?? EnvConfig::new();

        return $api;
    }


    public function callback(RequestDto $request) : ResponseDto
    {
        return OpenIdConnectService::new(
            $this->config->getProviderConfig(),
            $this->config->getRouteConfig(),
            $this->config->getSessionCrypt(),
            $this->getRequest()
        )
            ->callback(
                $request
            );
    }


    public function getUserInfos(RequestDto $request) : ResponseDto
    {
        return OpenIdConnectService::new(
            $this->config->getProviderConfig(),
            $this->config->getRouteConfig(),
            $this->config->getSessionCrypt(),
            $this->getRequest()
        )
            ->getUserInfos(
                $request
            );
    }


    public function login() : ResponseDto
    {
        return OpenIdConnectService::new(
            $this->config->getProviderConfig(),
            $this->config->getRouteConfig(),
            $this->config->getSessionCrypt(),
            $this->getRequest()
        )
            ->login();
    }


    public function logout() : ResponseDto
    {
        return OpenIdConnectService::new(
            $this->config->getProviderConfig(),
            $this->config->getRouteConfig(),
            $this->config->getSessionCrypt(),
            $this->getRequest()
        )
            ->logout();
    }


    private function getRequest() : RequestService
    {
        $this->request ??= RequestService::new();

        return $this->request;
    }
}
