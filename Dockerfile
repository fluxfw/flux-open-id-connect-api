FROM php:8.2-cli-alpine AS build

RUN (mkdir -p /flux-namespace-changer && cd /flux-namespace-changer && wget -O - https://github.com/fluxfw/flux-namespace-changer/releases/download/v2022-07-12-1/flux-namespace-changer-v2022-07-12-1-build.tar.gz | tar -xz --strip-components=1)

RUN (mkdir -p /build/flux-open-id-connect-api/libs/flux-autoload-api && cd /build/flux-open-id-connect-api/libs/flux-autoload-api && wget -O - https://github.com/fluxfw/flux-autoload-api/releases/download/v2022-12-12-1/flux-autoload-api-v2022-12-12-1-build.tar.gz | tar -xz --strip-components=1 && /flux-namespace-changer/bin/change-namespace.php . FluxAutoloadApi FluxOpenIdConnectApi\\Libs\\FluxAutoloadApi)

RUN (mkdir -p /build/flux-open-id-connect-api/libs/flux-rest-api && cd /build/flux-open-id-connect-api/libs/flux-rest-api && wget -O - https://github.com/fluxfw/flux-rest-api/releases/download/v2022-12-12-1/flux-rest-api-v2022-12-12-1-build.tar.gz | tar -xz --strip-components=1 && /flux-namespace-changer/bin/change-namespace.php . FluxRestApi FluxOpenIdConnectApi\\Libs\\FluxRestApi)

COPY . /build/flux-open-id-connect-api

FROM scratch

COPY --from=build /build /
