<?php

namespace FluxOpenIdConnectApi\Adapter\Route;

use FluxOpenIdConnectApi\Adapter\Api\Api;
use FluxOpenIdConnectApi\Adapter\Config\CookieConfigDto;
use FluxRestApi\Cookie\CookieDto;
use FluxRestApi\Request\RequestDto;
use FluxRestApi\Response\ResponseDto;
use FluxRestApi\Route\Route;
use FluxRestBaseApi\Header\Header;
use FluxRestBaseApi\Method\Method;
use FluxRestBaseApi\Status\Status;

class LogoutRoute implements Route
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


    public function getDocuRequestBodyTypes() : ?array
    {
        return null;
    }


    public function getDocuRequestQueryParams() : ?array
    {
        return null;
    }


    public function getMethod() : string
    {
        return Method::GET;
    }


    public function getRoute() : string
    {
        return "/logout";
    }


    public function handle(RequestDto $request) : ?ResponseDto
    {
        return ResponseDto::new(
            null,
            Status::_302,
            [
                Header::LOCATION => $this->api->logout()
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
