NAME = lcidral/php
VERSION = 7.2.4-fpm-xdebug-alpine

.PHONY: all build push tag_latest release

all: build

docker-build:
	@docker build -t $(NAME):$(VERSION) .

docker-push:
	@docker push $(NAME):$(VERSION)

docker-release: tag_latest
	@if ! docker images $(NAME) | awk '{ print $$2 }' | grep -q -F $(VERSION); then echo "$(NAME) version $(VERSION) is not yet built. Please run 'make docker-build'"; false; fi
	@docker push $(NAME)

docker-tag_latest:
	@docker tag -f $(NAME):$(VERSION) $(NAME):latest
