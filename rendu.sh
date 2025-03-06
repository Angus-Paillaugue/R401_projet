#!/usr/bin/env bash

outputDir="$(dirname $0)/build"

mkdir -p $outputDir

function prefixFiles() {
  prefix=$1
  files=("${@:2}")
  for i in "${!files[@]}"; do
    files[$i]="$prefix/${files[$i]}"
  done
  echo "${files[@]}"
}

# Make a zip of the frontend
toZipFrontend=("app/vue" "app/index.php" "app/lib/cookies.php" "package.json" "composer.json" ".dockerignore" ".gitignore" "docker-compose.yaml" "jsconfig.json" "README.md" "styles.css" ".prettierrc" ".prettierignore")
toZipFrontend=($(prefixFiles $(dirname $0) ${toZipFrontend[@]}))
# Zip the frontend
zip -r $outputDir/frontend.zip ${toZipFrontend[@]}

# Make a zip of the auth API
toZipAuth=("app/api/auth" "app/lib" "app/modele/UserDAO.php")
toZipAuth=($(prefixFiles $(dirname $0) ${toZipAuth[@]}))
# Zip the auth API
zip -r $outputDir/auth.zip ${toZipAuth[@]}

# Make a zip of the gestion API
toZipGestion=("app/api/gestion" "app/modele" "app/lib")
toZipGestion=($(prefixFiles $(dirname $0) ${toZipGestion[@]}))
# Zip the gestion API
zip -r $outputDir/gestion.zip ${toZipGestion[@]}


# Second version : one zip with one folder for each service
# mkdir -p $outputDir/rendu/frontend
# for file in ${toZipFrontend[@]}; do
#   cp -r $file $outputDir/rendu/frontend
# done

# mkdir -p $outputDir/rendu/auth
# for file in ${toZipAuth[@]}; do
#   cp -r $file $outputDir/rendu/auth
# done

# mkdir -p $outputDir/rendu/gestion
# for file in ${toZipGestion[@]}; do
#   cp -r $file $outputDir/rendu/gestion
# done
