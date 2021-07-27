<?php

namespace Fluxlabs\FluxOpenIdConnectApi\Adapter\SessionCrypt;

class PlainSessionCrypt implements SessionCrypt
{

    use JsonSessionCrypt;

    public static function new() : static
    {
        $session_crypt = new static();

        return $session_crypt;
    }


    public function decrypt(string $encrypted_session) : string
    {
        return $encrypted_session;
    }


    public function encrypt(string $session) : string
    {
        return $session;
    }
}
