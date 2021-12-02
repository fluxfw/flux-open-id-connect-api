<?php

namespace FluxOpenIdConnectApi\Adapter\Config;

use FluxRestApi\Cookie\Priority\CookiePriority;
use FluxRestApi\Cookie\Priority\DefaultCookiePriority;
use FluxRestApi\Cookie\SameSite\CookieSameSite;
use FluxRestApi\Cookie\SameSite\DefaultCookieSameSite;

class CookieConfigDto
{

    private readonly string $domain;
    private readonly ?int $expires_in;
    private readonly bool $http_only;
    private readonly string $name;
    private readonly string $path;
    private readonly CookiePriority $priority;
    private readonly CookieSameSite $same_site;
    private readonly bool $secure;


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


    public function getDomain() : string
    {
        return $this->domain;
    }


    public function getExpiresIn() : ?int
    {
        return $this->expires_in;
    }


    public function getName() : string
    {
        return $this->name;
    }


    public function getPath() : string
    {
        return $this->path;
    }


    public function getPriority() : CookiePriority
    {
        return $this->priority;
    }


    public function getSameSite() : CookieSameSite
    {
        return $this->same_site;
    }


    public function isHttpOnly() : bool
    {
        return $this->http_only;
    }


    public function isSecure() : bool
    {
        return $this->secure;
    }
}
