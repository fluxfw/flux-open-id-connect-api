<?php

namespace FluxOpenIdConnectApi\Channel\Request\Command;

use Exception;
use FluxRestApi\Header\Header;

class RequestCommand
{

    public static function new() : static
    {
        $command = new static();

        return $command;
    }


    public function request(string $url, ?string $authorization, ?array $post_data, ?bool $trust_self_signed_certificate) : array
    {
        $curl = null;
        try {
            $curl = curl_init($url);

            $headers = [
                Header::ACCEPT     => "application/json",
                Header::USER_AGENT => "FluxOpenIdConnectApi"
            ];

            if (!empty($authorization)) {
                $headers[Header::AUTHORIZATION] = $authorization;
            }

            if (!empty($post_data)) {
                $headers[Header::CONTENT_TYPE] = "application/json";

                curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($post_data, JSON_UNESCAPED_SLASHES));
            }

            curl_setopt($curl, CURLOPT_HTTPHEADER, array_map(fn(string $key, string $value) : string => $key . ": " . $value, array_keys($headers), $headers));

            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

            if ($trust_self_signed_certificate) {
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
