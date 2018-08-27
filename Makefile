NAME = lcidral/php
VERSION = 7.2.9-fpm-xdebug-alpine
PHP_CONTAINER_NAME = env-stack-php_php_1
MARIADB_CONTAINER_NAME = env-stack-php_mariadb_1

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

docker-tests:
	@docker exec -it /$(PHP_CONTAINER_NAME) bin/codecept run --env docker -vvv --debug

mail:
	@docker exec -it /$(PHP_CONTAINER_NAME) php -r 'mail("test@example.com","Testing php -v ".phpversion(),"php on ".gethostname());'
	@echo 'To see your fake inbox mail, open: http://mail:1080'

install:
	@docker exec -i /$(PHP_CONTAINER_NAME) curl -sS https://getcomposer.org/installer | php
	@docker exec -i /$(PHP_CONTAINER_NAME) mv composer.phar /usr/local/bin/composer
	@docker exec -i /$(PHP_CONTAINER_NAME) composer global require "fxp/composer-asset-plugin"
	@docker exec -i /$(PHP_CONTAINER_NAME) composer global require "hirak/prestissimo"
	@docker exec -i /$(PHP_CONTAINER_NAME) composer install

database:
	@docker exec -i /$(MARIADB_CONTAINER_NAME) /usr/bin/mysql -u root --password=$(MARIADB_USERNAME) --execute="DROP SCHEMA IF EXISTS developstack; CREATE DATABASE developstack"

migrate:
	@docker exec -i /$(PHP_CONTAINER_NAME) /var/www/html/bin/phinx --configuration=/var/www/html/phinx.yml migrate -e development -vvv

headless:
	@java -Dwebdriver.chrome.driver=~/Downloads/chromedriver -jar ~/Downloads/selenium-server-standalone-3.12.0.jar

up:
	@docker-compose up -d

clean:
	@set -e
	@clear && echo 'Removing ./vendor/'
	@rm -rf ./vendor/
	@echo 'Removing ./storage/*'
	@rm -rf ./storage/*
	@echo 'Removing ./logs/*'
	@rm -rf ./logs/*
	@echo 'Removing composer.lock'
	@rm composer.lock
	@echo $'\e[1;32m' [ OK ]

down:
	@docker-compose down
