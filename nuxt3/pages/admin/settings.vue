<template>
  <nuxt-layout name="admin">
    <template #default>
      <div class="admin-settings-form-row">
        <div class="admin-settings-form-row-label">
          App name
        </div>
        <div class="admin-settings-form-row-field">
          <v-text-field
            v-model="save.data['app.name']"
            v-bind="{}"
          />
        </div>
      </div>

      <div class="admin-settings-form-row">
        <div class="admin-settings-form-row-label">
          Timezone
        </div>
        <div class="admin-settings-form-row-field">
          <v-combobox
            v-model="save.data['app.timezone']"
            v-bind="{
              items: (settings.success ? settings.success.options.timezones : []),
            }"
          />
        </div>
      </div>

      <div class="admin-settings-form-row">
        <div class="admin-settings-form-row-label">
          Login time (minutes)
        </div>
        <div class="admin-settings-form-row-field">
          <v-text-field
            v-model="save.data['jwt.ttl']"
            v-bind="{
              type: 'number',
              suffix: 'Minutes',
            }"
          />
        </div>
      </div>

      <div class="admin-settings-form-row">
        <div class="admin-settings-form-row-label">
          E-mail settings
        </div>
        <div class="admin-settings-form-row-field">
          <v-text-field
            v-model="save.data['mail.mailers.smtp.host']"
            v-bind="{
              label: 'Host',
            }"
          />
          <v-text-field
            v-model="save.data['mail.mailers.smtp.port']"
            v-bind="{
              label: 'Port',
            }"
          />
          <v-text-field
            v-model="save.data['mail.mailers.smtp.encryption']"
            v-bind="{
              label: 'Encryption',
            }"
          />
        </div>
      </div>

      <div class="admin-settings-form-row">
        <div class="admin-settings-form-row-label">
          E-mail authentication
        </div>
        <div class="admin-settings-form-row-field">
          <v-text-field
            v-model="save.data['mail.mailers.smtp.username']"
            v-bind="{
              label: 'E-mail',
            }"
          />
          <v-text-field
            v-model="save.data['mail.mailers.smtp.password']"
            v-bind="{
              label: 'Password',
              type: 'password',
            }"
          />
        </div>
      </div>

      <!-- <pre>{{ save }}</pre> -->
      <!-- <pre>{{ settings }}</pre> -->
    </template>
  </nuxt-layout>
</template>

<script setup>
  const settings = useAxios({
    url: 'api://app_settings',
    method: 'get',
    autoSubmit: true,
    onSuccess: ({ data }) => {
      save.value.data = data.settings;
    },
  });

  const save = useAxios({
    url: 'api://app_settings',
    method: 'post',
  });
</script>

<style>
  .admin-settings-form-row {
    display: flex;
  }
  .admin-settings-form-row-label {
    min-width: 400px;
    padding: 16px;
  }
  .admin-settings-form-row-field {
    flex-grow: 1;
    display: flex;
    gap: 15px;
  }
</style>