<div class="container">
    <div class="row">
        @if($currentConstruction)
            <div class="col-10 current-process process-entry">
                <span>Aktuell in Konstruktion: {{$currentConstruction->building_name}} Stufe {{$currentConstruction->infrastructure != null ? $currentConstruction->infrastructure->level + 1 : 1}}</span>
                <br/>
                <span>Abgeschlossen in: </span>
                <span class="js-add-countdown" data-seconds-to-count="{{strtotime($currentConstruction->finished_at) - now()->timestamp}}">-</span>
                <br/>
                <span>Fertigstellung: {{ date("d.m.Y H:m:s", strtotime($currentConstruction->finished_at)) }}</span>
            </div>
            <div class="col-2 process-action">
                <a class="process-denied" href="/construction/{{$activePlanet}}/edit">Abbrechen</a>
            </div>
        @endif

        @foreach($availableBuildings as $building)
            @if($building->buildable)
                <div class="col-10 process-entry">
                    <span>{{$building->building_name}} {{$building->infrastructure != null ? '(Stufe ' . $building->infrastructure->level . ')' : ''}}</span>
                    <span>{{$building->description}}</span>
                    <span>Ausbau auf Stufe {{$building->infrastructure != null ? $building->infrastructure->level + 1 : 1}}:</span>
                    <span>
                        @if($building->fe > 0)
                            Eisen: {{number_format($building->fe,0, ',', '.')}}
                        @endif
                        @if($building->lut > 0)
                            Lutinum: {{number_format($building->lut,0, ',', '.')}}
                        @endif
                        @if($building->cry > 0)
                            Kristalle: {{number_format($building->cry,0, ',', '.')}}
                        @endif
                        @if($building->h2o > 0)
                            Wasser: {{number_format($building->h2o,0, ',', '.')}}
                        @endif
                        @if($building->h2 > 0)
                            Wasserstoff: {{number_format($building->h2,0, ',', '.')}}
                        @endif
                    </span>
                    <span>Dauer: {{$building->readableBuildtime}}</span>
                </div>
                <div class="col-2 process-action">
                    @if(!$currentConstruction && $planetaryResources->fe >= $building->fe && $planetaryResources->lut >= $building->lut && $planetaryResources->cry >= $building->cry && $planetaryResources->h2o >= $building->h2o && $planetaryResources->h2 >= $building->h2)
                        <a class="process-start" href="/construction/{{$activePlanet}}/{{$building->id}}">Konstruktion<br/>Stufe {{$building->infrastructure != null ? $building->infrastructure->level + 1 : 1}}</a>
                        @else
                        <span class="process-denied">Ausbau</span>
                    @endif
                </div>
            @endif
        @endforeach
    </div>
</div>
