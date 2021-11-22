<div class="container-fluid menu mobile d-md-none d-lg-none d-xl-none pos-f s-bottom pb-3 pt-3">
    <div class="row">
        <div class="col-2">
            <a href="/overview/{{$defaultPlanet}}" class="btn btn-secondary">
                <i class="fas fa-home"></i>
            </a>
        </div>
        <div class="col-2">
            <a href="/construction/{{$defaultPlanet}}" class="btn btn-secondary">
                <i class="fas fa-tools"></i>
            </a>
        </div>
        <div class="col-2">
            <a href="/research/{{$defaultPlanet}}" class="btn btn-secondary">
                <i class="fas fa-microscope"></i>
            </a>
        </div>
        <div class="col-2">
            <a href="/shipyard/{{$defaultPlanet}}" class="btn btn-secondary">
                <i class="fas fa-rocket"></i>
            </a>
        </div>
        <div class="col-2">
            <a href="/mission/{{$defaultPlanet}}" class="btn btn-secondary">
                <i class="fas fa-file-signature"></i>
            </a>
        </div>
        <div class="col-2">
            <button type="button" class="btn btn-secondary" data-toggle="modal" data-target="#mobileMenuModal">
                <i class="fas fa-ellipsis-h"></i>
            </button>
        </div>
    </div>
</div>
<div class="modal fade" id="mobileMenuModal" tabindex="-1" role="dialog" aria-labelledby="mobileMenuModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="mobileMenuModalLabel">Hauptmenü</h5>
                <button type="button" class="close text-light" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
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
                    <ul>
                        <li class="heading">Information</li>
                        <li><a href="/messages/new/{{$activePlanet}}">Nachrichten</a></li>
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
                        <li><a href="/settings/{{$defaultPlanet}}">{{__('settings.settings')}}</a></li>
                        <li><a href="/alliance/{{$defaultPlanet}}">Meine Allianz</a></li>
                        <li><a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">{{ __('Logout') }} </a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                @csrf
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Schließen</button>
            </div>
        </div>
    </div>
</div>

<div class="container-fluid d-md-none d-lg-none d-xl-none pos-f s-middle w-100">
    <div class="row">
        <div class="col-6 text-left">
            <button type="button" class="btn btn-secondary js-trigger-before">
                <i class="fas fa-arrow-left"></i>
            </button>
        </div>
        <div class="col-6 text-right">
            <button type="button" class="btn btn-secondary js-trigger-next">
                <i class="fas fa-arrow-right"></i>
            </button>
        </div>
    </div>
</div>
<div class="menu d-none d-md-block d-lg-block d-xl-block">
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
        <li class="heading" style="padding: 5px; background-image: none; background-color: #001e3b;">Universum</li>
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
        <li><a href="/messages/new/{{$activePlanet}}">Nachrichten</a></li>
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
