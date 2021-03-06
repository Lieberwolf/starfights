<div class="container">
    <div class="row">
        @if($currentResearch)
            <div class="col-10 current-process process-entry">
                <span>Aktuell in Forschung: {{$currentResearch->research_name}} Stufe {{$currentResearch->knowledge != null ? $currentResearch->knowledge->level + 1 : 1}}</span>
            </div>
            <div class="col-2 process-action">
                <a class="process-denied" href="/research/{{$activePlanet}}/edit">Abbrechen</a>
            </div>
        @endif
        @foreach($availableResearches as $key => $research)
            @if($research->buildable)
                <div class="col-10 process-entry">
                    <span>{{$research->research_name}} {{$research->knowledge != null ? '(Stufe ' . $research->knowledge->level . ')' : ''}}</span>
                    <span>{{$research->description}}</span>
                    <span>Forschung auf Stufe {{$research->knowledge != null ? $research->knowledge->level + 1 : 1}}:</span>
                    <span>
                        Eisen: {{number_format($research->fe, 0, ',', '.')}}
                        Lutinum: {{number_format($research->lut, 0, ',', '.')}}
                        Kristalle: {{number_format($research->cry, 0, ',', '.')}}
                        Wasser: {{number_format($research->h2o, 0, ',', '.')}}
                        Wasserstoff: {{number_format($research->h2, 0, ',', '.')}}
                    </span>
                    <span>Dauer: {{gmdate("H:i:s", $research->initial_researchtime)}}</span>
                </div>
                <div class="col-2 process-action">
                    @if(!$currentResearch && $planetaryResources->fe >= $research->fe && $planetaryResources->lut >= $research->lut && $planetaryResources->cry >= $research->cry && $planetaryResources->h2o >= $research->h2o && $planetaryResources->h2 >= $research->h2)
                        @if($research->inProgress)
                            <span class="process-denied">Wird bereits geforscht</span>
                        @else
                            <a class="process-start" href="/research/{{$activePlanet}}/{{$research->id}}">Forschung<br/>Stufe {{$research->knowledge != null ? $research->knowledge->level + 1 : 1}}</a>
                        @endif
                    @else
                        <span class="process-denied">Forschung starten</span>
                    @endif
                </div>
            @else
                @if($key == count($availableResearches) - 1)
                    Keine Forschungen Verfügbar
                @endif
            @endif
        @endforeach
    </div>
</div>
