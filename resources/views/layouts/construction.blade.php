<div class="container-fluid">
    <div class="row">
        <div class="col-12 title-line">
            @if($prevPlanet)
            <span>
                <a href="/construction/{{$prevPlanet->id}}"><<</a>
            </span>
            @endif
            <span>{{__('construction.availableAt', ['planet' => $planetInformation->galaxy.':'.$planetInformation->system.':'.$planetInformation->planet])}}</span>
            @if($nextPlanet)
            <span>
                <a href="/construction/{{$nextPlanet->id}}">>></a>
            </span>
            @endif
        </div>
        @if($currentConstruction)
            <div class="col-10 current-process process-entry">
                <span>{{__('construction.currently')}} {{__('construction.names.' . $currentConstruction->building_name)}} {{__('globals.level')}} {{$currentConstruction->infrastructure != null ? $currentConstruction->infrastructure->level + 1 : 1}}</span>
                <br/>
                <span>{{__('construction.finishedAt')}} </span>
                <span class="js-add-countdown" data-seconds-to-count="{{strtotime($currentConstruction->finished_at) - now()->timestamp}}">-</span>
                <br/>
                <span>{{__('construction.doneDate')}} {{ date("d.m.Y H:i:s", strtotime($currentConstruction->finished_at)) }}</span>
            </div>
            <div class="col-2 process-action">
                <a class="process-denied" href="/construction/{{$activePlanet}}/edit">{{__('construction.cancel')}}</a>
            </div>
        @endif

        @foreach($availableBuildings as $building)
            @if($building->buildable)
                <div class="col-10 process-entry">
                    <span>{{__('construction.names.' . $building->building_name)}} {{$building->infrastructure != null ? '(' . __('globals.level') . ' ' . $building->infrastructure->level . ')' : ''}} <a data-toggle="collapse" href="#collapse{{$building->id}}" aria-expanded="false" aria-controls="collapse{{$building->id}}">[{{__('globals.info')}}]</a></span>
                    <div class="collapse" id="collapse{{$building->id}}">
                        <span>{{__('construction.descriptions.short.' . $building->building_name)}}</span>
                    </div>
                    <span>{{__('globals.construct_at_level')}} {{$building->infrastructure != null ? $building->infrastructure->level + 1 : 1}}:</span>
                    <span>
                        @if($building->fe > 0)
                            {{__('globals.fe')}}: {{number_format($building->fe,0, ',', '.')}}
                        @endif
                        @if($building->lut > 0)
                            {{__('globals.lut')}}: {{number_format($building->lut,0, ',', '.')}}
                        @endif
                        @if($building->cry > 0)
                            {{__('globals.cry')}}: {{number_format($building->cry,0, ',', '.')}}
                        @endif
                        @if($building->h2o > 0)
                            {{__('globals.h2o')}}: {{number_format($building->h2o,0, ',', '.')}}
                        @endif
                        @if($building->h2 > 0)
                            {{__('globals.h2')}}: {{number_format($building->h2,0, ',', '.')}}
                        @endif
                    </span>
                    <span>{{__('globals.duration')}}: {{$building->readableBuildtime}}</span>
                </div>
                <div class="col-2 process-action">
                    @if(!$currentConstruction && $planetaryResources['fe'] >= $building->fe && $planetaryResources['lut'] >= $building->lut && $planetaryResources['cry'] >= $building->cry && $planetaryResources['h2o'] >= $building->h2o && $planetaryResources['h2'] >= $building->h2)
                        <a class="process-start" href="/construction/{{$activePlanet}}/{{$building->id}}">{{__('globals.construction')}}<br/>{{__('globals.level')}} {{$building->infrastructure != null ? $building->infrastructure->level + 1 : 1}}</a>
                        @else
                        <span class="process-denied">{{__('globals.construct')}}</span>
                    @endif
                </div>
            @endif
        @endforeach
    </div>
</div>
