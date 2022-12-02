<template>
  <v-row>
    <v-col cols="12" md="6">
      <v-card title="Dados">
        <v-card-text>
          <v-text-field label="Número" v-model="params.number" v-bind="{hideDetails:true}" />
          <v-textarea v-model="params.text" v-bind="{hideDetails:true, autoGrow:true}" />
        </v-card-text>
      </v-card>
    </v-col>
    <v-col cols="12" md="6">
      
      <!-- Whatsapp -->
      <div class="whatsapp">
        <div class="whatsapp-header">
          <v-btn variant="text" icon="mdi-arrow-left" />
          <v-avatar :image="whatsapp.contact.photo" />
          <div class="grow1">{{ [whatsapp.contact.name, params.number].join(' ') }}</div>
          <v-btn variant="text" icon="mdi-video" />
          <v-btn variant="text" icon="mdi-phone" />
          <v-btn variant="text" icon="mdi-dots-vertical" />
        </div>
        <div class="whatsapp-messages">
          <div class="whatsapp-messages-each" v-for="m in whatsappComp.messages" :class="{'whatsapp-messages-each-me': m.me}">
            <div class="whatsapp-messages-each-balloon">
              <div class="whatsapp-messages-each-balloon-text" v-html="m.message" />
              <div class="whatsapp-messages-each-balloon-footer">
                <v-icon>mdi-check-all</v-icon>
                <div>22:37</div>
              </div>
            </div>
          </div>
        </div>
        <div class="whatsapp-footer">
          <div class="whatsapp-footer-input">
            <v-icon color="#666">mdi-emoticon</v-icon>
            <div class="whatsapp-footer-input-placeholder">Mensagem</div>
            <v-icon color="#666">mdi-paperclip</v-icon>
            <v-icon color="#666">mdi-camera</v-icon>
          </div>
          <v-btn color="#008a7e" icon="mdi-send" />
        </div>
      </div>
      <br>
      <v-btn :href="numberLink" target="_blank" block color="success">{{ params.number || 'Informe um número' }}</v-btn>
    </v-col>
  </v-row>
</template>

<script>
  export default {
    data: () => ({
      params: {
        number: '',
        text: '',
      },
      whatsapp: {
        contact: (() => {
          let names = ['Ravi', 'Jaci', 'Ivani', 'Cris', 'Noah', 'Dair', 'Charlie', 'Lee'];
          let name = names[Math.floor(Math.random()*names.length)];
          const genre = Math.random() > .5? 'men': 'women';
          const number = Math.round(Math.random()*20);
          const photo = `https://randomuser.me/api/portraits/${genre}/${number}.jpg`;
          return { name, photo };
        })(),
        messages: [
          {me:false, message:'Olá! Tudo bem, e você?'},
        ],
      },
    }),
    computed: {
      whatsappComp() {
        let whatsapp = { ...this.whatsapp };
        
        if (this.params.text) {
          whatsapp.messages = [
            ...whatsapp.messages,
            {me:true, message:this.params.text},
          ];
        }

        whatsapp.messages = whatsapp.messages.map((message) => {
          message.message = message.message
            .replace(/\n/g, '<br />')
            .replace(/\*(.+?)\*/g, '<strong>$1</strong>')
            .replace(/_(.+?)_/g, '<i>$1</i>')
            .replace(/~(.+?)~/g, '<del>$1</del>')
            .replace(/--(.+?)--/g, '<u>$1</u>')
            .replace(/```(.+?)```/g, '<pre>$1</pre>')
          return message;
        });

        return whatsapp;
      },
      numberLink() {
        const number = this.params.number.replace(/[^0-9]/g, '');
        return number? `https://wa.me/${number}?text=${this.params.text}`: false;
      },
    },
  };
</script>


<style lang="scss">
  .whatsapp {
    background: #ddd;
    color: #fff;
    display: flex;
    flex-direction: column;
    height: 500px;
    border-radius: 5px;
    overflow: hidden;

    .grow1 {
      flex-grow: 1;
    }

    &-header {
      display: flex;
      align-items: center;
      background: #008a7e;
      padding: 5px;
      gap: 5px;
    }

    &-messages {
      flex-grow: 1;
      padding: 10px;
      display: flex;
      flex-direction: column;
      gap: 15px;

      &-each {
        &-me {
          text-align: right;
        }
        &-balloon {
          background: #fff;
          display: inline-block;
          padding: 5px 15px;
          border-radius: 5px;
          text-align: left;
          min-width: 120px;

          &-text {
            color: #666;
          }
          &-footer {
            color: #888;
            font-size: 13px;
            display: flex;
            flex-direction: row-reverse;
            gap: 5px;
          }
        }
      }
    }

    &-footer {
      display: flex;
      align-items: center;
      padding: 5px;
      gap: 10px;

      &-input {
        background: #fff;
        flex-grow: 1;
        display: flex;
        align-items: center;
        padding: 10px;
        gap: 10px;
        border-radius: 30px;
        height: 50px;

        &-placeholder {
          flex-grow: 1;
          color: #888;
        }
      }
    }
  }
</style>