<!-- https://codepen.io/pulpexploder/pen/pNpdeq?editors=1010 -->

<template>
  <div>
    <h1 class="mb-5">MEMEBTI</h1>
    <v-row>
      <v-col cols="12" md="7">
        <template v-for="q in questions">
          <v-select :label="q.question" v-model="q.answers" :items="q.options" multiple />
        </template>
        <div class="d-flex">
          <v-spacer />
          <v-btn @click="clear()">Limpar tudo</v-btn>
        </div>
      </v-col>
      <v-col cols="12" md="5">
        <div class="d-flex mb-3">
          <v-spacer />
          <div>{{ result.questions.answered.total }} / {{ result.questions.total }}</div>
          <v-spacer />
        </div>
        <v-progress-linear :model-value="result.questions.answered.percent" />
        <div class="d-flex flex-column mt-3" style="gap:16px;" v-if="result.questions.finished">
          <v-card :color="`${m.color}55`" elevation="0" v-for="m in result.mbtis" :key="m.name">
            <div class="d-flex align-center flex-no-wrap justify-space-between">
              <v-avatar class="ma-3" size="125" rounded="0">
                <v-img :src="m.icon"></v-img>
              </v-avatar>
  
              <div class="flex-grow-1">
                <v-card-subtitle>{{ m.name }}</v-card-subtitle>
  
                <v-card-actions>
                  <v-btn class="ml-2" variant="outlined" size="small" :href="m.link" target="_blank">
                    Detalhes
                  </v-btn>
                </v-card-actions>
              </div>
            </div>
          </v-card>
        </div>
      </v-col>
    </v-row>
    <!-- <v-row>
      <v-col cols="3" v-for="m in Object.values(mbti)">
        <div :style="`background: ${m.color}`" class="text-center py-4">
          <v-avatar size="100" rounded="0">
            <v-img :src="m.icon"></v-img>
          </v-avatar>
          <h4 class="mt-2">{{ m.name.toUpperCase() }}</h4>
        </div>
      </v-col>
    </v-row> -->
  </div>
</template>

<script>
  import enfj from './icons/enfj.png';
  import enfp from './icons/enfp.png';
  import entj from './icons/entj.png';
  import entp from './icons/entp.png';
  import esfj from './icons/esfj.png';
  import esfp from './icons/esfp.png';
  import estj from './icons/estj.png';
  import estp from './icons/estp.png';
  import infj from './icons/infj.png';
  import infp from './icons/infp.png';
  import intj from './icons/intj.png';
  import intp from './icons/intp.png';
  import isfj from './icons/isfj.png';
  import isfp from './icons/isfp.png';
  import istj from './icons/istj.png';
  import istp from './icons/istp.png';

  export default {
    data: () => ({
      mbti: {
        isfj: { name: 'isfj', icon: isfj, color: '#369496' },
        istj: { name: 'istj', icon: istj, color: '#369496' },
        esfj: { name: 'esfj', icon: esfj, color: '#369496' },
        estj: { name: 'estj', icon: estj, color: '#369496' },

        isfp: { name: 'isfp', icon: isfp, color: '#e5c828' },
        istp: { name: 'istp', icon: istp, color: '#e5c828' },
        esfp: { name: 'esfp', icon: esfp, color: '#e5c828' },
        estp: { name: 'estp', icon: estp, color: '#e5c828' },

        intj: { name: 'intj', icon: intj, color: '#96637c' },
        intp: { name: 'intp', icon: intp, color: '#96637c' },
        entj: { name: 'entj', icon: entj, color: '#96637c' },
        entp: { name: 'entp', icon: entp, color: '#96637c' },

        infj: { name: 'infj', icon: infj, color: '#99c26d' },
        infp: { name: 'infp', icon: infp, color: '#99c26d' },
        enfj: { name: 'enfj', icon: enfj, color: '#99c26d' },
        enfp: { name: 'enfp', icon: enfp, color: '#99c26d' },
      },
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
        const arrFlatten = (arr, depth = 1) => arr.reduce((a, v) => a.concat(depth > 1 && Array.isArray(v) ? arrFlatten(v, depth - 1) : v), []);
        const strReplaceIndex = (str, index, replace) => str.substring(0, index) + replace + str.substring(index + 1);

        const cartesian = (...args) => {
            let r = [], max = args.length-1;
            const helper = (arr, i) => {
                for (let j=0, l=args[i].length; j<l; j++) {
                    let a = arr.slice(0); // clone arr
                    a.push(args[i][j]);
                    if (i==max)
                        r.push(a);
                    else
                        helper(a, i+1);
                }
            };
            helper([], 0);
            return r;
        };

        let lettersGroup = [['e', 'i'], ['s', 'n'], ['t', 'f'], ['p', 'j']];
        let letters = arrFlatten(lettersGroup);
        
        let answers = [];
        this.questions.forEach((quest) => {
          arrFlatten(quest.answers).forEach(answer => {
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

        let mbti = lettersGroup.map(([l1, l2]) => {
          if (total[l1].quant == total[l2].quant) return 'x';
          return total[l1].quant >= total[l2].quant ? l1 : l2;
        }).join('');

        let mbtis = (() => {
          if (mbti == 'xxxx') return [];

          const mbtiPossibilities = mbti.split('').map((l, i) => {
            return lettersGroup[i].includes(l)? [l]: lettersGroup[i];
          });

          let mbtis = mbtiPossibilities.reduce((a, b) => {
            return a.reduce((r, v) => {
              return r.concat(b.map(w => {
                return [].concat(v, w).join('');
              }));
            }, []);
          });

          return mbtis.map(mbti => {
            return {
              ... this.mbti[mbti],
              type: mbti,
              name: mbti.toUpperCase(),
              link: `https://www.16personalities.com/${mbti}-personality`,
            };
          });
        })();

        let answered = { total: this.questions.filter(q => q.answers.length>0).length };
        answered.percent = answered.total * 100 / this.questions.length;
        let questions = { total: this.questions.length, finished: answered.total == this.questions.length, answered };

        return {
          questions,
          mbti,
          mbtis,
          answers,
          total,
        };
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