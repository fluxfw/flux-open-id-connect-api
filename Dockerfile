FROM phpswoole/swoole:4.8-php8.0-alpine

LABEL org.opencontainers.image.source="https://github.com/fluxapps/FluxOpenIdConnectApi"
LABEL maintainer="fluxlabs <support@fluxlabs.ch> (https://fluxlabs.ch)"

COPY . /FluxOpenIdConnectApi

ENTRYPOINT ["/FluxOpenIdConnectApi/bin/entrypoint.php"]

RUN /FluxOpenIdConnectApi/bin/build.sh

EXPOSE 9501
