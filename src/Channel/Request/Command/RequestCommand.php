<?php

namespace FluxOpenIdConnectApi\Channel\Request\Command;

use Exception;
use FluxOpenIdConnectApi\Libs\FluxRestBaseApi\Body\DefaultBodyType;
use FluxOpenIdConnectApi\Libs\FluxRestBaseApi\Header\DefaultHeader;

class RequestCommand
{

    private function __construct()
    {

    }


    public static function new() : static
    {
        return new static();
    }


    public function request(string $url, ?string $authorization, ?array $post_data, ?bool $trust_self_signed_certificate) : array
    {
        $curl = null;
        try {
            $curl = curl_init($url);

            $headers = [
                DefaultHeader::ACCEPT->value     => DefaultBodyType::JSON->value,
                DefaultHeader::USER_AGENT->value => "FluxOpenIdConnectApi"
            ];

            if (!empty($authorization)) {
                $headers[DefaultHeader::AUTHORIZATION->value] = $authorization;
            }

            if (!empty($post_data)) {
                $headers[DefaultHeader::CONTENT_TYPE->value] = DefaultBodyType::JSON->value;

                curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($post_data, JSON_UNESCAPED_SLASHES));
            }

            curl_setopt($curl, CURLOPT_HTTPHEADER, array_map(fn(string $key, string $value) : string => $key . ": " . $value, array_keys($headers), $headers));

            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

            if ($trust_self_signed_certificate) {
                curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
                curl_setopt($curl, CURLOPT_PROXY_SSL_VERIFYHOST, false);
            }

            curl_setopt($curl, CURLOPT_FAILONERROR, true);

            $response = curl_exec($curl);

            if (curl_errno($curl) !== 0) {
                throw new Exception(curl_error($curl));
            }

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
