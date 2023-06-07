<template>
  <v-defaults-provider
    :defaults="{
      global: {
        disabled: (settings.loading || save.loading),
      },
    }"
  >
    <nuxt-layout name="admin">
      <template #default>
        <form @submit.prevent="save.submit()">
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
              App locale
            </div>
            <div class="admin-settings-form-row-field">
              <v-text-field
                v-model="save.data['app.locale']"
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
              <v-select
                v-model.number="save.data['jwt.ttl']"
                v-bind="{
                  label: 'Time options',
                  items: [
                    { title: '1 hour', value: 60 },
                    { title: '1 day', value: 60*24 },
                    { title: '1 week', value: 60*24*7 },
                    { title: '1 month', value: 60*24*30 },
                    { title: '1 year', value: 60*24*365 },
                  ],
                }"
              />

              <v-text-field
                :model-value="save.data['jwt.ttl']"
                @input="save.data['jwt.ttl'] = parseInt($event.target.value);"
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

          <div class="admin-settings-form-row">
            <div class="admin-settings-form-row-label">
              E-mail template
            </div>
            <div class="admin-settings-form-row-field">
              <v-btn
                class="flex-grow-1"
                @click="modal.header=true"
              >Header</v-btn>

              <v-dialog v-model="modal.header">
                <v-card width="600" class="mx-auto" style="max-width:90vw;">
                  <v-card-title>Header</v-card-title>
                  <v-divider />
                  <v-card-text>
                    <app-html v-model="save.data['mail.header']" />
                  </v-card-text>
                  <v-card-actions>
                    <v-spacer />
                    <v-btn
                      color="primary"
                      @click="modal.header=false"
                    >Ok</v-btn>
                  </v-card-actions>
                </v-card>
              </v-dialog>

              <v-btn
                class="flex-grow-1"
                @click="modal.footer=true"
              >Footer</v-btn>

              <v-dialog v-model="modal.footer">
                <v-card width="600" class="mx-auto" style="max-width:90vw;">
                  <v-card-title>Footer</v-card-title>
                  <v-divider />
                  <v-card-text>
                    <app-html v-model="save.data['mail.footer']" />
                  </v-card-text>
                  <v-card-actions>
                    <v-spacer />
                    <v-btn
                      color="primary"
                      @click="modal.footer=false"
                    >Ok</v-btn>
                  </v-card-actions>
                </v-card>
              </v-dialog>
            </div>
          </div>
  
          <v-bottom-navigation>
            <v-btn
              class="bg-primary"
              type="submit"
              :loading="save.loading"
            >
              <v-icon>mdi-content-save-outline</v-icon>
              <span>Save</span>
            </v-btn>
          </v-bottom-navigation>
        </form>
      </template>
    </nuxt-layout>
  </v-defaults-provider>
</template>

<script setup>
  import { ref } from 'vue';
  import { useDisplay } from 'vuetify';
  
  const d = useDisplay();

  const settings = useAxios({
    url: 'api://app_settings',
    method: 'get',
    autoSubmit: true,
    onSuccess: ({ data }) => {
      save.value.data = data.settings;
    },
  });

  const modal = ref({
    header: false,
    footer: false,
  });
  
  const log = console.log;

  const save = useAxios({
    url: 'api://app_settings',
    method: 'post',
    onSuccess: ({ data }) => {
      save.value.data = data.settings;
    },
  });
</script>

<style>
  .admin-settings-form-row {
    display: flex;
  }
  .admin-settings-form-row-label {
    min-width: 400px;
    padding: 16px;
    color: #666;
  }
  .admin-settings-form-row-field {
    flex-grow: 1;
    display: flex;
    gap: 15px;
  }

  @media (min-width: 0) and (max-width: 960px) {
    .admin-settings-form-row {
      display: block;
    }
    .admin-settings-form-row-label {
      padding: 5px;
    }
  }
</style>