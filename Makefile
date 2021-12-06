.PHONY: create-database install run-migrations database-setup run-seeders start-server

vendors: composer.json
	composer install

create-database: ##creates the database
	@echo "\n\n\033[92mThe following steps will create the database:\033[0m\n"
	@read -p "Enter your mysql user: " user; \
	mysql -u$$user -p -e "CREATE DATABASE IF NOT EXISTS test_laravel_pk"
	@echo "\033[92mDatabase sucessfully created!\033[0m"
	

run-migrations: ##run migrations
	@echo "\n\n\033[92mThe following steps will run migrations:\033[0m\n"
	php artisan migrate

run-seeders: ##run seeders
	@echo "\n\n\033[92mThe following steps will run seeders:\033[0m\n"
	php artisan db:seed

database-setup: run-migrations run-seeders

start-server: #start server
	php artisan serve

install:  vendors create-database database-setup start-server