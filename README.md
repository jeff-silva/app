# Laravel + Nuxt + Docker
## Start dev server
```bash
docker-compose up
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