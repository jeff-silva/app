<template>
  <div>
    <v-row>
      <v-col cols="12" md="4">
        <v-select
          v-model="filter.category"
          label="Tipo de busca"
          :hide-details="true"
          :items="categories"
          item-title="name"
          item-value="id"
        />
      </v-col>
      <v-col cols="12" md="4">
        <v-text-field
          v-model="filter.term"
          label="Buscar pessoas, produtos, vagas de emprego..."
          :hide-details="true"
        />
      </v-col>
      <v-col cols="12" md="4">
        <v-text-field
          v-model="filter.place"
          label="Cidade"
          :hide-details="true"
        />
      </v-col>
      <v-col cols="12">
        <div class="d-flex">
          <div style="min-width:250px;">
            <app-nav :items="results.categories"></app-nav>
          </div>
          <div class="flex-grow-1 pa-1">
            <v-table>
              <tbody>
                <tr v-if="results.links.length==0">
                  <td class="text-center" style="background:#f5f5f5;">Digite um termo</td>
                </tr>
                <tr v-for="l in results.links">
                  <td><a :href="l.url" target="_blank" style="color:#333;">{{ l.name }}</a></td>
                </tr>
              </tbody>
            </v-table>
          </div>
        </div>
      </v-col>
    </v-row>
  </div>
</template>

<script>
  export default {
    data() {
      return {
        filter: {
          category: '',
          term: '',
          place: '',
        },
        categories: [
          {id:'people', icon:'mdi-account-group', name:'Pessoas'},
          {id:'shop', icon:'mdi-cart', name:'Produtos'},
          {id:'work', icon:'mdi-briefcase', name:'Trabalho'},
        ],
      };
    },

    computed: {
      results() {
        let category = this.filter.category;
        let term = encodeURI(this.filter.term);
        let place = encodeURI(this.filter.place);

        let links = [];

        if (term) {

          // People
          links.push({
            name: 'Google',
            url: `https://www.google.com/search?q=${term}+${place}&newwindow=1`,
            categories: this.categories.map(cat => cat.id),
          });
          
          links.push({
            name: 'Google - CNPJ',
            url: `https://www.google.com/search?q=${term}+${place}+%2BCNPJ&newwindow=1`,
            categories: this.categories.map(cat => cat.id),
          });
          
          links.push({
            name: 'Facebook - Pessoas',
            url: `https://www.facebook.com/search/people/?q=${term}+${place}`,
            categories: ['people'],
          });
          
          links.push({
            name: 'Webmii',
            url: `https://webmii.com/people?n=%22${term}%22#gsc.tab=0&gsc.q=%22${term}%22&gsc.sort=date`,
            categories: ['people'],
          });

          // Shop

          // links.push({
          //   name: 'Mercado Livre',
          //   url: `https://lista.mercadolivre.com.br/produto#D[A:${term}]`,
          //   categories: ['shop'],
          // });
          
          links.push({
            name: 'Shopee',
            url: `https://shopee.com.br/search?keyword=${term}`,
            categories: ['shop'],
          });

          // Work
          
          links.push({
            name: 'Linkedin - Vagas',
            url: `https://www.linkedin.com/jobs/search/?keywords=${term}&location=${place}&refresh=true`,
            categories: ['work'],
          });
          
          links.push({
            name: 'Linkedin',
            url: `https://www.linkedin.com/search/results/people/?keywords=${term}&origin=SWITCH_SEARCH_VERTICAL&sid=a5Y`,
            categories: ['people'],
          });
          
          links.push({
            name: 'Portal da transparência',
            url: `https://www.portaltransparencia.gov.br/pessoa-fisica/busca/lista?termo=${term}&pagina=1&tamanhoPagina=10`,
            categories: ['people'],
          });
          
          links.push({
            name: 'Procurar pessoas',
            url: `https://www.procurarpessoas.net/busca/?cx=partner-pub-0265720870061805%3A9931338853&cof=FORID%3A10&ie=UTF-8&q=${term}&siteurl=www.procurarpessoas.net%2F&ref=www.google.com%2F&ss=3128j722372j25`,
            categories: ['people'],
          });
          
          links.push({
            name: 'Indeed',
            url: `https://br.indeed.com/empregos?q=${term}&l=${place}`,
            categories: ['work'],
          });
          
          links.push({
            name: 'IT Jobs',
            url: `https://www.itjobs.pt/emprego?q=${term}`,
            categories: ['work'],
          });
          
          links.push({
            name: 'Vagas',
            url: `https://www.vagas.com.br/vagas/pesquisas?q=${term}%20${place}`,
            categories: ['work'],
          });
          
          links.push({
            name: 'Catho',
            url: `https://www.catho.com.br/vagas/?q=${term}`,
            categories: ['work'],
          });
          
          links.push({
            name: '99 Freelas',
            url: `https://www.99freelas.com.br/freelancers?q=${term}`,
            categories: ['work'],
          });
        }

        links = links.map(link => {
          link.categories = this.categories.filter(cat => link.categories.includes(cat.id));
          return link;
        });

        let categories = JSON.parse(JSON.stringify(this.categories)).map(cat => {
          cat.total = links.filter(link => link.categories.map(cat => cat.id).includes(cat.id)).length;
          cat.name = `${cat.name} (${cat.total})`;
          return cat;
        });

        if (category) {
          links = links.filter((link) => {
            return link.categories.filter(cat => cat.id==category).length > 0;
          });
        }

        return { categories, links };
      },
    },
  };
</script>