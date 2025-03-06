#!/usr/bin/env bash

dockerfilesDirectory="$(dirname $0)/../../"
contectDirectory="$(dirname $0)/../../../"

# Build the frontend
docker build -t anguspllg/r401_frontend -f $dockerfilesDirectory/Dockerfile-frontend $contectDirectory

# Build the auth api
docker build -t anguspllg/r401_auth_api -f $dockerfilesDirectory/Dockerfile-api-auth $contectDirectory

# Build the gestion api
docker build -t anguspllg/r401_gestion_api -f $dockerfilesDirectory/Dockerfile-api-gestion $contectDirectory
