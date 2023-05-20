<template>
  <form @submit.prevent="app.register.submit()">
    <v-card title="Register">
      <v-divider />
      <v-card-text>
        <v-text-field
          label="Name"
          v-model="app.register.params.name"
          :error-messages="app.register.error.get('name')"
        />
        <v-text-field
          label="E-mail"
          v-model="app.register.params.email"
          :error-messages="app.register.error.get('email')"
        />
        <v-text-field
          label="Password"
          type="password"
          v-model="app.register.params.password"
          :error-messages="app.register.error.get('password')"
        />
        <v-text-field
          label="Repeat password"
          type="password"
          v-model="app.register.params.password_confirmation"
          :error-messages="app.register.error.get('password_confirmation')"
        />
      </v-card-text>
      <v-divider />
      <v-card-actions>
        <v-spacer />
        <v-btn type="submit" :loading="app.register.loading">Register</v-btn>
      </v-card-actions>
    </v-card>
  </form>
</template>

<script setup>
  import useApp from '@/composables/useApp';

  const app = useApp({
    register: {
      validation: {
        password_confirmation: ['required', 'same:password'],
      },
    },
  });

  const rules = {
    password_confirmation: [
      (value) => {
        return value == app.value.register.params.password || 'Password and confirmation does not match';
      },
    ],
  };
</script>