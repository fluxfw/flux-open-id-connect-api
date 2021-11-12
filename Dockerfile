ARG REST_API_IMAGE
FROM $REST_API_IMAGE AS rest_api

FROM phpswoole/swoole:latest-alpine

LABEL org.opencontainers.image.source="https://github.com/fluxapps/FluxOpenIdConnectApi"
LABEL maintainer="fluxlabs <support@fluxlabs.ch> (https://fluxlabs.ch)"

COPY --from=rest_api /FluxRestApi /FluxOpenIdConnectApi/libs/FluxRestApi
COPY . /FluxOpenIdConnectApi

ENTRYPOINT ["/FluxOpenIdConnectApi/bin/entrypoint.php"]

EXPOSE 9501
