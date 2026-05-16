#!/bin/sh
set -e

cd /var/www/html

if [ ! -x node_modules/.bin/vite ]; then
    echo "Installing npm dependencies..."
    npm ci
fi

echo "Starting Vite dev server (HMR) on port 5173..."
exec npm run dev -- --host 0.0.0.0
