# Laravel + Nuxt + Docker
## Start dev server
```bash
docker-compose up
```

or use short command
```bash
yarn dev
```

## Start prod server
```bash
docker-compose -f docker-compose-prod.yml up
```

or use short command
```bash
yarn build
```

## TODO
- ⬛ Ports in settings .env
- ⬛ Nuxt 3
  - ⬛ Masks
  - ⬛ Validation
- ⬛ Laravel
  - ⬛ Implement crontab
  - ⬛ PHP websocket

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