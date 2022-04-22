ARG FLUX_AUTOLOAD_API_IMAGE=docker-registry.fluxpublisher.ch/flux-autoload/api
ARG FLUX_NAMESPACE_CHANGER_IMAGE=docker-registry.fluxpublisher.ch/flux-namespace-changer
ARG FLUX_REST_API_IMAGE=docker-registry.fluxpublisher.ch/flux-rest/api

FROM $FLUX_AUTOLOAD_API_IMAGE:latest AS flux_autoload_api
FROM $FLUX_NAMESPACE_CHANGER_IMAGE:latest AS flux_autoload_api_build
ENV FLUX_NAMESPACE_CHANGER_FROM_NAMESPACE FluxAutoloadApi
ENV FLUX_NAMESPACE_CHANGER_TO_NAMESPACE FluxOpenIdConnectApi\\Libs\\FluxAutoloadApi
COPY --from=flux_autoload_api /flux-autoload-api /code
RUN /flux-namespace-changer/bin/docker-entrypoint.php

FROM $FLUX_REST_API_IMAGE:latest AS flux_rest_api
FROM $FLUX_NAMESPACE_CHANGER_IMAGE:latest AS flux_rest_api_build
ENV FLUX_NAMESPACE_CHANGER_FROM_NAMESPACE FluxRestApi
ENV FLUX_NAMESPACE_CHANGER_TO_NAMESPACE FluxOpenIdConnectApi\\Libs\\FluxRestApi
COPY --from=flux_rest_api /flux-rest-api /code
RUN /flux-namespace-changer/bin/docker-entrypoint.php

FROM alpine:latest AS build

COPY --from=flux_autoload_api_build /code /flux-open-id-connect-api/libs/flux-autoload-api
COPY --from=flux_rest_api_build /code /flux-open-id-connect-api/libs/flux-rest-api
COPY . /flux-open-id-connect-api

FROM scratch

LABEL org.opencontainers.image.source="https://github.com/flux-eco/flux-open-id-connect-api"
LABEL maintainer="fluxlabs <support@fluxlabs.ch> (https://fluxlabs.ch)"

COPY --from=build /flux-open-id-connect-api /flux-open-id-connect-api

ARG COMMIT_SHA
LABEL org.opencontainers.image.revision="$COMMIT_SHA"
