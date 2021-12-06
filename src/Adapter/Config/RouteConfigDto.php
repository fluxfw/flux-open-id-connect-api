<?php

namespace FluxOpenIdConnectApi\Adapter\Config;

class RouteConfigDto
{

    public readonly string $after_login_url;
    public readonly string $after_logout_url;


    public static function new(?string $after_login_url = null, ?string $after_logout_url = null) : static
    {
        $dto = new static();

        $dto->after_login_url = $after_login_url ?? "/";
        $dto->after_logout_url = $after_logout_url ?? "/";

        return $dto;
    }
}
