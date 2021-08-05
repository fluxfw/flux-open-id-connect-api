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
            $this->mapToProviderUrlProtocol($config["authorization_endpoint"] ?? null, $command->getProviderConfig()->getUrl()),
            $this->mapToProviderUrlProtocol($config["token_endpoint"] ?? null, $command->getProviderConfig()->getUrl()),
            $this->mapToProviderUrlProtocol($config["userinfo_endpoint"] ?? null, $command->getProviderConfig()->getUrl())
        );
    }


    private function mapToProviderUrlProtocol(?string $url, string $provider_url) : ?string
    {
        if (empty($url)) {
            return $url;
        }

        $matches = [];
        preg_match("/^https?:\/\//", $provider_url, $matches);
        if (empty($matches)) {
            return $url;
        }

        return preg_replace("/^https?:\/\//", $matches[0], $url);
    }
}
