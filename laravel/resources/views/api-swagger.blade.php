<!DOCTYPE html>
<html>

<head>
    <title>API Docs</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swagger-ui-dist@3.17.0/swagger-ui.css">
</head>

<body>
    <div id="ui-wrapper" data-spec="xxx">
        Loading....
    </div>
</body>
<script src="https://unpkg.com/swagger-ui-dist@3.23.1/swagger-ui-bundle.js"></script>
<script>
  window.ui = SwaggerUIBundle({
    url: "/api/app/openapi",
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
</script>
</html>