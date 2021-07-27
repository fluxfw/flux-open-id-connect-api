FROM phpswoole/swoole:4.7-php8.0-alpine

LABEL org.opencontainers.image.source="https://github.com/fluxapps/FluxOpenIdConnectApi"
LABEL maintainer="fluxlabs <support@fluxlabs.ch> (https://fluxlabs.ch)"

WORKDIR /app

COPY . .

RUN composer install --no-dev

WORKDIR bin

EXPOSE 9501

RUN chmod +x entrypoint.php
ENTRYPOINT ["./entrypoint.php"]
