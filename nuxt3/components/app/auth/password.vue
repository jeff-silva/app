<template>
  <form @submit.prevent="password.submit()">
    <template v-if="password.success && password.success.password_changed">
      <v-alert type="success">
        Password changed
      </v-alert>
      <br>
    </template>

    <template v-if="password.error.hasError()">
      <v-alert type="error" @click="password.error.clear()">
        {{ password.error.message }}
      </v-alert>
      <br>
    </template>

    <template v-if="!password.success">
      <v-text-field
        label="E-mail"
        v-model="password.data.email"
        :error-messages="password.error.get('email')"
      />

      <v-btn
        :loading="password.loading"
        type="submit"
        color="primary"
        block
      >Send code</v-btn>
    </template>

    <template v-if="password.success">
      <v-text-field
        label="E-mail"
        v-model="password.data.email"
        :error-messages="password.error.get('email')"
        :disabled="true"
      />

      <v-text-field
        label="Code"
        v-model="password.data.code"
        :error-messages="password.error.get('code')"
      />

      <v-text-field
        label="New password"
        v-model="password.data.password"
        :error-messages="password.error.get('password')"
      />

      <v-text-field
        label="Password confirmation"
        v-model="password.data.password_confirmation"
        :error-messages="password.error.get('password_confirmation')"
      />

      <v-btn
        :loading="password.loading"
        type="submit"
        color="primary"
        block
      >Change password</v-btn>
    </template>
  </form>
</template>

<script setup>
  import useAxios from '@/composables/useAxios';
  const password = useAxios({
    method: 'post',
    url: 'api://auth/password',
    data: {
      email: '',
      code: '',
      password: '',
      password_confirmation: '',
    },
    onSuccess({ data }) {
      if (data.password_changed) {
        password.value.data = {
          email: '',
          code: '',
          password: '',
          password_confirmation: '',
        };
      }
    },
  });
</script>