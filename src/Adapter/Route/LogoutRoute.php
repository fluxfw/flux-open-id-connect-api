<?php

namespace FluxOpenIdConnectApi\Adapter\Route;

use FluxOpenIdConnectApi\Adapter\Api\Api;
use FluxOpenIdConnectApi\Adapter\Config\CookieConfigDto;
use FluxRestApi\Cookie\CookieDto;
use FluxRestApi\Request\RequestDto;
use FluxRestApi\Response\ResponseDto;
use FluxRestApi\Route\Route;
use FluxRestBaseApi\Header\DefaultHeader;
use FluxRestBaseApi\Method\DefaultMethod;
use FluxRestBaseApi\Method\Method;
use FluxRestBaseApi\Status\DefaultStatus;

class LogoutRoute implements Route
{

    private readonly Api $api;
    private readonly CookieConfigDto $cookie_config;


    public static function new(Api $api, CookieConfigDto $cookie_config) : static
    {
        $route = new static();

        $route->api = $api;
        $route->cookie_config = $cookie_config;

        return $route;
    }


    public function getDocuRequestBodyTypes() : ?array
    {
        return null;
    }


    public function getDocuRequestQueryParams() : ?array
    {
        return null;
    }


    public function getMethod() : Method
    {
        return DefaultMethod::GET;
    }


    public function getRoute() : string
    {
        return "/logout";
    }


    public function handle(RequestDto $request) : ?ResponseDto
    {
        return ResponseDto::new(
            null,
            DefaultStatus::_302,
            [
                DefaultHeader::LOCATION->value => $this->api->logout()
            ],
            [
                CookieDto::new(
                    $this->cookie_config->getName(),
                    null,
                    null,
                    $this->cookie_config->getPath(),
                    $this->cookie_config->getDomain()
                )
            ]
        );
    }
}
