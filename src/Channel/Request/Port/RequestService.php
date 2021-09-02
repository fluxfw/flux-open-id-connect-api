<?php

namespace Fluxlabs\FluxOpenIdConnectApi\Channel\Request\Port;

use Fluxlabs\FluxOpenIdConnectApi\Channel\Request\Command\RequestCommand;

class RequestService
{

    public static function new() : static
    {
        $service = new static();

        return $service;
    }


    public function request(string $url, ?string $authorization, ?array $post_data, ?bool $trust_self_signed_certificate) : array
    {
        return RequestCommand::new()
            ->request(
                $url,
                $authorization,
                $post_data,
                $trust_self_signed_certificate
            );
    }
}
