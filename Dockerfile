FROM phpswoole/swoole:latest-alpine

LABEL org.opencontainers.image.source="https://github.com/fluxapps/FluxOpenIdConnectApi"
LABEL maintainer="fluxlabs <support@fluxlabs.ch> (https://fluxlabs.ch)"

COPY --from=docker-registry.fluxpublisher.ch/flux-rest/api:latest /FluxRestApi /FluxOpenIdConnectApi/libs/FluxRestApi
COPY . /FluxOpenIdConnectApi

ENTRYPOINT ["/FluxOpenIdConnectApi/bin/entrypoint.php"]

EXPOSE 9501
