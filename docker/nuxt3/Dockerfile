FROM node:18.16.0

WORKDIR /nuxt3

# Instalar as dependências do projeto
RUN yarn install

# Definir as variáveis de ambiente
ENV NODE_ENV=development
ENV CHOKIDAR_USEPOLLING=true
ENV HOST=${NUXT_HOST:-0.0.0.0}
ENV PORT=${NUXT_PORT:-3000}
ENV APP_PORT=${APP_PORT:-80}

# Comando de execução
CMD ["yarn", "dev"]
