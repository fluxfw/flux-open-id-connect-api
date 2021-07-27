<?php

namespace Fluxlabs\FluxOpenIdConnectApi\Channel\OpenIdConnect\Command\Login;

class LoginCommand
{

    public static function new() : static
    {
        $command = new static();

        return $command;
    }
}
