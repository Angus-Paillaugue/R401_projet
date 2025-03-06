#!/usr/bin/env bash

dockerComposeFile="$(dirname $0)/../../../docker-compose-dev.yaml"

docker compose -f $dockerCompseFile build
