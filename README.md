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

## TODO
- ☑️ ~~Sync useApp data with Pinia~~
- ☑️ ~~Resolve backend cors~~
- ☑️ ~~Make communication with backend port~~
- ☑️ ~~Generate routes using PHP 8 native annotation syntax~~
- ☑️ ~~Implement Laravel JWT~~
- ☑️ ~~Implement front end useApp~~
- ⬛ Implement Settings
- ⬛ PHP Swoole websocket notification system
- ⬛ Implement crontab
- ⬛ Installation command
- ⬛ Error "Windows named pipe error" using entrypoint or command at laravel service in docker-compose.yml
- ⬛ Ports in settings .env
- ⬛ Admin area
- ⬛ Model File to upload
- ⬛ Model Address to manage system addresses

<hr />

Nuxt Releases <br />
https://github.com/vuetifyjs/vuetify/releases

Vuetify Releases <br />
https://github.com/nuxt/framework/releases