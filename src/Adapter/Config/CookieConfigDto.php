<?php

namespace FluxOpenIdConnectApi\Adapter\Config;

use FluxRestApi\Cookie\Priority\CookiePriority;
use FluxRestApi\Cookie\Priority\DefaultCookiePriority;
use FluxRestApi\Cookie\SameSite\CookieSameSite;
use FluxRestApi\Cookie\SameSite\DefaultCookieSameSite;

class CookieConfigDto
{

    public readonly string $domain;
    public readonly ?int $expires_in;
    public readonly bool $http_only;
    public readonly string $name;
    public readonly string $path;
    public readonly CookiePriority $priority;
    public readonly CookieSameSite $same_site;
    public readonly bool $secure;


    public static function new(
        ?string $name = null,
        ?int $expires_in = null,
        ?string $path = null,
        ?string $domain = null,
        ?bool $secure = null,
        ?bool $http_only = null,
        ?CookieSameSite $same_site = null,
        ?CookiePriority $priority = null
    ) : static {
        $dto = new static();

        $dto->name = $name ?? "auth";
        $dto->expires_in = $expires_in;
        $dto->path = $path ?? "/";
        $dto->domain = $domain ?? "";
        $dto->secure = $secure ?? true;
        $dto->http_only = $http_only ?? true;
        $dto->same_site = $same_site ?? DefaultCookieSameSite::LAX;
        $dto->priority = $priority ?? DefaultCookiePriority::MEDIUM;

        return $dto;
    }
}
