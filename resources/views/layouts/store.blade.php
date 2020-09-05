<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>DelGordo</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>
    
    <!-- Mercado Pago -->
    <script src="https://secure.mlstatic.com/sdk/javascript/v1/mercadopago.js"></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">
    <link href='./images/logo.png' rel="icon" Atype="image/x-icon" />

    <!-- Styles -->
    <link href="{{ asset('css/store.css') }}" rel="stylesheet">
    
</head>
<body>
    <div id="app">
        <main class="py-4">
            @yield('content')
        </main>
    </div>
    <script>
    (function () { 
        var ldk = document.createElement('script'); 
        ldk.type = 'text/javascript'; 
        ldk.async = true; 
        ldk.src = 'https://s.cliengo.com/weboptimizer/5f0f9cf43b6953002a3d4876/5f0f9d63e2f723002a86f995.js';
        var s = document.getElementsByTagName('script')[0]; 
        s.parentNode.insertBefore(ldk, s); 
    })();
    
    </script>
</body>
</html>

