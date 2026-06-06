#!/bin/bash
set -e

echo "==> Pulling latest code..."
git pull origin main

echo "==> Building frontend assets..."
docker run --rm \
  -v "$(pwd):/app" \
  -w /app \
  node:22-alpine \
  sh -c "npm ci && npm run build"

echo "==> Rebuilding and restarting containers..."
docker compose -f docker-compose.prod.yml up -d --build

echo "==> Waiting for app container..."
sleep 5

echo "==> Installing PHP dependencies..."
docker compose -f docker-compose.prod.yml exec -T laravel-app \
  composer install --no-dev --optimize-autoloader

echo "==> Running migrations..."
docker compose -f docker-compose.prod.yml exec -T laravel-app \
  php artisan migrate --force

echo "==> Caching config, routes and views..."
docker compose -f docker-compose.prod.yml exec -T laravel-app \
  php artisan optimize

echo "==> Linking storage..."
docker compose -f docker-compose.prod.yml exec -T laravel-app \
  php artisan storage:link --quiet 2>/dev/null || true

echo "==> Done."
