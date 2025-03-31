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
toZipFrontend=("app/vue" "app/index.php" "app/lib/cookies.php" "package.json" "composer.json" ".dockerignore" ".gitignore" "docker-compose.yaml" "jsconfig.json" "styles.css" ".prettierrc" ".prettierignore")
toZipAuth=("app/api/auth" "app/lib" "app/modele/UserDAO.php")
toZipGestion=("app/api/gestion" "app/modele" "app/lib")
toZipRoot=("README.pdf")

#!/usr/bin/env bash

outputDir="$(dirname $0)/build"

rm -r $outputDir
mkdir -p $outputDir/rendu/frontend
for file in ${toZipFrontend[@]}; do
  cp -r $file $outputDir/rendu/frontend
done

mkdir -p $outputDir/rendu/auth
for file in ${toZipAuth[@]}; do
  cp -r $file $outputDir/rendu/auth
done

mkdir -p $outputDir/rendu/gestion
for file in ${toZipGestion[@]}; do
  cp -r $file $outputDir/rendu/gestion
done

for file in ${toZipRoot[@]}; do
  cp -r $file $outputDir/rendu
done

# Zip the subdirectories directly
cd $outputDir/rendu
zip -r ../rendu.zip frontend auth gestion ${toZipRoot[@]}
cd -

rm -r $outputDir/rendu
