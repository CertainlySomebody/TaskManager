# TaskManager

Application for task management based on Symfony 8.0 with DDD architecture, Event sourcing and GraphQL API.

## Technologies

- PHP 8.4
- Symfony 8.0
- PostgreSQL 16
- GraphQL
- Symfony Messenger
- Twig
- PHPUnit 13
- Docker

## Architecture
Project is based on DDD architecture (Domain driven design) with 3 separate layers.

- src/Domain - (entities, value objects, events, repository interfaces)
- src/Application (commands, queries, factory, strategy)
- src/Infrastructure (controllers, repositories, doctrine, graphql, security)

## Getting started

### Prerequisites
- Docker and Docker Compose

### Installation

```bash
docker compose up -d --build
```

Install dependencies (if vendor doesnt exist)
```bash
docker compose exec php composer install
```

Run database migrations
```bash
docker compose exec php php bin/console doctrine:migrations:migrate --no-interaction
```

Clear cache
```bash
docker compose exec php php bin/console cache:clear
```

### First run

1. Open http://localhost:8080/web/login
2. Click "Sync users from JSONPlaceholder" - fetches sample users
3. Log in with on of the synced user email, e.g.: Sincere@april.biz
4. Use the dashboard to create tasks, change statuses, and view event history

### Web UI

Available urls
- /web/login (login form)
- /web/dashboard (task list)
- /web/task/{id}/history (task change history)
- /web/sync-users (sync users from JSONPlaceholder API)
- /web/logout (Logout)

### Testing

Run all tests
```bash
docker compose exec php php bin/phpunit
```

### Docker
Available containers:
- PHP 8.4 FPM (taskmanager-php, port 9000)
- Nginx (taskmanager-nginx, port 8080)
- PostgreSQL 16 (taskmanager-db, port 5432)

Available commands:
- docker compose up -d (start containers)
- docker compose down (stop containers)
- docker compose exec php bash (enter php container)
