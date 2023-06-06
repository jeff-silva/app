<template>
  <form @submit.prevent="register.submit()">
    <template v-if="register.success">
      <v-alert type="success">
        Welcome {{ register.success.name }}
      </v-alert>
      <br>
    </template>

    <v-text-field
      label="Name"
      v-model="register.data.name"
      :error-messages="register.error.get('name')"
    />
    <v-text-field
      label="E-mail"
      v-model="register.data.email"
      :error-messages="register.error.get('email')"
    />
    <v-text-field
      label="Password"
      type="password"
      v-model="register.data.password"
      :error-messages="register.error.get('password')"
    />
    <v-text-field
      label="Repeat password"
      type="password"
      v-model="register.data.password_confirmation"
      :error-messages="register.error.get('password_confirmation')"
    />
    <div class="d-flex align-center">
      <v-spacer />
      <v-btn
        type="submit"
        :loading="register.loading"
        color="primary"
      >Register</v-btn>
    </div>
  </form>
</template>

<script setup>
  import useAxios from '@/composables/useAxios';
  const register = useAxios({
    method: 'post',
    url: 'api://auth/register',
    data: {
      name: '',
      email: '',
      password: '',
      password_confirmation: '',
    },
    onSuccess({ data }) {
      register.value.data = {
        name: '',
        email: '',
        password: '',
        password_confirmation: '',
      };
    },
  });
</script>

<!-- <template>
  <form @submit.prevent="app.register.submit()">
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
</script> -->