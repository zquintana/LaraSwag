{# This file is part of the API Platform project.

(c) Kévin Dunglas <dunglas@gmail.com>

For the full copyright and license information, please view the LICENSE
file that was distributed with this source code. #}

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>LaraSwag</title>

    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Open+Sans:400,700|Source+Code+Pro:300,600|Titillium+Web:400,600,700">
    <link rel="stylesheet" href="{{ asset('vendor/lara_swag/swagger-ui/swagger-ui.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/lara_swag/style.css') }}">

    {# json_encode(65) is for JSON_UNESCAPED_SLASHES|JSON_HEX_TAG to avoid JS XSS #}
    <script id="swagger-data" type="application/json">{{ swagger_data|json_encode(65)|raw }}</script>
</head>
<body>
    <header>
        <a id="logo" href="https://github.com/zquintana/LaraSwag"><img src="{{ asset('vendor/lara_swag/logo.png') }}" alt="LaraSwag"></a>
    </header>

    <div id="swagger-ui" class="api-platform"></div>

    <div class="swagger-ui-wrap" style="margin-top: 20px; margin-bottom: 20px;">
        &copy; 2017 <a href="https://api-platform.com">Api-Platform</a>
    </div>

    <script src="{{ asset('vendor/lara_swag/swagger-ui/swagger-ui-bundle.js') }}"></script>
    <script src="{{ asset('vendor/lara_swag/swagger-ui/swagger-ui-standalone-preset.js') }}"></script>
    <script src="{{ asset('vendor/lara_swag/init-swagger-ui.js') }}"></script>
</body>
</html>
