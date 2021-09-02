<?php

namespace Fluxlabs\FluxOpenIdConnectApi\Adapter\Route;

use Fluxlabs\FluxOpenIdConnectApi\Adapter\Api\Api;
use Fluxlabs\FluxOpenIdConnectApi\Adapter\Config\CookieConfigDto;
use Fluxlabs\FluxRestApi\Body\JsonBodyDto;
use Fluxlabs\FluxRestApi\Body\TextBodyDto;
use Fluxlabs\FluxRestApi\Method\Method;
use Fluxlabs\FluxRestApi\Request\RequestDto;
use Fluxlabs\FluxRestApi\Response\ResponseDto;
use Fluxlabs\FluxRestApi\Route\Route;
use Fluxlabs\FluxRestApi\Status\Status;

class UserInfosRoute implements Route
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
                Status::_401
            );
        }
    }
}
