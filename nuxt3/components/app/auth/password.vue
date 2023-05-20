<template>
  <form @submit.prevent="app.password.submit()">
    <v-card title="E-mail recover">
      <v-divider />

      <v-alert
        type="success"
        rounded="0"
        v-if="app.password.resp && app.password.resp.password_changed"
      >
        Password changed
      </v-alert>

      <v-alert
        type="error"
        rounded="0"
        v-if="app.password.error.message"
      >
        {{ app.password.error.message }}
      </v-alert>

      <v-card-text>
        <v-text-field
          label="E-mail"
          v-model="app.password.params.email"
          :error-messages="app.password.error.get('email')"
          :disabled="app.password.resp && app.password.resp.user_found && !app.password.resp.password_changed"
        />

        <template v-if="app.password.resp && app.password.resp.user_found && !app.password.resp.password_changed">
          <v-text-field
            label="Code"
            v-model="app.password.params.code"
            :error-messages="app.password.error.get('code')"
          />
          <v-text-field
            label="New password"
            v-model="app.password.params.password"
            :error-messages="app.password.error.get('password')"
            type="password"
          />
          <v-text-field
            label="Confirm new password"
            v-model="app.password.params.password_confirmation"
            :error-messages="app.password.error.get('password_confirmation')"
            type="password"
          />
        </template>
      </v-card-text>
      <v-divider />
      <v-card-actions>
        <v-spacer />
        <v-btn type="submit" :loading="app.password.loading">Send</v-btn>
      </v-card-actions>
    </v-card>
  </form>
</template>

<script setup>
  import useApp from '@/composables/useApp';
  const app = useApp({
    password: {
      email: ['required', 'email'],
      password_confirmation: ['required', 'same:password'],
    },
  });
</script>