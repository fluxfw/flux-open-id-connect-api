<?php

namespace FluxOpenIdConnectApi;

require_once __DIR__ . "/../libs/flux-autoload-api/autoload.php";
require_once __DIR__ . "/../libs/flux-rest-api/autoload.php";

use FluxOpenIdConnectApi\Libs\FluxAutoloadApi\Adapter\Autoload\Psr4Autoload;
use FluxOpenIdConnectApi\Libs\FluxAutoloadApi\Adapter\Checker\PhpExtChecker;
use FluxOpenIdConnectApi\Libs\FluxAutoloadApi\Adapter\Checker\PhpVersionChecker;

PhpVersionChecker::new(
    ">=8.1"
)
    ->checkAndDie(
        __NAMESPACE__
    );
PhpExtChecker::new(
    [
        "curl",
        "json",
        "openssl"
    ]
)
    ->checkAndDie(
        __NAMESPACE__
    );

Psr4Autoload::new(
    [
        __NAMESPACE__ => __DIR__
    ]
)
    ->autoload();
