#!/bin/sh
set -e

exec docker compose -f docker-compose.yml -f docker-compose.dev.yml exec -w /var/www/html app "$@"
