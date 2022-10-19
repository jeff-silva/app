# Laravel + Nuxt + Docker
## Start dev server
```bash
docker-compose up
```


## Start prod server
```bash
docker-compose -f docker-compose-install.yml up && docker-compose -f docker-compose-prod.yml up
```


To start, install Docker then:

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


------
The stream or file "/laravel/storage/logs/laravel.log" could not be opened in append mode: Failed to open stream: Permission denied


chmod -R 777 ./laravel/storage/logs/laravel.log