<?php

namespace FluxOpenIdConnectApi\Adapter\Route;

use FluxOpenIdConnectApi\Adapter\Api\Api;
use FluxOpenIdConnectApi\Adapter\Config\CookieConfigDto;
use FluxRestApi\Body\JsonBodyDto;
use FluxRestApi\Body\TextBodyDto;
use FluxRestApi\Request\RequestDto;
use FluxRestApi\Response\ResponseDto;
use FluxRestApi\Route\Route;
use FluxRestBaseApi\Method\DefaultMethod;
use FluxRestBaseApi\Method\Method;
use FluxRestBaseApi\Status\DefaultStatus;

class UserInfosRoute implements Route
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
        return "/userinfos";
    }


    public function handle(RequestDto $request) : ?ResponseDto
    {
        $user_infos = $this->api->getUserInfos(
            $request->getCookie(
                $this->cookie_config->getName()
            )
        );

        if ($user_infos !== null) {
            return ResponseDto::new(
                JsonBodyDto::new(
                    $user_infos
                )
            );
        } else {
            return ResponseDto::new(
                TextBodyDto::new(
                    "Authorization needed"
                ),
                DefaultStatus::_401
            );
        }
    }
}
