NAME = lcidral/php
VERSION = 7.2.4-fpm-xdebug-alpine

.PHONY: all build push latest release

all: build

build:
	docker build -t $(NAME):$(VERSION) .

push:
	docker push $(NAME):$(VERSION)

release: latest
	@if ! docker images $(NAME) | awk '{ print $$2 }' | grep -q -F $(VERSION); then echo "$(NAME) version $(VERSION) is not yet built. Please run 'make build'"; false; fi
	docker push $(NAME)

latest:
	docker tag -f $(NAME):$(VERSION) $(NAME):latest

swarm:
	docker swarm init

stack:
	docker stack deploy -c docker-compose.yml developstack

service:
	docker service ls

test:
	@bin/phpunit
	@bin/codecept run

mail:
	@php -r 'mail("test@example.com","Testing php -v ".phpversion(),"php on ".gethostname());'
	@echo 'To see your fake inbox mail, open: http://mail:1080'

install:
	@docker exec -i /phpdevstackapi_php_1 curl -sS https://getcomposer.org/installer | php
	@docker exec -i /phpdevstackapi_php_1 mv composer.phar /usr/local/bin/composer
	@docker exec -i /phpdevstackapi_php_1 composer global require "fxp/composer-asset-plugin"
	@docker exec -i /phpdevstackapi_php_1 composer global require "hirak/prestissimo"
	@docker exec -i /phpdevstackapi_php_1 composer install

database:
	@docker exec -i /phpdevstackapi_mariadb_1 /usr/bin/mysql -u root --password=admin --execute="DROP SCHEMA IF EXISTS developstack; CREATE DATABASE developstack"

migrate:
	@docker exec -i /phpdevstackapi_php_1 /var/www/html/bin/phinx --configuration=/var/www/html/phinx.yml migrate -e development -vvv
