@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header title-line text-light">
                        <h1 class="m-0">Starfights</h1>
                    </div>
                    <div class="card-body sub-line text-light">
                        <p class="text-justify">Starfights ist ein Browsergame im bekannten Stil. Dieses im Weltraum angesiedelte Spiel ist leicht zu bedienen und zu verstehen.</p>
                        <p class="text-justify">Bauen, Forschen und Kämpfen sind die zentralen Elemente von Starfights. Ob Einzelkämpfer oder Teamplayer in einer Allianz, Starfights bietet für jeden etwas.</p>
                        <p class="text-justify">Um einen schnellen Einstieg zu ermöglichen steht dir direkt ein Kolonisationsschiff zum Besiedeln einer weiteren Welt zur Verfügung.</p>
                        <p class="text-justify">Melde dich jetzt an und beherrsche das Universum! <a href="/register">>> Zur Anmeldung <<</a></p>
                        <p class="text-justify mt-2 font-weight-bold">Alpha Version! Das Spiel befindet sich zur Zeit im Aufbau und stellt noch nicht alle Funktionen zur Verfügung.</p>
                        <p class="text-justify">Du hast einen Bug gefunden? Erzähle uns davon in unserem Discord. <a href="https://discord.gg/qEQw2YQjKh" target="_blank">>> Zu Discord <<</a></p>
                        <p class="text-justify">Du hast bereits einen Account? <a href="/login">>> Zum Login <<</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
