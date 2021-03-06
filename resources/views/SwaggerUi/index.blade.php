<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>LaraSwag</title>

    <link rel="icon" type="image/png" href="{{ asset('vendor/lara_swag/swagger/images/favicon-32x32.png') }}" sizes="32x32" />
    <link rel="icon" type="image/png" href="{{ asset('vendor/lara_swag/swagger/images/favicon-16x16.png') }}" sizes="16x16" />
    <link href="{{ asset('vendor/lara_swag/swagger/css/typography.css') }}" media='screen' rel='stylesheet' type='text/css'/>
    <link href="{{ asset('vendor/lara_swag/swagger/css/reset.css') }}" media='screen' rel='stylesheet' type='text/css'/>
    <link href="{{ asset('vendor/lara_swag/swagger/css/screen.css') }}" media='screen' rel='stylesheet' type='text/css'/>
    <link href="{{ asset('vendor/lara_swag/swagger/css/reset.css') }}" media='print' rel='stylesheet' type='text/css'/>
    <link href="{{ asset('vendor/lara_swag/swagger/css/print.css') }}" media='print' rel='stylesheet' type='text/css'/>

    <script src="{{ asset('vendor/lara_swag/swagger/lib/object-assign-pollyfill.js') }}" type='text/javascript'></script>
    <script src="{{ asset('vendor/lara_swag/swagger/lib/jquery-1.8.0.min.js') }}" type='text/javascript'></script>
    <script src="{{ asset('vendor/lara_swag/swagger/lib/jquery.slideto.min.js') }}" type='text/javascript'></script>
    <script src="{{ asset('vendor/lara_swag/swagger/lib/jquery.wiggle.min.js') }}" type='text/javascript'></script>
    <script src="{{ asset('vendor/lara_swag/swagger/lib/jquery.ba-bbq.min.js') }}" type='text/javascript'></script>
    <script src="{{ asset('vendor/lara_swag/swagger/lib/handlebars-4.0.5.js') }}" type='text/javascript'></script>
    <script src="{{ asset('vendor/lara_swag/swagger/lib/lodash.min.js') }}" type='text/javascript'></script>
    <script src="{{ asset('vendor/lara_swag/swagger/lib/backbone-min.js') }}" type='text/javascript'></script>
    <script src="{{ asset('vendor/lara_swag/swagger/swagger-ui.js') }}" type='text/javascript'></script>
    <script src="{{ asset('vendor/lara_swag/swagger/lib/highlight.9.1.0.pack.js') }}" type='text/javascript'></script>
    <script src="{{ asset('vendor/lara_swag/swagger/lib/highlight.9.1.0.pack_extended.js') }}" type='text/javascript'></script>
    <script src="{{ asset('vendor/lara_swag/swagger/lib/jsoneditor.min.js') }}" type='text/javascript'></script>
    <script src="{{ asset('vendor/lara_swag/swagger/lib/marked.js') }}" type='text/javascript'></script>
    <script src="{{ asset('vendor/lara_swag/swagger/lib/swagger-oauth.js') }}" type='text/javascript'></script>

    <script type="text/javascript">
        var SPEC_URL = "{{ route('lara_swag.doc.spec') }}";
    </script>
    <script src="{{ asset('vendor/lara_swag/init-swagger-ui.js') }}" type='text/javascript'></script>
</head>
<body class="swagger-section">
<div id='header'>
    <div class="swagger-ui-wrap">
        <a id="logo" href="http://swagger.io"><img class="logo__img" alt="swagger" height="30" width="30" src="{{ asset('vendor/lara_swag/swagger/images/logo_small.png') }}" /><span class="logo__title">swagger</span></a>
        <form id='api_selector'>
            <div id='auth_container'></div>
        </form>
    </div>
</div>

<div id="message-bar" class="swagger-ui-wrap" data-sw-translate>&nbsp;</div>
<div id="swagger-ui-container" class="swagger-ui-wrap"></div>
</body>
</html>
