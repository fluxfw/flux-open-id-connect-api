<?php

namespace Fluxlabs\FluxOpenIdConnectApi\Channel\Request\Command;

class RequestCommand
{

    private ?string $authorization;
    private ?array $post_data;
    private bool $trust_self_signed_certificate;
    private string $url;


    public static function new(string $url, ?string $authorization, ?array $post_data, ?bool $trust_self_signed_certificate) : static
    {
        $command = new static();

        $command->url = $url;
        $command->authorization = $authorization;
        $command->post_data = $post_data;
        $command->trust_self_signed_certificate = $trust_self_signed_certificate ?? false;

        return $command;
    }


    public function getAuthorization() : ?string
    {
        return $this->authorization;
    }


    public function getPostData() : ?array
    {
        return $this->post_data;
    }


    public function getUrl() : string
    {
        return $this->url;
    }


    public function isTrustSelfSignedCertificate() : bool
    {
        return $this->trust_self_signed_certificate;
    }
}
