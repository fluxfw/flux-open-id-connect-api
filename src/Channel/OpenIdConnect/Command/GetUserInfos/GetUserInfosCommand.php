<?php

namespace Fluxlabs\FluxOpenIdConnectApi\Channel\OpenIdConnect\Command\GetUserInfos;

use Fluxlabs\FluxOpenIdConnectApi\Adapter\Api\RequestDto;

class GetUserInfosCommand
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
