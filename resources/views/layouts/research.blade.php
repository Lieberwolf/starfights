<div class="container">
    <div class="row">
        <div class="col-12 title-line">
            <span>Verfügbare Forschungen auf {{$planetInformation->galaxy}}:{{$planetInformation->system}}:{{$planetInformation->planet}}</span>
        </div>
        @if($currentResearch)
        <div class="col-10 current-process process-entry">
            <span>Aktuell in Forschung: {{$currentResearch->research_name}} Stufe {{$currentResearch->knowledge != null ? $currentResearch->knowledge->level + 1 : 1}}</span>
            <br/>
            <span>Abgeschlossen in: </span>
            <span class="js-add-countdown"
                  data-seconds-to-count="{{strtotime($currentResearch->finished_at) - now()->timestamp}}">-</span>
            <br/>
            <span>Fertigstellung: {{ date("d.m.Y H:i:s", strtotime($currentResearch->finished_at)) }}</span>
        </div>
        <div class="col-2 process-action">
            <a class="process-denied" href="/research/{{$activePlanet}}/edit">Abbrechen</a>
        </div>
        @endif
        @if(count($availableResearches) <= 0)
            Keine Forschungen Verfügbar
        @else
            @foreach($availableResearches as $key => $research)
            @if($research->buildable)
            <div class="col-10 process-entry">
                <span>{{$research->research_name}} {{$research->knowledge != null ? '(Stufe ' . $research->knowledge->level . ')' : ''}}</span>
                <span>{{$research->description}}</span>
                <span>Forschung auf Stufe {{$research->knowledge != null ? $research->knowledge->level + 1 : 1}}:</span>
                <span>
                    @if($research->fe > 0)
                        Eisen: {{number_format($research->fe,0, ',', '.')}}
                    @endif
                    @if($research->lut > 0)
                        Lutinum: {{number_format($research->lut,0, ',', '.')}}
                    @endif
                    @if($research->cry > 0)
                        Kristalle: {{number_format($research->cry,0, ',', '.')}}
                    @endif
                    @if($research->h2o > 0)
                        Wasser: {{number_format($research->h2o,0, ',', '.')}}
                    @endif
                    @if($research->h2 > 0)
                        Wasserstoff: {{number_format($research->h2,0, ',', '.')}}
                    @endif
                </span>
                <span>Dauer: {{$research->readableBuildtime}}</span>
            </div>
            <div class="col-2 process-action">
                @if(!$currentResearch && $planetaryResources->fe >= $research->fe && $planetaryResources->lut >=
                $research->lut && $planetaryResources->cry >= $research->cry && $planetaryResources->h2o >= $research->h2o
                && $planetaryResources->h2 >= $research->h2)
                @if($research->inProgress)
                <span class="process-denied">Wird bereits geforscht</span>
                @else
                <a class="process-start" href="/research/{{$activePlanet}}/{{$research->id}}">Forschung<br/>Stufe
                    {{$research->knowledge != null ? $research->knowledge->level + 1 : 1}}</a>
                @endif
                @else
                <span class="process-denied">Forschung starten</span>
                @endif
            </div>
            @endif
            @endforeach
        @endif
    </div>
</div>
