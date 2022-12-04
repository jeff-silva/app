<!--
  https://codepen.io/pulpexploder/pen/pNpdeq?editors=1010
  https://www.infomoney.com.br/carreira/saiba-como-escolher-sua-carreira-atraves-da-sua-personalidade/
-->

<template>
  <div>
    <h1 class="mb-5">MEMEBTI</h1>

    <v-window v-model="questionCurrent">
      <v-window-item v-for="(q, i) in questions" :key="index" :value="i" class="pa-1">
        <v-card>
          <v-card-subtitle class="mt-3">{{ i+1 }} / {{ questions.length }}</v-card-subtitle>
          <v-card-title>{{ q.question }}</v-card-title>
          <v-progress-linear :model-value="result.questions.answered.percent" />
          <v-card-text>
            <v-checkbox
              v-for="(o, ii) in q.options"
              :key="ii"
              :label="o.title"
              v-model="q.answers"
              multiple
              :true-value="o.value"
              :hide-details="true"
            ></v-checkbox>
            <!-- <app-dd>{{ q }}</app-dd> -->
          </v-card-text>
          <v-divider />
          <v-card-actions>
            <v-btn @click="questionCurrent--" v-if="questionCurrent>0">Voltar</v-btn>
            <v-btn @click="clear()" color="error">Limpar tudo</v-btn>
            <v-spacer />
            <v-btn
              @click="questionCurrent++"
              :disabled="q.answers.length==0"
              color="success"
            >
              {{ questionCurrent==questions.length-1 ? 'Resultado' : 'Próximo' }}
            </v-btn>
          </v-card-actions>
        </v-card>
      </v-window-item>
      <v-window-item :value="questions.length" class="pa-1">
        <v-card title="Resultado">
          <v-divider />
          <v-card-text>
            <v-alert type="warning" v-if="result.mbtis.length > 1">
              Houveram algumas colisões de características, por isso, estamos retornando
              todas as possibilidades dentro do que foi respondido.
            </v-alert>

            <div class="d-flex flex-column mt-5" style="gap:16px;">
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
          </v-card-text>
          <v-divider />
          <v-card-actions>
            <v-btn @click="questionCurrent--" v-if="questionCurrent>0">Voltar</v-btn>
            <v-spacer />
            <v-btn @click="clear()">Limpar tudo</v-btn>
          </v-card-actions>
        </v-card>
      </v-window-item>
    </v-window>

    <div class="py-5">
      <v-divider class="my-5 mx-1" />
    </div>

    <v-card title="Todos os tipos">
      <v-card-text>
        <v-row>
          <v-col cols="3" v-for="m in Object.values(mbti)">
            <div :style="`background: ${m.color}`" class="text-center py-4">
              <v-avatar size="100" rounded="0">
                <v-img :src="m.icon"></v-img>
              </v-avatar>
              <div class="d-flex align-center justify-center px-3 mt-2" style="height:120px;">
                <div>
                  <h4>{{ m.name.toUpperCase() }} - {{ m.type }}</h4>
                  <p>{{ m.description }}</p>
                </div>
              </div>
            </div>
          </v-col>
        </v-row>
      </v-card-text>
    </v-card>
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

        // Analists
        intj: { name: "intj", icon: intj, color: "#96637c", type: "Analista", description: "Pensadores criativos e estratégicos, sempre com um plano para tudo." },
        intp: { name: "intp", icon: intp, color: "#96637c", type: "Analista", description: "Inventores inovadores com grande necessidade de conhecimento." },
        entj: { name: "entj", icon: entj, color: "#96637c", type: "Analista", description: "Líderes fortes e criativos, sempre encontrando um caminho - ou criando o seu próprio." },
        entp: { name: "entp", icon: entp, color: "#96637c", type: "Analista", description: "Pensadores inteligentes e curiosos que não resistem à um desafio intelectual." },

        // Diplomats
        infj: { name: "infj", icon: infj, color: "#99c26d", type: "Diplomata", description: "Quietos e místicos, são inspiradores e idealistsa incansáveis." },
        infp: { name: "infp", icon: infp, color: "#99c26d", type: "Diplomata", description: "Péticos, tipos de pessoas autruístas, estão sempre dispostos à ajudar uma boa causa." },
        enfj: { name: "enfj", icon: enfj, color: "#99c26d", type: "Diplomata", description: "Carismáticos e líderes inspiradores, consemguem hipnotizar seus ouvintes." },
        enfp: { name: "enfp", icon: enfp, color: "#99c26d", type: "Diplomata", description: "Entusiastsa, criativos e sociáveis de espírito livre, que sempre encontram uma razão para sorrir." },

        // Sentinels
        isfj: { name: "isfj", icon: isfj, color: "#369496", type: "Sentinela", description: "Grandes protetores e muito dedicados, sempre prontos para defender quem ama." },
        istj: { name: "istj", icon: istj, color: "#369496", type: "Sentinela", description: "Práticos e com a mentalidade focada em fatos, cuja confiabilidade não pode ser duvidada." },
        esfj: { name: "esfj", icon: esfj, color: "#369496", type: "Sentinela", description: "Pessoas sociáveis e populares, com um carinho extraordinário, sempre prontos para ajudar." },
        estj: { name: "estj", icon: estj, color: "#369496", type: "Sentinela", description: "Excelente administradores, insuperáveis em gerenciar coisas - ou pessoas." },
        
        // Explorers
        isfp: { name: "isfp", icon: isfp, color: "#e5c828", type: "Explorador", description: "ARtistas flexíveis e charmosos, sempre prontos para explorar e experienciar algo novo." },
        istp: { name: "istp", icon: istp, color: "#e5c828", type: "Explorador", description: "Experimentadores práticos e ousados, dominam todos os tipos de ferramentas." },
        esfp: { name: "esfp", icon: esfp, color: "#e5c828", type: "Explorador", description: "Pessoas espontâneas e ótimas em entreter, a vida nunca é chata perto delas." },
        estp: { name: "estp", icon: estp, color: "#e5c828", type: "Explorador", description: "Inteligentes e cheios de enrgia, que realmente gostam de viver no limite." },
      },
      questionCurrent: 0,
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
        this.questionCurrent = 0;
        this.questions.forEach(quest => {
          quest.answers = [];
        });
      },
    },
  };
</script>