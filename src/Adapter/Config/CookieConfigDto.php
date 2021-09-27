<?php

namespace Fluxlabs\FluxOpenIdConnectApi\Adapter\Config;

class CookieConfigDto
{

    const PRIORITY_HIGH = "High";
    const PRIORITY_LOW = "Low";
    const PRIORITY_MEDIUM = "Medium";
    const SAME_SITE_LAX = "Lax";
    const SAME_SITE_NONE = "None";
    const SAME_SITE_STRICT = "Strict";
    private ?string $domain;
    private ?int $expires_in;
    private bool $http_only;
    private string $name;
    private string $path;
    private string $priority;
    private string $same_site;
    private bool $secure;


    public static function new(
        ?string $name = null,
        ?int $expires_in = null,
        ?string $path = null,
        ?string $domain = null,
        ?bool $secure = null,
        ?bool $http_only = null,
        ?string $same_site = null,
        ?string $priority = null
    ) : static {
        $dto = new static();

        $dto->name = $name ?? "auth";
        $dto->expires_in = $expires_in;
        $dto->path = $path ?? "/";
        $dto->domain = $domain;
        $dto->secure = $secure ?? true;
        $dto->http_only = $http_only ?? true;
        $dto->same_site = $same_site ?? static::SAME_SITE_LAX;
        $dto->priority = $priority ?? static::PRIORITY_MEDIUM;

        return $dto;
    }


    public function getDomain() : ?string
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


    public function getPriority() : string
    {
        return $this->priority;
    }


    public function getSameSite() : string
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
