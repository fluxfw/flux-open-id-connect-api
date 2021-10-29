#!/usr/bin/env sh

set -e

composer install -d "$(dirname $0)/.." --no-dev

"$(dirname $0)/../vendor/fluxlabs/fluxrestapi/bin/build.sh"
