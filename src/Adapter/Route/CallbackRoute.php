<?php

namespace Fluxlabs\FluxOpenIdConnectApi\Adapter\Route;

use Fluxlabs\FluxOpenIdConnectApi\Adapter\Api\Api;
use Fluxlabs\FluxOpenIdConnectApi\Adapter\Config\CookieConfigDto;
use Fluxlabs\FluxRestApi\Body\TextBodyDto;
use Fluxlabs\FluxRestApi\Cookie\CookieDto;
use Fluxlabs\FluxRestApi\Header\Header;
use Fluxlabs\FluxRestApi\Method\Method;
use Fluxlabs\FluxRestApi\Request\RequestDto;
use Fluxlabs\FluxRestApi\Response\ResponseDto;
use Fluxlabs\FluxRestApi\Route\Route;
use Fluxlabs\FluxRestApi\Status\Status;

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
