<?php

namespace Fluxlabs\FluxOpenIdConnectApi\Channel\OpenIdConnect\Command\GetOpenIdConfig;

use Fluxlabs\FluxOpenIdConnectApi\Adapter\Config\ProviderConfigDto;

class GetOpenIdConfigCommand
{

    private ProviderConfigDto $provider_config;


    public static function new(ProviderConfigDto $provider_config) : static
    {
        $command = new static();

        $command->provider_config = $provider_config;

        return $command;
    }


    public function getProviderConfig() : ProviderConfigDto
    {
        return $this->provider_config;
    }
}
