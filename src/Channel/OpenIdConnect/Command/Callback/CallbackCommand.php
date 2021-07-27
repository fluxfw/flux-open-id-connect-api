<?php

namespace Fluxlabs\FluxOpenIdConnectApi\Channel\OpenIdConnect\Command\Callback;

use Fluxlabs\FluxOpenIdConnectApi\Adapter\Api\RequestDto;

class CallbackCommand
{

    private RequestDto $request;


    public static function new(RequestDto $request) : static
    {
        $command = new static();

        $command->request = $request;

        return $command;
    }


    public function getRequest() : RequestDto
    {
        return $this->request;
    }
}
