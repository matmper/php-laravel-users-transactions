include .env

CONTAINER=ut-php-8

build: kill
	docker-compose up --build -d
	docker exec -it $(CONTAINER) php artisan key:generate
	make config-cache
	make migrate-fresh

up: down
	docker-compose up --no-build -d
	sleep 2
	make config-cache
	make migrate

down:
	docker-compose down || true

kill:
	docker-compose kill || true

tty:
	docker exec -it $(CONTAINER) bash

migrate:
	docker exec -it $(CONTAINER) php artisan migrate

migrate-rollback:
	docker exec -it $(CONTAINER) php artisan migrate:rollback

migrate-fresh:
	docker exec -it $(CONTAINER) php artisan migrate:fresh --seed

composer-install:
	docker exec -it $(CONTAINER) composer install

composer-update:
	docker exec -it $(CONTAINER) composer update

tests:
	docker exec -it $(CONTAINER) ./vendor/bin/phpunit tests

config-cache:
	docker exec -it $(CONTAINER) php artisan config:cache

# Code Sniffer

phpcs:
	docker exec -it $(CONTAINER) php vendor/bin/phpcs

phpcbf:
	docker exec -it $(CONTAINER) php vendor/bin/phpcbf