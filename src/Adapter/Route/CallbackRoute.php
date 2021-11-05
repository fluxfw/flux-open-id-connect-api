<?php

namespace FluxOpenIdConnectApi\Adapter\Route;

use FluxOpenIdConnectApi\Adapter\Api\Api;
use FluxOpenIdConnectApi\Adapter\Config\CookieConfigDto;
use FluxRestApi\Body\TextBodyDto;
use FluxRestApi\Cookie\CookieDto;
use FluxRestApi\Request\RequestDto;
use FluxRestApi\Response\ResponseDto;
use FluxRestApi\Route\Route;
use FluxRestBaseApi\Header\Header;
use FluxRestBaseApi\Method\Method;
use FluxRestBaseApi\Status\Status;

class CallbackRoute implements Route
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
        return [
            "code",
            "error",
            "error_description",
            "state"
        ];
    }


    public function getMethod() : string
    {
        return Method::GET;
    }


    public function getRoute() : string
    {
        return "/callback";
    }


    public function handle(RequestDto $request) : ?ResponseDto
    {
        [$encrypted_session, $redirect_url] = $this->api->callback(
            $request->getCookie(
                $this->cookie_config->getName()
            ),
            $request->getQueryParams()
        );

        if ($redirect_url !== null) {
            return ResponseDto::new(
                null,
                Status::_302,
                [
                    Header::LOCATION => $redirect_url
                ],
                [
                    CookieDto::new(
                        $this->cookie_config->getName(),
                        $encrypted_session,
                        $this->cookie_config->getExpiresIn(),
                        $this->cookie_config->getPath(),
                        $this->cookie_config->getDomain(),
                        $this->cookie_config->isSecure(),
                        $this->cookie_config->isHttpOnly(),
                        $this->cookie_config->getSameSite(),
                        $this->cookie_config->getPriority()
                    )
                ]
            );
        } else {
            return ResponseDto::new(
                TextBodyDto::new(
                    "Invalid authorization"
                ),
                Status::_403,
                null,
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
}
