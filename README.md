# app
## Laravel + Nuxt + Docker


To start, install Docker then:

```bash

# Run dev mode
docker-compose --env-file /.env up

# Deploy
docker-compose -f docker-compose-prod.yml up

# To create initial installation (avoid "Windows named pipe error" error at laravel service command)
docker run --rm -it -v ${PWD}/laravel:/app -w /app composer composer install
docker run --rm -it -v ${PWD}/laravel:/app -w /app --env-file ${PWD}/.env composer php artisan app:install

# Laravel bash (composer)
docker run --rm -it -v ${PWD}/laravel:/app -w /app composer bash

# Nuxt3 bash (node)
docker run --rm -it -v ${PWD}/nuxt3:/app -w /app node bash

```

## TODO
- ☑️ ~~Sync useApp data with Pinia~~
- ☑️ ~~Resolve backend cors~~
- ☑️ ~~Make communication with backend port~~
- ☑️ ~~Generate routes using PHP 8 native annotation syntax~~
- ☑️ ~~Implement Laravel JWT~~
- ⬛ Implement Settings
- ⬛ PHP Swoole websocket notification system
- ⬛ Installation command
- ⬛ Error "Windows named pipe error" using entrypoint or command at laravel service in docker-compose.yml

## Dependencies

Laravel Route Attributes <br>
https://github.com/spatie/laravel-route-attributes
