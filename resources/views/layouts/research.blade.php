<div class="container-fluid">
    <div class="row">
        <div class="col-12 title-line">
            @if($prevPlanet)
            <span>
                <a href="/research/{{$prevPlanet->id}}"><<</a>
            </span>
            @endif
            <span>{{__('research.availableAt', ['planet' => $planetInformation->galaxy.':'.$planetInformation->system.':'.$planetInformation->planet])}}</span>
            @if($nextPlanet)
            <span>
                <a href="/research/{{$nextPlanet->id}}">>></a>
            </span>
            @endif
        </div>
        @if($currentResearch)
        <div class="col-10 current-process process-entry">
            <span>{{__('research.currently')}} {{$currentResearch->research_name}} Stufe {{$currentResearch != null ? $currentResearch->level + 1 : 1}}</span>
            <br/>
            <span>{{__('research.finishedAt')}} </span>
            <span class="js-add-countdown"
                  data-seconds-to-count="{{strtotime($currentResearch->finished_at) - now()->timestamp}}">-</span>
            <br/>
            <span>{{__('research.doneDate')}} {{ date("d.m.Y H:i:s", strtotime($currentResearch->finished_at)) }}</span>
        </div>
        <div class="col-2 process-action">
            <a class="process-denied" href="/research/{{$activePlanet}}/edit">{{__('research.cancel')}}</a>
        </div>
        @endif
        @if(count($availableResearches) <= 0)
        <div class="col-12">{{__('research.emptyList')}}</div>
        @else
            @foreach($availableResearches as $key => $research)
            @if($research->buildable)
            <div class="col-10 process-entry">
                <span>{{$research->research_name}} {{$research->level != null ? '(Stufe ' . $research->level . ')' : ''}} <a data-toggle="collapse" href="#collapse{{$research->id}}" aria-expanded="false" aria-controls="collapse{{$research->id}}">[Info]</a></span>
                <div class="collapse" id="collapse{{$research->id}}">
                    <span>{{$research->description}}</span>
                </div>
                <span>Forschung auf Stufe {{$research != null ? $research->level + 1 : 1}}:</span>
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
                @if(!$currentResearch && $planetaryResources['fe'] >= $research->fe && $planetaryResources['lut'] >=
                $research->lut && $planetaryResources['cry'] >= $research->cry && $planetaryResources['h2o'] >= $research->h2o
                && $planetaryResources['h2'] >= $research->h2)
                @if($research->inProgress)
                <span class="process-denied">Wird bereits geforscht</span>
                @else
                <a class="process-start" href="/research/{{$activePlanet}}/{{$research->research_id}}">Forschung<br/>Stufe
                    {{$research != null ? $research->level + 1 : 1}}</a>
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
