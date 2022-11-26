<!-- https://codepen.io/pulpexploder/pen/pNpdeq?editors=1010 -->

<template>
  <div>
    <h1>MBTI</h1>
    <template v-for="q in questions">
      <v-select :label="q.question" v-model="q.answer" :items="[{title:'None', value:false}, ...q.options]" />
    </template>
    <app-dd>result: {{ result }}</app-dd>
    <app-dd>$data: {{ $data }}</app-dd>
  </div>
</template>

<script>
  export default {
    data: () => ({
      questions: [
        {
          question: 'At a party do you:',
          answer: false,
          options: [
            {
              title: 'Interact with many, including strangers',
              value: 'e',
            },
            {
              title: 'Interact with a few, known to you',
              value: 'i',
            },
          ],
        },
        
        {
          question: 'Are you more:',
          answer: false,
          options: [
            {
              title: 'Realistic than speculative',
              value: 's',
            },
            {
              title: 'Speculative than realistic',
              value: 'n',
            },
          ],
        },

        {
          question: 'Is it worse to:',
          answer: false,
          options: [
            {
              title: 'Have your “head in the clouds”',
              value: 's',
            },
            {
              title: 'Be “in a rut”',
              value: 'n',
            },
          ],
        },
      ],
    }),

    computed: {
      result() {
        let letters = ['e', 'i', 's', 'n', 't', 'f', 'j', 'p'];
        
        let total = {};
        letters.forEach((l) => {
          let quant = this.questions.filter(q => q.answer==l).length;
          let percent = Math.floor(quant / (['e', 'i'].includes(l)? 10: 20) * 100);
          total[l] = { quant, percent };
        });

        let type = [
          (total.e >= total.i ? 'E' : 'I'),
          (total.s >= total.n ? 'S' : 'N'),
          (total.t >= total.f ? 'T' : 'F'),
          (total.j >= total.p ? 'J' : 'P'),
        ].join('');

        return { type, total };
      },
    },
  };
</script>