<?php

namespace Fluxlabs\FluxOpenIdConnectApi\Adapter\Server;

use Fluxlabs\FluxOpenIdConnectApi\Adapter\Api\Api;
use Fluxlabs\FluxOpenIdConnectApi\Adapter\Config\Config;
use Fluxlabs\FluxOpenIdConnectApi\Adapter\Config\EnvConfig;
use Fluxlabs\FluxRestApi\Adapter\Collector\FolderRouteCollector;
use Fluxlabs\FluxRestApi\Adapter\Handler\SwooleHandler;
use Swoole\Http\Server as SwooleServer;

class Server
{

    private Config $config;
    private SwooleHandler $handler;


    public static function new(?Config $config = null) : static
    {
        $server = new static();

        $server->config = $config ?? EnvConfig::new();
        $server->handler = SwooleHandler::new(
            FolderRouteCollector::new(
                __DIR__ . "/../Route",
                [
                    Api::new(
                        $server->config
                    ),
                    $server->config->getCookieConfig()
                ]
            )
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

        $server->on("request", [$this->handler, "handle"]);

        $server->start();
    }
}
