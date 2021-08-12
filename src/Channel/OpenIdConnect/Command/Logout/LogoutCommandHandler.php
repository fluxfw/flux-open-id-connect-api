<?php

namespace Fluxlabs\FluxOpenIdConnectApi\Channel\OpenIdConnect\Command\Logout;

use Fluxlabs\FluxOpenIdConnectApi\Adapter\Config\RouteConfigDto;

class LogoutCommandHandler
{

    private RouteConfigDto $route_config;


    public static function new(RouteConfigDto $route_config) : static
    {
        $handler = new static();

        $handler->route_config = $route_config;

        return $handler;
    }


    public function handle(LogoutCommand $command) : string
    {
        return $this->route_config->getAfterLogoutUrl();
    }
}
