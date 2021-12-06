<?php

namespace FluxOpenIdConnectApi\Adapter\Route;

use FluxOpenIdConnectApi\Adapter\Api\Api;
use FluxOpenIdConnectApi\Adapter\Config\CookieConfigDto;
use FluxRestApi\Body\TextBodyDto;
use FluxRestApi\Cookie\CookieDto;
use FluxRestApi\Request\RequestDto;
use FluxRestApi\Response\ResponseDto;
use FluxRestApi\Route\Route;
use FluxRestBaseApi\Header\DefaultHeader;
use FluxRestBaseApi\Method\DefaultMethod;
use FluxRestBaseApi\Method\Method;
use FluxRestBaseApi\Status\DefaultStatus;

class CallbackRoute implements Route
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
        return [
            "code",
            "error",
            "error_description",
            "state"
        ];
    }


    public function getMethod() : Method
    {
        return DefaultMethod::GET;
    }


    public function getRoute() : string
    {
        return "/callback";
    }


    public function handle(RequestDto $request) : ?ResponseDto
    {
        [$encrypted_session, $redirect_url] = $this->api->callback(
            $request->getCookie(
                $this->cookie_config->name
            ),
            $request->getQueryParams()
        );

        if ($redirect_url !== null) {
            return ResponseDto::new(
                null,
                DefaultStatus::_302,
                [
                    DefaultHeader::LOCATION->value => $redirect_url
                ],
                [
                    CookieDto::new(
                        $this->cookie_config->name,
                        $encrypted_session,
                        $this->cookie_config->expires_in,
                        $this->cookie_config->path,
                        $this->cookie_config->domain,
                        $this->cookie_config->secure,
                        $this->cookie_config->http_only,
                        $this->cookie_config->same_site,
                        $this->cookie_config->priority
                    )
                ]
            );
        } else {
            return ResponseDto::new(
                TextBodyDto::new(
                    "Invalid authorization"
                ),
                DefaultStatus::_403,
                null,
                [
                    CookieDto::new(
                        $this->cookie_config->name,
                        null,
                        null,
                        $this->cookie_config->path,
                        $this->cookie_config->domain
                    )
                ]
            );
        }
    }
}
