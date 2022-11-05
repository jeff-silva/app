<template>
  <div>
    <Head>
      <Style></Style>
      <Script src="https://unpkg.com/swagger-ui-dist@3.23.1/swagger-ui-bundle.js"></Script>
      <Style type="text/css" children="@import url('https://cdn.jsdelivr.net/npm/swagger-ui-dist@3.17.0/swagger-ui.css');" @load="scriptLoad()"></Style>
    </Head>
    <div id="ui-wrapper"></div>
  </div>
</template>

<script>
  export default {
    methods: {
      scriptLoad() {
        const u = new URL(location.href);
        const apiUrl = `${u.protocol}//${u.hostname}/api/app/openapi`;
        setTimeout(() => {
          window.SwaggerUIBundle({
            url: apiUrl,
            dom_id: '#ui-wrapper', // Determine what element to load swagger ui
            docExpansion: 'list',
            deepLinking: true, // Enables dynamic deep linking for tags and operations
            filter: true,
            presets: [
              SwaggerUIBundle.presets.apis,
              SwaggerUIBundle.SwaggerUIStandalonePreset
            ],
            plugins: [
              SwaggerUIBundle.plugins.DownloadUrl
            ],
          });
        }, 1000);
      },
    },
  };
</script>