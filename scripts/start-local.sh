#!/bin/bash

set -e
set -x

docker compose up -d --build

sleep 2

docker compose exec php cp .env.example .env
docker compose exec php composer install
docker compose exec php php bin/console doctrine:migrations:migrate
docker compose exec php php bin/console app:load-currencies
docker compose exec php php bin/console app:load-rates

#docker compose logs -f
