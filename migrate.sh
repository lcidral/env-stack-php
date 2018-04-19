#!/usr/bin/env bash

export TERM=xterm-color

set -x

clear

docker exec -i /engieorcamentodocker_mariadb_1 /usr/bin/mysql -u root --password=admin --execute="DROP SCHEMA IF EXISTS $DB_NAME; CREATE DATABASE $DB_NAME"

cd ../engie-orcamento-api

docker exec -i /engieorcamentodocker_php_1 /var/www/html/vendor/bin/phinx --configuration=/var/www/html/phinx.yml migrate -e development -vvv
docker exec -i /engieorcamentodocker_php_1 /var/www/html/vendor/bin/phinx --configuration=/var/www/html/phinx.yml seed:run -e development -vvv
docker exec -i /engieorcamentodocker_php_1 /var/www/html/vendor/bin/phinx --configuration=/var/www/html/phinx.yml seed:run -s RouteSeeder -e development -vvv
