<?php

namespace FluxOpenIdConnectApi;

if (version_compare(PHP_VERSION, ($min_php_version = "8.0"), "<")) {
    die(__NAMESPACE__ . " needs at least PHP " . $min_php_version);
}

foreach (["curl", "json", "openssl", "swoole"] as $ext) {
    if (!extension_loaded($ext)) {
        die(__NAMESPACE__ . " needs PHP ext " . $ext);
    }
}

require_once __DIR__ . "/../libs/FluxRestApi/autoload.php";

spl_autoload_register(function (string $class) : void {
    if (str_starts_with($class, __NAMESPACE__ . "\\")) {
        require_once __DIR__ . str_replace("\\", "/", substr($class, strlen(__NAMESPACE__))) . ".php";
    }
});
