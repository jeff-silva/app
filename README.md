# app
## Laravel + Nuxt + Docker


To start, install Docker then:

```bash

# Run dev mode
docker-compose --env-file /.env up

# Deploy
docker-compose -f docker-compose-prod.yml up

# Nuxt3 bash (node)
docker run --rm -it -v ${PWD}/nuxt3:/app -w /app node bash

# Laravel bash (composer)
docker run --rm -it -v ${PWD}/laravel:/app -w /app composer bash

```

## TODO
- ☑️ ~~Sync useApp data with Pinia~~
- ☑️ ~~Resolve backend cors~~
- ⬛ Make communication with backend port