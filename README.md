# Laravel + Nuxt + Docker
## Start dev server
```bash
docker-compose up
```

or with force recreation and rebuild
```bash
docker-compose up --build --force-recreate
```

## Start prod server
```bash
docker-compose -f docker-compose-prod.yml up
```

## TODO
- ⬛ Ports in settings .env
- ⬛ Nuxt 3
  - ☑️ ~~Sync useApp data with Pinia~~
  - ☑️ ~~Make communication with backend port~~
  - ☑️ ~~Implement front end useApp~~
  - ☑️ ~~Error "Windows named pipe error" using entrypoint or command at laravel service in docker-compose.yml~~
  - ⬛ Admin area
  - ⬛ Implement Settings
  - ⬛ Implement Swal2
    - ⬛ Use axios confirmation
  - ⬛ Masks
  - ⬛ Validation
- ⬛ Laravel
  - ☑️ ~~Resolve backend cors~~
  - ☑️ ~~Implement Laravel JWT~~
  - ☑️ ~~Installation command~~
  - ⬛ Model File to upload
  - ⬛ Model Address to manage system addresses
  - ⬛ Implement Settings
  - ⬛ Implement crontab
  - ⬛ PHP Swoole websocket notification system
  - ⬛ Migration
    - ☑️ ~~Fields~~
    - ⬛ Foreign keys

<hr />

Nuxt Releases <br />
https://github.com/vuetifyjs/vuetify/releases

Vuetify Releases <br />
https://github.com/nuxt/framework/releases

Crontab <br />
https://forums.docker.com/t/cron-does-not-run-in-a-php-docker-container/103897 <br />
https://stackoverflow.com/questions/40180326/cron-isnt-running-when-i-start-my-docker-container <br />
https://laracasts.com/discuss/channels/servers/cron-does-not-run-in-a-php-docker-container <br />
https://serverfault.com/questions/1034381/cron-is-not-running-from-docker-container-failed <br />