<?php

namespace FluxOpenIdConnectApi\Channel\OpenIdConnect\Command;

use FluxOpenIdConnectApi\Adapter\Config\RouteConfigDto;

class LogoutCommand
{

    private RouteConfigDto $route_config;


    public static function new(RouteConfigDto $route_config) : static
    {
        $command = new static();

        $command->route_config = $route_config;

        return $command;
    }


    public function logout() : string
    {
        return $this->route_config->getAfterLogoutUrl();
    }
}
