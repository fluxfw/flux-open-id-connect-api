<?php

namespace FluxOpenIdConnectApi\Adapter\Config;

class ProviderConfigDto
{

    public readonly string $client_id;
    public readonly string $client_secret;
    public readonly string $redirect_uri;
    public readonly string $scope;
    public readonly bool $supports_pkce;
    public readonly bool $trust_self_signed_certificate;
    public readonly string $url;


    public static function new(string $url, string $client_id, string $client_secret, string $redirect_uri, ?string $scope, ?bool $supports_pkce, ?bool $trust_self_signed_certificate = null) : static
    {
        $dto = new static();

        $dto->url = $url;
        $dto->client_id = $client_id;
        $dto->client_secret = $client_secret;
        $dto->redirect_uri = $redirect_uri;
        $dto->scope = $scope ?? "openid profile email";
        $dto->supports_pkce = $supports_pkce ?? true;
        $dto->trust_self_signed_certificate = $trust_self_signed_certificate ?? false;

        return $dto;
    }
}
