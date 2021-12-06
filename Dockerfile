ARG FLUX_REST_API_IMAGE=docker-registry.fluxpublisher.ch/flux-rest/api:latest
ARG PHP_CLI_IMAGE=php:cli-alpine
ARG SWOOLE_SOURCE_URL=https://github.com/swoole/swoole-src/archive/master.tar.gz

FROM $FLUX_REST_API_IMAGE AS flux_rest_api

FROM $PHP_CLI_IMAGE
ARG SWOOLE_SOURCE_URL

LABEL org.opencontainers.image.source="https://github.com/fluxapps/flux-open-id-connect-api"
LABEL maintainer="fluxlabs <support@fluxlabs.ch> (https://fluxlabs.ch)"

RUN apk add --no-cache libstdc++ && \
    apk add --no-cache --virtual .build-deps $PHPIZE_DEPS curl-dev openssl-dev && \
    (mkdir -p /usr/src/php/ext/swoole && cd /usr/src/php/ext/swoole && wget -O - $SWOOLE_SOURCE_URL | tar -xz --strip-components=1) && \
    docker-php-ext-configure swoole --enable-openssl --enable-swoole-curl --enable-swoole-json && \
    docker-php-ext-install -j$(nproc) swoole && \
    docker-php-source delete && \
    apk del .build-deps

COPY --from=flux_rest_api /flux-rest-api /flux-open-id-connect-api/libs/flux-rest-api
COPY . /flux-open-id-connect-api

ENTRYPOINT ["/flux-open-id-connect-api/bin/entrypoint.php"]

EXPOSE 9501
