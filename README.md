# WoW Forever Guide

A World of Warcraft API for races, classes, and characters.  

Stack: Symfony 8.1, Doctrine ORM, PostgreSQL, FrankenPHP (Docker), Nelmio OpenAPI + Scalar.

## Getting started

```bash
docker compose up --wait
```

- App: https://localhost  
- API docs: https://localhost/scalar  
- Adminer: http://localhost:8081  
  - System: PostgreSQL, Server: `database`, User: `app`, Password: `password`, Database: `wow_guide`

Migrations and seed data:

```bash
docker compose exec php bin/console doctrine:migrations:migrate --no-interaction
docker compose exec php bin/console doctrine:fixtures:load --no-interaction
```

Shell aliases used locally: `sf` = `docker compose exec php bin/console`, `dc` = `docker compose exec php`.

## Endpoints

| Method | Path | Description |
|--------|------|-------------|
| GET | `/api/characters` | List characters (filters: `?faction=horde&class=druid`) |
| GET | `/api/characters/{id}` | Character details (404 if missing) |
| POST | `/api/characters` | Create a character |
| GET | `/api/races` | List races with allowed classes |
| GET | `/api/classes` | List classes with armor types |

OpenAPI JSON: `/api/doc.json` · UI: `/scalar`

## Tests

```bash
# one-time test database setup
docker compose exec php sh -c 'APP_ENV=test bin/console doctrine:database:create --if-not-exists'
docker compose exec php sh -c 'APP_ENV=test bin/console doctrine:migrations:migrate --no-interaction'
docker compose exec php sh -c 'APP_ENV=test bin/console doctrine:fixtures:load --no-interaction'

docker compose exec php bin/phpunit
```

## Disclaimer

Educational / fan project only. WoW names and themes are used as learning flavour for the API.
