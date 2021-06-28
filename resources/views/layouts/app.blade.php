<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Starfights') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}?t={{now()->timestamp}}" defer></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}?t={{now()->timestamp}}" rel="stylesheet">
</head>
<body>
    <div id="app" class="pb-5">
        @if(!Auth::user())
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
            <a class="navbar-brand" href="/">Starfights</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav mr-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="/login">Login</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/register">Registrierung</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="https://discord.gg/qEQw2YQjKh" target="_blank">Discord</a>
                    </li>
                </ul>
            </div>
        </nav>
        @endif
        <main class="py-4">
            @yield('content')
        </main>
    </div>
    @if(!Auth::user())
    <!--
    <div id="chat-opener" class="chat js-show-chat">
        <i class="bi bi-chat-left-dots"></i>
    </div>
    <div id="chat-window" class="container js-chat-window">
        <div class="row">
            <div class="col-12 text-right">
                <i class="bi bi-box-arrow-in-down-right js-hide-chat"></i>
            </div>
            <div class="col-12" style="max-height: 200px;overflow: hidden;">
                <ul id="chat-list" class="chat-list js-chat-list mb-3"></ul>
            </div>
            <div class="col-12">
                <form action="" method="" class="js-prevent">
                    @csrf
                    <div class="input-group mb-3">
                        <input type="text" class="form-control" placeholder="Nachricht" aria-label="Nachricht" aria-describedby="button-addon2">
                        <button class="btn btn-secondary js-send-chat-message" type="button" id="button-addon2">Senden</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    -->
    @endif
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
<div class="">
    This page took {{ (microtime(true) - LARAVEL_START) }} seconds to render
</div>
</body>
</html>
