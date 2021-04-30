#!/bin/sh
set -e

# first arg is `-f` or `--some-option`
if [ "${1#-}" != "$1" ]; then
	set -- php "$@"
fi

if [ "$1" = 'php' ]; then
	mkdir -p var/cache var/log public/media

	if [ "$APP_ENV" != 'prod' ]; then
		composer dump-autoload -o
		composer install --prefer-dist --no-progress --no-suggest --no-interaction
		bin/console assets:install --no-interaction
	fi
fi

exec docker-php-entrypoint "$@"
