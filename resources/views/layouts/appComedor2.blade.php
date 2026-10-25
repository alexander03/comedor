<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Scripts -->
    <!--script src="{{ asset('js/app.js') }}" defer></script-->

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet" type="text/css">
    <link  rel="icon"   href="dist/logo_images/comedorsystem.png" type="image/png" />
    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script> 
    <link type="text/css" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/south-street/jquery-ui.css" rel="stylesheet"> 
    <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
    <script type="text/javascript" src="https://sistemasolmos.pe/comedor/js/signature.js"></script>
  
</head>
<body>
    <div id="app " style="margin-top: 40px !important;" class="m-auto">
        <main class="">
            @yield('content')
        </main>
    </div>
</body>
</html>
<style>
    body{
        height: 100%;
        width: 100%;
        display:flex;
        background-image: url(https://sistemasolmos.pe/comedor/dist/logo_images/fondo-alimentacioncto.jpg);
        background-size: cover;
        background-position: top center;
    }
    html{
        height: 100vh;
        width: 100vw;
    }
    .card{
        min-width: 1400px !important;
    }

    
</style>