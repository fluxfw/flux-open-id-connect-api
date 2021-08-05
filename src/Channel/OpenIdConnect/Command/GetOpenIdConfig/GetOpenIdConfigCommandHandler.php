<?php

namespace Fluxlabs\FluxOpenIdConnectApi\Channel\OpenIdConnect\Command\GetOpenIdConfig;

use Fluxlabs\FluxOpenIdConnectApi\Adapter\Api\OpenIdConfigDto;
use Fluxlabs\FluxOpenIdConnectApi\Channel\Request\Port\RequestService;

class GetOpenIdConfigCommandHandler
{

    private RequestService $request;


    public static function new(RequestService $request) : static
    {
        $handler = new static();

        $handler->request = $request;

        return $handler;
    }


    public function handle(GetOpenIdConfigCommand $command) : OpenIdConfigDto
    {
        $config = $this->request->request(
            $command->getProviderConfig()->getUrl() . "/.well-known/openid-configuration",
            null,
            null,
            $command->getProviderConfig()->isTrustSelfSignedCertificate()
        );

        return OpenIdConfigDto::new(
            $command->getProviderConfig(),
            $config["authorization_endpoint"] ?? null,
            $config["token_endpoint"] ?? null,
            $config["userinfo_endpoint"] ?? null
        );
    }
}
