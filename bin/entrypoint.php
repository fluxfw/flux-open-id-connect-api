#!/usr/bin/env php
<?php

require_once __DIR__ . "/../vendor/autoload.php";

use Fluxlabs\FluxOpenIdConnectApi\Adapter\Server\Server;

Server::new()
    ->init();
