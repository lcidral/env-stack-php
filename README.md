# qa-dev-stack-php

WIP: draft project

## Requirements
- docker
- execute docker login command in host machine

## First: configure local hosts to:
```
127.0.0.1		api72.developstack.local
127.0.0.1		api56.developstack.local
127.0.0.1		web72.developstack.local
127.0.0.1		web56.developstack.local
127.0.0.1		db.developstack.local
127.0.0.1		selenium-hub
127.0.0.1		chrome
127.0.0.1		firefox
127.0.0.1		mail
```

## After run
- install composer dependencies

## Update config files
See ``environment.config`` and files in directory ``./conf/*``.

## How to use this sample project with PHPStorm
- execute http requests
- execute phpunit tests
- execute codeception tests

## Docker commands with Makefile

```bash
make build ....... Build a new docker image, see Dockerfile.
make push ........ Push image to your docker hub account
make release ..... TODO
make latest ...... TODO
make swarm ....... TODO
make start ....... TODO
make service ..... TODO
make test ........ Execute tests in docker containers
make mail ........ Send an test email to Mailcatcher Inbox
make install ..... Install composer dependencies
make database .... Create a new database in MariaDB container
make migrate ..... Run Phinx migration command
```

## Automated Test Suites
- PHPUnit
- Codeception

### How to test a feature using BDD, Codeception and PHPStorm
- persona
- action
- value

### Running Units Tests with PHPUnit
```bash
bin/phpunit
```

### Running Api Tests with Codeception
```bash
bin/codecept run
```

### Running Tests with Docker Exec
- see docker exec command in `Makefile:docker-tests`

## How to use PHPStorm with Tasks Servers
- clearly given tasks with user stories


## See acceptance tests with VNC

Use VNC client to connect.

Browser | Port
--------|-------
Chrome  | 5900
Firefox | 5901

