<?php

namespace Fluxlabs\FluxOpenIdConnectApi\Channel\OpenIdConnect\Command\GetUserInfos;

class GetUserInfosCommand
{

    private ?string $encrypted_session;


    public static function new(?string $encrypted_session) : static
    {
        $command = new static();

        $command->encrypted_session = $encrypted_session;

        return $command;
    }


    public function getEncryptedSession() : ?string
    {
        return $this->encrypted_session;
    }
}
