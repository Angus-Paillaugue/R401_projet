#!/usr/bin/env bash

dockerComposeFile="$(dirname $0)/../../../docker-compose-dev.yaml"

docker compose -f $dockerComposeFile up --build --force-recreate --remove-orphans
