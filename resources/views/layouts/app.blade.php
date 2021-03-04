<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Starfights') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body>
    <div id="app">
        <main class="py-4">
            @yield('content')
        </main>
    </div>
    @if(Auth::user() && Auth::user()->isAdmin != 0)
    <!-- Todo: only admin -->
    <div style="position:fixed; width: 90%; top: 0; right: 0;">
        <a href="/racedashboard" title="{{ __('Race Dashboard') }}">{{ __('Race Dashboard') }}</a>
        <a href="/universedashboard" title="{{ __('Universe Dashboard') }}">{{ __('Universe Dashboard') }}</a>
        <a href="/playerdashboard" title="{{ __('Player Dashboard') }}">{{ __('Player Dashboard') }}</a>
        <a href="/planetdashboard" title="{{ __('Planet Dashboard') }}">{{ __('Planet Dashboard') }}</a>
        <a href="/shipdashboard" title="{{ __('Ship Dashboard') }}">{{ __('Ship Dashboard') }}</a>
        <a href="/defensedashboard" title="{{ __('Defense Dashboard') }}">{{ __('Defense Dashboard') }}</a>
        <a href="/buildingdashboard" title="{{ __('Building Dashboard') }}">{{ __('Building Dashboard') }}</a>
        <a href="/researchdashboard" title="{{ __('Research Dashboard') }}">{{ __('Research Dashboard') }}</a>
    </div>
    @endif
</body>
</html>
