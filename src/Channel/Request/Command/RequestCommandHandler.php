<?php

namespace Fluxlabs\FluxOpenIdConnectApi\Channel\Request\Command;

use Exception;

class RequestCommandHandler
{

    public static function new() : static
    {
        $handler = new static();

        return $handler;
    }


    public function handle(RequestCommand $command) : array
    {
        $curl = null;
        try {
            $curl = curl_init($command->getUrl());

            $headers = [
                "Accept"     => "application/json;charset=utf-8",
                "User-Agent" => "FluxOpenIdConnectApi"
            ];

            if (!empty($command->getAuthorization())) {
                $headers["Authorization"] = $command->getAuthorization();
            }

            if (!empty($command->getPostData())) {
                $headers["Content-Type"] = "application/json;charset=utf-8";

                curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($command->getPostData(), JSON_UNESCAPED_SLASHES));
            }

            curl_setopt($curl, CURLOPT_HTTPHEADER, array_map(fn(string $key, string $value) : string => $key . ": " . $value, array_keys($headers), $headers));

            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

            if ($command->isTrustSelfSignedCertificate()) {
                curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
                curl_setopt($curl, CURLOPT_PROXY_SSL_VERIFYHOST, false);
            }

            $response = curl_exec($curl);

            if (empty($response) || empty($response = json_decode($response, true))) {
                throw new Exception("Invalid response");
            }

            if (!empty($error_description = $response["error_description"] ?? null)) {
                throw new Exception("Request error description: " . $error_description);
            }

            if (!empty($error = $response["error"] ?? null)) {
                throw new Exception("Request error: " . $error);
            }

            if (!empty($message = $response["message"] ?? null)) {
                throw new Exception("Request message: " . $message);
            }

            return (array) $response;
        } finally {
            if ($curl !== null) {
                curl_close($curl);
            }
        }
    }
}
