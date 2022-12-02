<template>
  <nuxt-layout name="admin" :container-fluid="false" :nav-width="350">
    <nuxt-page />

    <template #sidebar>
      <app-nav variant="plain" :items="[
        {to:'/tools', name:'Ferramentas', icon:'mdi-cog', children: [
          {to:'/tools/color', name:'Color tools'},
          {to:'/tools/clockify', name:'Clockify'},
          {to:'/tools/fuso', name:'Fuso horário'},
          {to:'/tools/img-paste', name:'Editar print'},
          {to:'/tools/ip-info', name:'IP e geolocalização'},
          {to:'/tools/linkedin-cv', name:'Currículo Linkedin'},
          {to:'/tools/lotto', name:'Análise lotérica'},
          {to:'/tools/mbti', name:'Teste rápido MBTI'},
          {to:'/tools/money-convert', name:'Conversor de moedas'},
          {to:'/tools/person-generator', name:'Gerador de pessoas'},
          {to:'/tools/qr-code', name:'Gerador de QRCode'},
          {to:'/tools/regex-extractor', name:'Extrator de dados de texto'},
          {to:'/tools/search', name:'Busca'},
          {to:'/tools/text-case', name:'Formatações texto'},
          {to:'/tools/text-charset', name:'Charset'},
          {to:'/tools/text-sort', name:'Ordenador lista'},
          {to:'/tools/whatsapp', name:'Whatsapp link'},
        ]},
        {to:'/dev', name:'Dev', icon:'mdi-cog', children: [
          {to:'/dev/api', name:'API'},
          {to:'/dev/auth', name:'Auth'},
          {to:'/dev/test', name:'Test'},
        ]},
        {to:'/admin', name:'Admin', icon:'mdi-cog'},
      ]" />
    </template>
  </nuxt-layout>
</template>