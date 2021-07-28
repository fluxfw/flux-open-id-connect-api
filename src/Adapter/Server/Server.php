<?php

namespace Fluxlabs\FluxOpenIdConnectApi\Adapter\Server;

use Fluxlabs\FluxOpenIdConnectApi\Adapter\Api\Api;
use Fluxlabs\FluxOpenIdConnectApi\Adapter\Api\RequestDto;
use Fluxlabs\FluxOpenIdConnectApi\Adapter\Api\ResponseDto;
use Fluxlabs\FluxOpenIdConnectApi\Adapter\Config\Config;
use Fluxlabs\FluxOpenIdConnectApi\Adapter\Config\EnvConfig;
use Swoole\Http\Request;
use Swoole\Http\Response;
use Swoole\Http\Server as SwooleServer;

class Server
{

    private Api $api;
    private Config $config;


    public static function new(?Config $config = null, ?Api $api = null) : static
    {
        $server = new static();

        $server->config = $config ?? EnvConfig::new();
        $server->api = $api ?? Api::new(
                $server->config
            );

        return $server;
    }


    public function init() : void
    {
        $options = [];
        $sock_type = SWOOLE_TCP;

        if ($this->config->getServerConfig()->getHttpsCert() !== null) {
            $options += [
                "ssl_cert_file" => $this->config->getServerConfig()->getHttpsCert(),
                "ssl_key_file"  => $this->config->getServerConfig()->getHttpsKey()
            ];
            $sock_type += SWOOLE_SSL;
        }

        $server = new SwooleServer($this->config->getServerConfig()->getListen(), $this->config->getServerConfig()->getPort(), SWOOLE_PROCESS, $sock_type);

        $server->set($options);

        $server->on("request", function (Request $request, Response $response) : void {
            $this->request($request, $response);
        });

        $server->start();
    }


    private function callbackRequest(Request $request, Response $response) : void
    {
        $api_request = $this->mapRequest(
            $request
        );

        $api_response = $this->api->callback(
            $api_request
        );

        $this->mapResponse(
            $response,
            $api_response
        );
    }


    private function loginRequest(Response $response) : void
    {
        $api_response = $this->api->login();

        $this->mapResponse(
            $response,
            $api_response
        );
    }


    private function logoutRequest(Response $response) : void
    {
        $api_response = $this->api->logout();

        $this->mapResponse(
            $response,
            $api_response
        );
    }


    private function mapRequest(Request $request) : RequestDto
    {
        return RequestDto::new(
            $request->cookie[$this->config->getCookieConfig()->getName()] ?? null,
            $request->get ?? null
        );
    }


    private function mapResponse(Response $response, ResponseDto $api_response) : void
    {
        if (!empty($api_response->getEncryptedSession())) {
            $response->cookie(
                $this->config->getCookieConfig()->getName(),
                $api_response->getEncryptedSession(),
                $this->config->getCookieConfig()->getExpires(),
                $this->config->getCookieConfig()->getPath(),
                $this->config->getCookieConfig()->getDomain(),
                $this->config->getCookieConfig()->isSecure(),
                $this->config->getCookieConfig()->isHttpOnly(),
                $this->config->getCookieConfig()->getSameSite(),
                $this->config->getCookieConfig()->getPriority()
            );
        } else {
            $response->cookie(
                $this->config->getCookieConfig()->getName(),
                null,
                null,
                $this->config->getCookieConfig()->getPath(),
                $this->config->getCookieConfig()->getDomain()
            );
        }

        if (!empty($api_response->getRedirectUrl())) {
            $response->status(302);
            $response->header["Location"] = $api_response->getRedirectUrl();
            $response->end();

            return;
        }

        if (!empty($api_response->getBody())) {
            $response->header("Content-Type", "application/json;charset=utf-8");
            $response->write(json_encode($api_response->getBody(), JSON_UNESCAPED_SLASHES));
        } else {
            $response->status(401);
        }
        $response->end();
    }


    private function request(Request $request, Response $response) : void
    {
        switch (true) {
            case $request->server["request_uri"] === "/login" && $request->getMethod() === "GET":
                $this->loginRequest($response);
                break;

            case $request->server["request_uri"] === "/callback" && $request->getMethod() === "GET":
                $this->callbackRequest($request, $response);
                break;

            case $request->server["request_uri"] === "/userinfos" && $request->getMethod() === "GET":
                $this->userInfosRequest($request, $response);
                break;

            case $request->server["request_uri"] === "/logout" && $request->getMethod() === "GET":
                $this->logoutRequest($response);
                break;

            default:
                $response->status(403);
                $response->end();
                break;
        }
    }


    private function userInfosRequest(Request $request, Response $response) : void
    {
        $api_request = $this->mapRequest(
            $request
        );

        $api_response = $this->api->getUserInfos(
            $api_request
        );

        $this->mapResponse(
            $response,
            $api_response
        );
    }
}
