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
            <span>{{__('research.currently')}} {{__('research.names.' . $currentResearch->research_name)}} {{__('globals.level')}} {{$currentResearch != null ? $currentResearch->level + 1 : 1}}</span>
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
                <span>{{$research->research_name}} {{$research->level != null ? '(' . __('globals.level') . ' ' . $research->level . ')' : ''}} <a data-toggle="collapse" href="#collapse{{$research->research_id}}" aria-expanded="false" aria-controls="collapse{{$research->research_id}}">[{{__('globals.info')}}]</a></span>
                <div class="collapse" id="collapse{{$research->research_id}}">
                    <span>{{__('research.descriptions.short.' . $research->research_name)}}</span>
                </div>
                <span>{{__('research.research_at_level')}} {{$research != null ? $research->level + 1 : 1}}:</span>
                <span>
                    @if($research->fe > 0)
                        {{ __('globals.fe') }}: {{number_format($research->fe,0, ',', '.')}}
                    @endif
                    @if($research->lut > 0)
                        {{ __('globals.lut') }}: {{number_format($research->lut,0, ',', '.')}}
                    @endif
                    @if($research->cry > 0)
                        {{ __('globals.cry') }}: {{number_format($research->cry,0, ',', '.')}}
                    @endif
                    @if($research->h2o > 0)
                        {{ __('globals.h2o') }}: {{number_format($research->h2o,0, ',', '.')}}
                    @endif
                    @if($research->h2 > 0)
                        {{ __('globals.h2') }}: {{number_format($research->h2,0, ',', '.')}}
                    @endif
                </span>
                <span>{{__('globals.duration')}}: {{$research->readableBuildtime}}</span>
            </div>
            <div class="col-2 process-action">
                @if(!$currentResearch && $planetaryResources['fe'] >= $research->fe && $planetaryResources['lut'] >=
                $research->lut && $planetaryResources['cry'] >= $research->cry && $planetaryResources['h2o'] >= $research->h2o
                && $planetaryResources['h2'] >= $research->h2)
                @if($research->inProgress)
                <span class="process-denied">{{__('globals.already_in_research')}}</span>
                @else
                <a class="process-start" href="/research/{{$activePlanet}}/{{$research->research_id}}">{{__('globals.research')}}<br/>{{__('globals.level')}}
                    {{$research != null ? $research->level + 1 : 1}}</a>
                @endif
                @else
                <span class="process-denied">{{__('research.start_research')}}</span>
                @endif
            </div>
            @endif
            @endforeach
        @endif
    </div>
</div>
