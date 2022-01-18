<div class="container-fluid">
    <div class="row">
        <div class="col-12 title-line">
            {{__('globals.planet')}} "{{$planetInformation->planet_name != null ? $planetInformation->planet_name : __('globals.unknown_planet')}}"
            <a href="/universe/{{$defaultPlanet}}/{{$planetInformation->galaxy}}/{{$planetInformation->system}}">({{$planetInformation->galaxy}}:{{$planetInformation->system}}:{{$planetInformation->planet}})</a>
            <a href="/details/{{$planetInformation->id}}">[{{__('globals.details')}}]</a>
        </div>
        <div class="col-12 sub-line">
            <div class="row">
                <div class="col-6 col-md-2 offset-md-1">
                    <a href="/planetary/{{$activePlanet}}">{{__('globals.overview')}}</a>
                </div>
                <div class="col-6 col-md-2">
                    <a href="/ships/{{$activePlanet}}">{{__('globals.ships_overview')}}</a>
                </div>
                <div class="col-12 col-md-2">
                    <a href="/notice/{{$activePlanet}}">{{__('globals.noticeblock')}}</a>
                </div>
                <div class="col-6 col-md-2">
                    <a href="/statistics/{{$activePlanet}}/user/{{$planetInformation->user_id}}">{{__('globals.statistics')}}</a>
                </div>
                <div class="col-6 col-md-2">
                    <a href="">{{__('globals.trade')}}</a>
                </div>
            </div>
        </div>
        <div class="col-6 sub-line">{{__('globals.server_time')}}</div>
        <div class="col-6 sub-line">{{now()}}</div>
        @if($planetInformation->image != null)
            <div class="col-12 title-line mt-3">{{__('globals.planetimage')}} <a href="/details/{{$activePlanet}}">[{{__('globals.change')}}]</a></div>
            <div class="col-12 sub-line py-2">
                <img class="img-fluid" src="{{$planetInformation->image}}" title="Toller Planet" alt="Kaputt"/>
            </div>
        @endif
        <div class="col-12 title-line mt-3">{{__('globals.ships_at_planet')}}</div>
        @if($shipsAtPlanet)
            @foreach(json_decode($shipsAtPlanet->ship_types) as $ship)
                @if($ship->amount > 0)
                    <div class="col-6 sub-line">{{$ship->ship_name}}</div>
                    <div class="col-6 sub-line">{{number_format($ship->amount, 0, ',', '.')}}</div>
                @endif
            @endforeach
        @else
            <div class="col-12 sub-line">- {{__('globals.none')}} -</div>
        @endif
        <div class="col-12 title-line mt-3">{{__('globals.defense_turrets')}}</div>
        @if($turretsAtPlanet)
            @foreach(json_decode($turretsAtPlanet->turret_types) as $turret)
                @if($turret->amount > 0)
                    <div class="col-6 sub-line">{{$turret->turret_name}}</div>
                    <div class="col-6 sub-line">{{number_format($turret->amount, 0, ',', '.')}}</div>
                @endif
            @endforeach
        @else
            <div class="col-12 sub-line">- {{__('globals.none')}} -</div>
        @endif
        <div class="col-12 title-line mt-3">{{__('globals.events')}}</div>
        @if(count($planetaryProcesses) > 0)
            <div class="col-3 text-right sub-line">{{__('globals.remaining_time')}} - [{{__('globals.time')}}]</div>
            <div class="col-9 sub-line">{{__('globals.event')}}</div>
        @else
            <div class="col-12 sub-line">- {{__('globals.none')}} -</div>
        @endif
        @foreach($planetaryProcesses as $process)
            @if($process)
                @if($process->type == 'building')
                    <div class="col-3 text-right sub-line" style="background-color:#BD5C03;"><span class="js-add-countdown" data-seconds-to-count="{{strtotime($process->finished_at) - now()->timestamp}}"></span> - [{{date('H:i:s', strtotime($process->finished_at))}}]</div>
                    <div class="col-9 sub-line" style="background-color:#BD5C03;">
                        <span>{{__('globals.construction_of')}} {{__('construction.names.' . $process->building_name)}} {{__('globals.level')}} {{$process->level != null ? $process->level + 1 : 1}} auf {{$process->galaxy}}:{{$process->system}}:{{$process->planet}}</span>
                    </div>
                @endif
                @if($process->type == 'research')
                    <div class="col-3 text-right sub-line" style="background-color:#067D06;"><span class="js-add-countdown" data-seconds-to-count="{{strtotime($process->finished_at) - now()->timestamp}}"></span> - [{{date('H:i:s', strtotime($process->finished_at))}}]</div>
                    <div class="col-9 sub-line" style="background-color:#067D06;">
                        <span>{{__('globals.research_of')}} {{$process->research_name}} Stufe {{$process->level != null ? $process->level + 1 : 1}} auf {{$process->galaxy}}:{{$process->system}}:{{$process->planet}}</span>
                    </div>
                @endif
                @if($process->type == 'foreignFleet' && $process->mission != 0)
                    <div class="col-3 text-right sub-line {{($process->mission == 2 ? 'transport' : ($process->mission == 3 ? 'espionage' : ($process->mission == 4 ? 'espionage' : ($process->mission == 6 ? 'attack' : ($process->mission == 7 ? 'invasion' : '')))))}}">
                        <span class="js-add-countdown" data-seconds-to-count="{{strtotime($process->arrival) - now()->timestamp}}"></span> - [{{date('H:i:s', strtotime($process->arrival))}}]
                    </div>
                    <div class="col-9 sub-line {{($process->mission == 2 ? 'transport' : ($process->mission == 3 ? 'espionage' : ($process->mission == 4 ? 'espionage' : ($process->mission == 6 ? 'attack' : ($process->mission == 7 ? 'invasion' : '')))))}}">
                        <span>{{__('globals.fleet_of')}} {{$process->sourceGalaxy}}:{{$process->sourceSystem}}:{{$process->sourcePlanet}} {{__('globals.reaches')}} {{$process->targetGalaxy}}:{{$process->targetSystem}}:{{$process->targetPlanet}} ({{$process->mission == 1 ? __('missions.deployment') : ($process->mission == 2 ? __('missions.transport') : ($process->mission == 3 ? __('missions.espionage') : ($process->mission == 4 ? __('missions.delta_scan') : ($process->mission == 5 ? __('missions.colonization') : ($process->mission == 6 ? __('missions.attack') : ($process->mission == 7 ? __('missions.invasion') : ''))))))}})</span>
                    </div>
                @endif
                @if($process->type == 'fleet')
                    @if(strtotime($process->arrival) > now()->timestamp && !property_exists($process, 'aborted'))
                    <div class="col-3 text-right sub-line" style="background-color:#102f4c;">
                        <span class="js-add-countdown" data-seconds-to-count="{{strtotime($process->arrival) - now()->timestamp}}"></span> - [{{date('H:i:s', strtotime($process->arrival))}}]
                    </div>
                    <div class="col-9 sub-line" style="background-color:#102f4c;">
                        <span>{{__('globals.fleet_of')}} {{$process->sourceGalaxy}}:{{$process->sourceSystem}}:{{$process->sourcePlanet}} {{__('globals.reaches')}} {{$process->targetGalaxy}}:{{$process->targetSystem}}:{{$process->targetPlanet}} ({{$process->mission == 1 ? __('missions.deployment') : ($process->mission == 2 ? __('missions.transport') : ($process->mission == 3 ? __('missions.espionage') : ($process->mission == 4 ? __('missions.delta_scan') : ($process->mission == 5 ? __('missions.colonization') : ($process->mission == 6 ? __('missions.attack') : ($process->mission == 7 ? __('missions.invasion') : ''))))))}})</span>
                    </div>
                    @endif
                    @if($process->mission == 0)
                    <div class="col-3 text-right sub-line" style="background-color:#001e3b;">
                        <span class="js-add-countdown" data-seconds-to-count="{{strtotime($process->arrival) + (strtotime($process->arrival) - strtotime($process->departure)) - now()->timestamp}}"></span> - [{{date('H:i:s', strtotime($process->arrival) + (strtotime($process->arrival) - strtotime($process->departure)))}}]
                    </div>
                    <div class="col-9 sub-line" style="background-color:#001e3b;">
                        <span>{{__('globals.fleet_of')}} {{$process->sourceGalaxy}}:{{$process->sourceSystem}}:{{$process->sourcePlanet}} {{__('globals.returns')}} [{{__('globals.mission_canceled')}}]</span>
                    </div>
                    @else
                    @if(property_exists($process, 'aborted') && $process->aborted == 1)
                    <div class="col-3 text-right sub-line" style="background-color:#001e3b;">
                        <span class="js-add-countdown" data-seconds-to-count="{{strtotime($process->arrival) + (strtotime($process->arrival) - strtotime($process->departure)) - now()->timestamp}}"></span> - [{{date('H:i:s', strtotime($process->arrival) + (strtotime($process->arrival) - strtotime($process->departure)))}}]
                    </div>
                    <div class="col-9 sub-line" style="background-color:#001e3b;">
                        <span>{{__('globals.fleet_of')}} {{$process->sourceGalaxy}}:{{$process->sourceSystem}}:{{$process->sourcePlanet}} {{__('globals.reaches')}} {{$process->targetGalaxy}}:{{$process->targetSystem}}:{{$process->targetPlanet}} ({{$process->mission == 1 ? __('missions.deployment') : ($process->mission == 2 ? __('missions.transport') : ($process->mission == 3 ? __('missions.espionage') : ($process->mission == 4 ? __('missions.delta_scan') : ($process->mission == 5 ? __('missions.colonization') : ($process->mission == 6 ? __('missions.attack') : ($process->mission == 7 ? __('missions.invasion') : ''))))))}}) [{{__('globals.return')}}]</span>
                    </div>
                    @endif
                    @endif
                @endif
            @endif
        @endforeach
        <div class="col-12 title-line mt-3">{{__('globals.account')}} "{{ Auth::user()->username }}"</div>
        <div class="col-6 sub-line">{{__('globals.planets')}}</div>
        <div class="col-6 sub-line">{{count($allUserPlanets)}} ({{__('globals.max')}}. {{$maxPlanets}})</div>
        <div class="col-6 sub-line">{{__('globals.planetary_points')}}</div>
        <div class="col-6 sub-line">{{number_format($allPlanetPoints, 0, ',', '.')}}</div>
        <div class="col-6 sub-line">{{__('globals.research_points')}}</div>
        <div class="col-6 sub-line">{{number_format($allResearchPoints, 0, ',', '.')}}</div>
        <div class="col-6 sub-line">{{__('globals.total_points')}}</div>
        <div class="col-6 sub-line">{{number_format($allPlanetPoints + $allResearchPoints, 0, ',', '.')}}</div>
    </div>
</div>
