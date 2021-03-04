<div class="menu">
    <ul>
        <li class="heading">Aktion</li>
        <li><a href="/overview/{{$defaultPlanet}}">Übersicht</a></li>
        <li><a href="/construction/{{$defaultPlanet}}">Konstruktion</a></li>
        <li><a href="/shipyard/{{$defaultPlanet}}">Schiffswerft</a></li>
        <li><a href="/defense/{{$defaultPlanet}}">Verteidigung</a></li>
        <li><a href="/research/{{$defaultPlanet}}">Forschung</a></li>
        <li><a href="/mission/{{$defaultPlanet}}">Mission</a></li>
        <li><a href="/fleetlist/{{$defaultPlanet}}">Flottenliste</a></li>
        <li><a href="/resources/{{$defaultPlanet}}">Rohstoffe</a></li>
    </ul>
    <ul class="planet-selector">
        <li>
            <form id="planet-select-form">
                <form class="form-group">
                    <select data-url="" id="planet-select" class="form-control js-planet-select" name="planet-select" onchange="window.location.href=document.getElementById('planet-select').value;">
                        @foreach($allUserPlanets as $planet)
                            <option {{$planet->id == $activePlanet ? 'selected' : ''}} value="{{$planet->id}}">{{$planet->galaxy}}:{{$planet->system}}:{{$planet->planet}}</option>
                        @endforeach
                    </select>
                    <div class="row planet-triggers">
                        <div class="col-6">
                            <span class="js-trigger-before">&lt;&lt;</span>
                        </div>
                        <div class="col-6">
                            <span class="js-trigger-next">&gt;&gt;</span>
                        </div>
                    </div>
                </form>
            </form>
        </li>
    </ul>
    <ul>
        <li class="heading">Information</li>
        <li><a href="/messages/new">Nachrichten</a></li>
        @foreach($allUserPlanets as $planet)
            @if($planet->id == $activePlanet)
            <li><a href="/universe/{{$planet->id . '/' . $planet->galaxy . '/' .$planet->system}}">Universum</a></li>
            @endif
        @endforeach
        <li><a href="/search/{{$defaultPlanet}}">Suche</a></li>
        <li><a href="/techtree/{{$defaultPlanet}}">Technik</a></li>
        <li><a href="/database/{{$defaultPlanet}}">Datenbank</a></li>
        <li><a href="/simulation/{{$defaultPlanet}}">Simulation</a></li>
        <li><a href="/highscore/{{$defaultPlanet}}">Top 100</a></li>
    </ul>
    <ul>
        <li class="heading">Account</li>
        <li><a href="/settings/{{$defaultPlanet}}">Einstellungen</a></li>
        <li><a href="/alliance/{{$defaultPlanet}}">Meine Allianz</a></li>
        <li><a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">{{ __('Logout') }} </a>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                @csrf
            </form>
        </li>
    </ul>
</div>
