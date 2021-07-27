<?php

namespace Fluxlabs\FluxOpenIdConnectApi\Adapter\Config;

class RouteConfigDto
{

    private string $after_login_url;
    private string $after_logout_url;


    public static function new(?string $after_login_url = null, ?string $after_logout_url = null) : static
    {
        $dto = new static();

        $dto->after_login_url = $after_login_url ?? "/";
        $dto->after_logout_url = $after_logout_url ?? "/";

        return $dto;
    }


    public function getAfterLoginUrl() : string
    {
        return $this->after_login_url;
    }


    public function getAfterLogoutUrl() : string
    {
        return $this->after_logout_url;
    }
}
