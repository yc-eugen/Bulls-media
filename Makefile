install:
	docker compose up -d
	docker compose exec php_fpm composer install
	docker compose exec php_fpm php artisan migrate

