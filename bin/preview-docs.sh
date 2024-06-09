#!/usr/bin/env bash

THIS_DIR=$(realpath "$(dirname "${BASH_SOURCE[0]}")")
cd "$THIS_DIR/../"


redocly preview-docs doc/openapi.yaml

#fswatch -o ./doc/openapi.yaml | xargs -n1 "$THIS_DIR/build-docs.sh"
#docker run -p:8080:8080 --rm -v $PWD/doc:/spec redocly/cli preview-docs /spec/openapi.yaml
