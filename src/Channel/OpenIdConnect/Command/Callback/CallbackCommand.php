<?php

namespace Fluxlabs\FluxOpenIdConnectApi\Channel\OpenIdConnect\Command\Callback;

class CallbackCommand
{

    private ?string $encrypted_session;
    private array $query;


    public static function new(?string $encrypted_session, array $query) : static
    {
        $command = new static();

        $command->encrypted_session = $encrypted_session;
        $command->query = $query;

        return $command;
    }


    public function getEncryptedSession() : ?string
    {
        return $this->encrypted_session;
    }


    public function getQuery() : array
    {
        return $this->query;
    }
}
