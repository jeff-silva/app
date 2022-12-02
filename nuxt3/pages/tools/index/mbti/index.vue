<!-- https://codepen.io/pulpexploder/pen/pNpdeq?editors=1010 -->

<template>
  <div>
    <h1 class="mb-5">MEMEBTI</h1>
    <template v-for="q in questions">
      <v-select :label="q.question" v-model="q.answers" :items="q.options" multiple />
    </template>
    <div class="d-flex">
      <v-spacer />
      <v-btn @click="clear()">Limpar tudo</v-btn>
    </div>
  </div>
</template>

<script>
  export default {
    data: () => ({
      questions: [
        {
          question: 'Qual desses números mais combina com você?',
          multiple: false,
          answers: [],
          options: [
            {
              title: '69',
              value: ['esfj', 'intj', 'isfj', 'isfp', 'istp', 'esfp'],
            },
            {
              title: '420',
              value: ['entj', 'istj', 'enfp', 'infp', 'enfj', 'estp'],
            },
            {
              title: '666',
              value: ['isfp', 'istp', 'estj', 'entp', 'infj', 'intp', 'entj'],
            },
          ],
        },
        {
          question: 'Você se considera mais:',
          answers: [],
          options: [
            {
              title: 'Visionário / Arrogante',
              value: ['intj'],
            },
            {
              title: 'Calculista / Sem emoções',
              value: ['intp'],
            },
            {
              title: 'Excêntrico / Narcisista',
              value: ['entp'],
            },
            {
              title: 'Líder / Teimoso',
              value: ['entj'],
            },
            {
              title: 'Único / Amoroso',
              value: ['enfp'],
            },
            {
              title: 'Altruísta / Intrusivo',
              value: ['enfj'],
            },
            {
              title: 'Sonhador / Emotivo',
              value: ['infp'],
            },
            {
              title: 'Confidente / Arrogante',
              value: ['infj'],
            },
          ],
        },
        {
          question: 'Se você tromba com alguém chorando...',
          answers: [],
          options: [
            {
              title: 'Pergunta se está tudo bem',
              value: ['enfj', 'infj', 'estp', 'isfp'],
            },
            {
              title: 'Dá um lenço',
              value: ['estj', 'esfj'],
            },
            {
              title: 'Passa correndo',
              value: ['intp', 'infp', 'entj'],
            },
            {
              title: 'Dá uma olhada sem jeito e vai embora',
              value: ['istp', 'intj', 'entp'],
            },
            {
              title: 'Abraça',
              value: ['enfp', 'esfp'],
            },
            {
              title: 'Olha em volta cheio de pânico',
              value: ['isfj', 'istj'],
            },
          ],
        },
        {
          question: 'Você se considera mais:',
          answers: [],
          options: [
            {
              title: 'Físico',
              value: ['enfp', 'istj', 'isfj', 'esfp', 'estp', 'istp', 'estj'],
            },
            {
              title: 'Mental',
              value: ['enfp', 'istj', 'enfj', 'entj', 'intp', 'intj', 'entp'],
            },
            {
              title: 'Emocional',
              value: ['enfp', 'enfj', 'isfj', 'infp', 'isfp', 'infj', 'esfj'],
            },
          ],
        },
      ],
    }),

    computed: {
      result() {
        let letters = ['e', 'i', 's', 'n', 't', 'f', 'j', 'p'];

        const flatten = (arr, depth = 1) => arr.reduce((a, v) => a.concat(depth > 1 && Array.isArray(v) ? flatten(v, depth - 1) : v), []);
        
        let answers = [];
        this.questions.forEach((quest) => {
          flatten(quest.answers).forEach(answer => {
            answers.push(answer);
          });
        });
        
        let total = {};
        letters.forEach((l) => {
          total[l] = { quant: 0, percent: 0 };
        });

        answers.forEach(answer => {
          answer.split('').forEach(l => {
            total[l].quant++;
          });
        });

        let type = [['e', 'i'], ['s', 'n'], ['t', 'f'], ['p', 'j']].map(([l1, l2]) => {
          if (total[l1].quant==0 && total[l2].quant==0) return 'X';
          return total[l1].quant >= total[l2].quant ? l1.toUpperCase() : l2.toUpperCase();
        }).join('');

        return { type, total, answers };
      },
    },

    methods: {
      clear() {
        this.questions.forEach(quest => {
          quest.answers = [];
        });
      },
    },
  };
</script>