<?php

namespace Fluxlabs\FluxOpenIdConnectApi\Channel\OpenIdConnect\Command\Logout;

class LogoutCommand
{

    public static function new() : static
    {
        $command = new static();

        return $command;
    }
}
