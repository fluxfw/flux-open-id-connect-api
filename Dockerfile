FROM phpswoole/swoole:4.8-php8.0-alpine

LABEL org.opencontainers.image.source="https://github.com/fluxapps/FluxOpenIdConnectApi"
LABEL maintainer="fluxlabs <support@fluxlabs.ch> (https://fluxlabs.ch)"

COPY . /app
ENTRYPOINT ["/app/bin/entrypoint.php"]

RUN composer install -d /app --no-dev

EXPOSE 9501
