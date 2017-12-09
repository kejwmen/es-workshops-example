all: dev

COMPOSE = docker-compose
COMPOSE_RUN_APP = $(COMPOSE) run --rm --user=1000 app

dev: | docker-build composer-install composer-dump-autoload test

docker-build:
	$(COMPOSE) build $(SERVICE-)

composer-install:
	$(COMPOSE_RUN_APP) composer install

composer-update:
	$(COMPOSE_RUN_APP) composer update

composer-dump-autoload:
	$(COMPOSE_RUN_APP) composer dump-autoload

test:
	$(COMPOSE_RUN_APP) phpdbg -qrr bin/phpunit

metrics:
	$(COMPOSE_RUN_APP) bin/phpmetrics --report-html=report src --junit=coverage/logfile.xml

