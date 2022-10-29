# Laravel + Nuxt + Docker
## Start dev server
```bash
docker-compose up
```


## Start prod server
```bash
docker-compose -f docker-compose-prod.yml up
```

## Permission error
In case of permission erro, run this in the laravel folder:
```bash
chmod -R 777 storage/
```

## Run whilte changing docker-compose.yml
In case of permission erro, run this in the laravel folder:
```bash
reset && docker-compose up --force-recreate && docker-compose down
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
  - ⬛ Graphql
    - ⬛ Save
    - ⬛ Find
    - ⬛ Search (basic and custom params)
    - ⬛ Bulk Delete

<hr />

Nuxt Releases <br />
https://github.com/vuetifyjs/vuetify/releases

Vuetify Releases <br />
https://github.com/nuxt/framework/releases