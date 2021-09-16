<?php

namespace Fluxlabs\FluxOpenIdConnectApi\Channel\OpenIdConnect\Command;

use Fluxlabs\FluxOpenIdConnectApi\Adapter\Api\OpenIdConfigDto;
use Fluxlabs\FluxOpenIdConnectApi\Adapter\Config\ProviderConfigDto;
use Fluxlabs\FluxOpenIdConnectApi\Channel\Request\Port\RequestService;

class GetOpenIdConfigCommand
{

    private RequestService $request;


    public static function new(RequestService $request) : static
    {
        $command = new static();

        $command->request = $request;

        return $command;
    }


    public function getOpenIdConfig(ProviderConfigDto $provider_config) : OpenIdConfigDto
    {
        $config = $this->request->request(
            $provider_config->getUrl() . "/.well-known/openid-configuration",
            null,
            null,
            $provider_config->isTrustSelfSignedCertificate()
        );

        return OpenIdConfigDto::new(
            $provider_config,
            $this->mapToProviderUrlProtocol(
                $config["authorization_endpoint"] ?? null,
                $provider_config->getUrl()
            ),
            $this->mapToProviderUrlProtocol(
                $config["token_endpoint"] ?? null,
                $provider_config->getUrl()
            ),
            $this->mapToProviderUrlProtocol(
                $config["userinfo_endpoint"] ?? null,
                $provider_config->getUrl()
            )
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
