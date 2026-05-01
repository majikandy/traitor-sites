.PHONY: help dev start stop logs shell db rebuild

help: ## Show available commands
	@grep -E '^[a-zA-Z_-]+:.*?## .*$$' $(MAKEFILE_LIST) | awk 'BEGIN {FS = ":.*?## "}; {printf "\033[36m%-15s\033[0m %s\n", $$1, $$2}'

dev: ## Rebuild, migrate, tail logs (full dev cycle)
	-docker compose down
	docker compose up --build -d
	docker compose exec proteinin php artisan migrate
	docker compose logs -f

start: ## Build and start containers with migrations
	-docker compose down
	docker compose up --build -d
	docker compose exec proteinin php artisan migrate

stop: ## Stop all containers (safe to run when already stopped)
	-docker compose down

logs: ## Tail container logs
	docker compose logs -f

shell: ## Open a shell in the proteinin container
	docker compose exec proteinin bash

db: ## Open MySQL CLI
	docker compose exec db mysql -uprotein -pproteinin proteinin


rebuild: ## Full rebuild from scratch (no cache)
	docker compose build --no-cache && docker compose up -d
