<?php

namespace Fluxlabs\FluxOpenIdConnectApi\Adapter\Route;

use Fluxlabs\FluxOpenIdConnectApi\Adapter\Api\Api;
use Fluxlabs\FluxOpenIdConnectApi\Adapter\Config\CookieConfigDto;
use Fluxlabs\FluxRestApi\Cookie\CookieDto;
use Fluxlabs\FluxRestApi\Request\RequestDto;
use Fluxlabs\FluxRestApi\Response\ResponseDto;
use Fluxlabs\FluxRestApi\Route\Route;

class LoginRoute implements Route
{

    private Api $api;
    private CookieConfigDto $cookie_config;


    public static function new(Api $api, CookieConfigDto $cookie_config) : static
    {
        $route = new static();

        $route->api = $api;
        $route->cookie_config = $cookie_config;

        return $route;
    }


    public function getBodyType() : ?string
    {
        return null;
    }


    public function getMethod() : string
    {
        return "GET";
    }


    public function getRoute() : string
    {
        return "/login";
    }


    public function handle(RequestDto $request) : ResponseDto
    {
        [$encrypted_session, $authorize_url] = $this->api->login();

        return ResponseDto::new(
            null,
            302,
            [
                "Location" => $authorize_url
            ],
            [
                CookieDto::new(
                    $this->cookie_config->getName(),
                    $encrypted_session,
                    $this->cookie_config->getExpires(),
                    $this->cookie_config->getPath(),
                    $this->cookie_config->getDomain(),
                    $this->cookie_config->isSecure(),
                    $this->cookie_config->isHttpOnly(),
                    $this->cookie_config->getSameSite(),
                    $this->cookie_config->getPriority()
                )
            ]
        );
    }
}
