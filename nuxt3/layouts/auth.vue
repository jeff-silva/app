<template>
  <v-app>
    <div style="background:url(https://picsum.photos/600/600) center center no-repeat; background-size:cover; display:flex; align-items:center; justify-content:center; height:100vh;">
      <v-card style="min-width:400px;">
        <v-card-text>
          <slot />
        </v-card-text>

        <v-divider />

        <v-card-text>
          <div>
            <nuxt-link to="/auth">Login</nuxt-link>
          </div>
          <div>
            <nuxt-link to="/auth/register">Cadastro</nuxt-link>
          </div>
          <div>
            <nuxt-link to="/auth/password">Recuperar senha</nuxt-link>
          </div>
        </v-card-text>
      </v-card>
    </div>
  </v-app>
</template>