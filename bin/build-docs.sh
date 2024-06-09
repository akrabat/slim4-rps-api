#!/usr/bin/env bash

THIS_DIR=$(realpath "$(dirname "${BASH_SOURCE[0]}")")
cd "$THIS_DIR/../"

trap 'rm -rf "/tmp/rps-builddocs-output.txt"' EXIT

redocly build-docs -o doc/rps.html doc/openapi.yaml 2> /tmp/rps-builddocs-output.txt || cat /tmp/rps-builddocs-output.txt

#docker run --rm -v $PWD/doc:/spec redocly/cli build-docs openapi.yaml 2> /tmp/rps-builddocs-output.txt || cat /tmp/rps-builddocs-output.txt
