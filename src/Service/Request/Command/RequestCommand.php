<?php

namespace FluxOpenIdConnectApi\Service\Request\Command;

use Exception;
use FluxOpenIdConnectApi\Libs\FluxRestApi\Adapter\Api\RestApi;
use FluxOpenIdConnectApi\Libs\FluxRestApi\Adapter\Body\Type\DefaultBodyType;
use FluxOpenIdConnectApi\Libs\FluxRestApi\Adapter\Client\ClientRequestDto;
use FluxOpenIdConnectApi\Libs\FluxRestApi\Adapter\Header\DefaultHeaderKey;
use FluxOpenIdConnectApi\Libs\FluxRestApi\Adapter\Method\DefaultMethod;

class RequestCommand
{

    private function __construct(
        private readonly RestApi $rest_api
    ) {

    }


    public static function new(
        RestApi $rest_api
    ) : static {
        return new static(
            $rest_api
        );
    }


    public function request(string $url, ?array $query_params, ?string $authorization, ?array $post_data, ?bool $trust_self_signed_certificate) : array
    {
        $headers = [
            DefaultHeaderKey::ACCEPT->value     => DefaultBodyType::JSON->value,
            DefaultHeaderKey::USER_AGENT->value => "flux-open-id-connect-api"
        ];

        if (!empty($authorization)) {
            $headers[DefaultHeaderKey::AUTHORIZATION->value] = $authorization;
        }

        if ($post_data !== null) {
            $method = DefaultMethod::POST;
            $headers[DefaultHeaderKey::CONTENT_TYPE->value] = DefaultBodyType::JSON->value;
            $post_data = json_encode($post_data, JSON_UNESCAPED_SLASHES);
        } else {
            $method = DefaultMethod::GET;
        }

        $response = $this->rest_api->makeRequest(
            ClientRequestDto::new(
                $url,
                $method,
                $query_params,
                $post_data,
                $headers,
                true,
                true,
                false,
                $trust_self_signed_certificate
            )
        );

        if (empty($response = $response?->body) || empty($response = json_decode($response, true))) {
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
    }
}
