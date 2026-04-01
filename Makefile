# CONFIG
# ----------------

APP=app-dev
TEST=app-test
DC=docker-compose

# AMBIENTE
# ----------------

up:
	$(DC) up -d $(APP) server

down:
	$(DC) down

restart: down up

build:
	$(DC) build $(APP) server

reset:
	$(DC) down -v --remove-orphans

logs:
	$(DC) logs -f

# EXEC

exec:
	$(DC) exec $(APP) sh

# LARAVEL
# ----------------

migrate:
	$(DC) run --rm $(APP) php artisan migrate

migrate-fresh:
	$(DC) exec $(APP) php artisan migrate:fresh

seed:
	$(DC) exec $(APP) php artisan db:seed

fresh:
	$(DC) exec $(APP) php artisan migrate:fresh --seed

swag:
	$(DC) exec $(APP) php artisan l5-swagger:generate


# TESTES
# ----------------

test:
	$(DC) run --rm $(TEST) sh -c "php artisan migrate && php artisan test"

test-verbose:
	$(DC) exec $(TEST) sh -c "php artisan migrate && php artisan test --display-errors --display-notices"

# SETUP COMPLETO
# ----------------

setup: build up migrate seed