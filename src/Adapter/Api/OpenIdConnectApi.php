<?php

namespace FluxOpenIdConnectApi\Adapter\Api;

use FluxOpenIdConnectApi\Adapter\UserInfo\UserInfosDto;
use FluxOpenIdConnectApi\Channel\OpenIdConnect\Port\OpenIdConnectService;
use FluxOpenIdConnectApi\Channel\Request\Port\RequestService;

class OpenIdConnectApi
{

    private function __construct(
        private readonly OpenIdConnectApiConfigDto $open_id_connect_api_config
    ) {

    }


    public static function new(
        ?OpenIdConnectApiConfigDto $open_id_connect_api_config = null
    ) : static {
        return new static(
            $open_id_connect_api_config ?? OpenIdConnectApiConfigDto::newFromEnv()
        );
    }


    /**
     * @param string[] $query_params
     *
     * @return string[]
     */
    public function callback(?string $encrypted_session, array $query_params) : array
    {
        return $this->getOpenIdConnectService()
            ->callback(
                $encrypted_session,
                $query_params
            );
    }


    public function getUserInfos(?string $encrypted_session) : ?UserInfosDto
    {
        return $this->getOpenIdConnectService()
            ->getUserInfos(
                $encrypted_session
            );
    }


    /**
     * @return string[]
     */
    public function login() : array
    {
        return $this->getOpenIdConnectService()
            ->login();
    }


    public function logout() : string
    {
        return $this->getOpenIdConnectService()
            ->logout();
    }


    private function getOpenIdConnectService() : OpenIdConnectService
    {
        return OpenIdConnectService::new(
            $this->open_id_connect_api_config->open_id_config,
            $this->open_id_connect_api_config->route_config,
            $this->open_id_connect_api_config->session_crypt,
            $this->getRequestService()
        );
    }


    private function getRequestService() : RequestService
    {
        return RequestService::new();
    }
}
