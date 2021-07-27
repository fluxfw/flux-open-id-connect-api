<?php

namespace Fluxlabs\FluxOpenIdConnectApi\Channel\Request\Port;

use Fluxlabs\FluxOpenIdConnectApi\Channel\Request\Command\RequestCommand;
use Fluxlabs\FluxOpenIdConnectApi\Channel\Request\Command\RequestCommandHandler;
use Fluxlabs\FluxOpenIdConnectApi\Channel\Request\Dto\ResponseDto;

class RequestService
{

    public static function new() : static
    {
        $service = new static();

        return $service;
    }


    public function request(string $url, ?string $authorization, ?array $post_data, ?bool $trust_self_signed_certificate) : array
    {
        return RequestCommandHandler::new()
            ->handle(
                RequestCommand::new(
                    $url,
                    $authorization,
                    $post_data,
                    $trust_self_signed_certificate
                )
            );
    }
}
