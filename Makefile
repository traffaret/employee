.PHONY: up
up:
	@docker-compose up -d --build
	@make composer-install

.PHONY: down
down:
	@docker-compose down --remove-orphans

.PHONY:test
test:
	@docker-compose exec app composer test

.PHONY: composer
composer: composer.json composer.lock

.PHONY: composer-update
composer-update: composer
	@docker-compose exec app composer update --no-interaction
	@composer install --no-plugins --no-scripts --ignore-platform-reqs 2>&1

.PHONY: composer-install
composer-install: composer
	@docker-compose exec app composer install --no-interaction
	@composer install --no-plugins --no-scripts --ignore-platform-reqs 2>&1
