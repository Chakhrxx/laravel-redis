<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Swagger UI</title>
    <link href="swagger-ui/swagger-ui.css" rel="stylesheet">
</head>

<body>
    <div id="swagger-ui"></div>
    <script src="swagger-ui/swagger-ui-bundle.js" crossorigin></script>
    <script>
        const ui = SwaggerUIBundle({
      url: '/swagger.yaml',
      dom_id: '#swagger-ui',
    })
    </script>
</body>

</html>