<div class="container-fluid">
    <div class="row">
        <div class="col-12 title-line">
            Planet "{{$planetInformation->planet_name != null ? $planetInformation->planet_name : 'Unbenannter Planet'}}"
            <a href="/universe/{{$defaultPlanet}}/{{$planetInformation->galaxy}}/{{$planetInformation->system}}">({{$planetInformation->galaxy}}:{{$planetInformation->system}}:{{$planetInformation->planet}})</a>
            <a href="/details/{{$planetInformation->id}}">[Details]</a>
        </div>
        <div class="col-12 sub-line">
            <div class="row">
                <div class="col-4 col-md-2">
                    <a href="/resources_overview/{{$activePlanet}}">Ressourcenübersicht</a>
                </div>
                <div class="col-4 col-md-2">
                    <a href="/production_overview/{{$activePlanet}}">Produktionsübersicht</a>
                </div>
                <div class="col-4 col-md-2">
                    <a href="/planetary/{{$activePlanet}}">Planetenübersicht</a>
                </div>
                <div class="col-4 col-md-2">
                    <a href="/ships/{{$activePlanet}}">Schiffsübersicht</a>
                </div>
                <div class="col-4 col-md-2">
                    <a href="/notice/{{$activePlanet}}">Notizblock</a>
                </div>
                <div class="col-4 col-md-2">
                    <a href="/statistics/{{$activePlanet}}/user/{{$planetInformation->user_id}}">Kampfstatistik</a>
                </div>
            </div>
        </div>
        <div class="col-6 sub-line">Serverzeit</div>
        <div class="col-6 sub-line">{{now()}}</div>
        @if($planetInformation->image != null)
            <div class="col-12 title-line mt-3">Planetenbild <a href="/details/{{$activePlanet}}">[ändern]</a></div>
            <div class="col-12 sub-line py-2">
                <img class="img-fluid" src="{{$planetInformation->image}}" title="Toller Planet" alt="Kaputt"/>
            </div>
        @endif
        <div class="col-12 title-line mt-3">Schiffe am Planeten</div>
        @if($shipsAtPlanet)
            @foreach(json_decode($shipsAtPlanet->ship_types) as $ship)
                @if($ship->amount > 0)
                    <div class="col-6 sub-line">{{$ship->ship_name}}</div>
                    <div class="col-6 sub-line">{{number_format($ship->amount, 0, ',', '.')}}</div>
                @endif
            @endforeach
        @else
            <div class="col-12 sub-line">- keine -</div>
        @endif
        <div class="col-12 title-line mt-3">Verteidigungsanlagen</div>
        @if($turretsAtPlanet)
            @foreach(json_decode($turretsAtPlanet->turret_types) as $turret)
                @if($turret->amount > 0)
                    <div class="col-6 sub-line">{{$turret->turret_name}}</div>
                    <div class="col-6 sub-line">{{number_format($turret->amount, 0, ',', '.')}}</div>
                @endif
            @endforeach
        @else
            <div class="col-12 sub-line">- keine -</div>
        @endif
        <div class="col-12 title-line mt-3">Ereignisse</div>
        @if(count($planetaryProcesses) > 0)
            <div class="col-3 text-right sub-line">Restzeit - [Uhrzeit]</div>
            <div class="col-9 sub-line">Ereignis</div>
        @else
            <div class="col-12 sub-line">- keine -</div>
        @endif
        @foreach($planetaryProcesses as $process)
            @if($process)
                @if($process->type == 'building')
                    <div class="col-3 text-right sub-line" style="background-color:#BD5C03;"><span class="js-add-countdown" data-seconds-to-count="{{strtotime($process->finished_at) - now()->timestamp}}"></span> - [{{date('H:i:s', strtotime($process->finished_at))}}]</div>
                    <div class="col-9 sub-line" style="background-color:#BD5C03;">
                        <span>Konstruktion von {{$process->building_name}} Stufe {{$process->level != null ? $process->level + 1 : 1}} auf {{$process->galaxy}}:{{$process->system}}:{{$process->planet}}</span>
                    </div>
                @endif
                @if($process->type == 'research')
                    <div class="col-3 text-right sub-line" style="background-color:#067D06;"><span class="js-add-countdown" data-seconds-to-count="{{strtotime($process->finished_at) - now()->timestamp}}"></span> - [{{date('H:i:s', strtotime($process->finished_at))}}]</div>
                    <div class="col-9 sub-line" style="background-color:#067D06;">
                        <span>Forschung von {{$process->research_name}} Stufe {{$process->level != null ? $process->level + 1 : 1}} auf {{$process->galaxy}}:{{$process->system}}:{{$process->planet}}</span>
                    </div>
                @endif
                @if($process->type == 'foreignFleet' && $process->mission != 0)
                    <div class="col-3 text-right sub-line {{($process->mission == 2 ? 'transport' : ($process->mission == 3 ? 'espionage' : ($process->mission == 4 ? 'espionage' : ($process->mission == 6 ? 'attack' : ($process->mission == 7 ? 'invasion' : '')))))}}">
                        <span class="js-add-countdown" data-seconds-to-count="{{strtotime($process->arrival) - now()->timestamp}}"></span> - [{{date('H:i:s', strtotime($process->arrival))}}]
                    </div>
                    <div class="col-9 sub-line {{($process->mission == 2 ? 'transport' : ($process->mission == 3 ? 'espionage' : ($process->mission == 4 ? 'espionage' : ($process->mission == 6 ? 'attack' : ($process->mission == 7 ? 'invasion' : '')))))}}">
                        <span>Flotte von {{$process->sourceGalaxy}}:{{$process->sourceSystem}}:{{$process->sourcePlanet}} erreicht {{$process->targetGalaxy}}:{{$process->targetSystem}}:{{$process->targetPlanet}} ({{$process->mission == 1 ? 'Stationierung' : ($process->mission == 2 ? 'Transport' : ($process->mission == 3 ? 'Spionage' : ($process->mission == 4 ? 'Delta Scan' : ($process->mission == 5 ? 'Kolonisierung' : ($process->mission == 6 ? 'Angriff' : ($process->mission == 7 ? 'Invasion' : ''))))))}})</span>
                    </div>
                @endif
                @if($process->type == 'fleet')
                    @if(strtotime($process->arrival) > now()->timestamp && !property_exists($process, 'aborted'))
                    <div class="col-3 text-right sub-line" style="background-color:#102f4c;">
                        <span class="js-add-countdown" data-seconds-to-count="{{strtotime($process->arrival) - now()->timestamp}}"></span> - [{{date('H:i:s', strtotime($process->arrival))}}]
                    </div>
                    <div class="col-9 sub-line" style="background-color:#102f4c;">
                        <span>Flotte von {{$process->sourceGalaxy}}:{{$process->sourceSystem}}:{{$process->sourcePlanet}} erreicht {{$process->targetGalaxy}}:{{$process->targetSystem}}:{{$process->targetPlanet}} ({{$process->mission == 1 ? 'Stationierung' : ($process->mission == 2 ? 'Transport' : ($process->mission == 3 ? 'Spionage' : ($process->mission == 4 ? 'Delta Scan' : ($process->mission == 5 ? 'Kolonisierung' : ($process->mission == 6 ? 'Angriff' : ($process->mission == 7 ? 'Invasion' : ''))))))}})</span>
                    </div>
                    @endif
                    @if($process->mission == 0)
                    <div class="col-3 text-right sub-line" style="background-color:#001e3b;">
                        <span class="js-add-countdown" data-seconds-to-count="{{strtotime($process->arrival) + (strtotime($process->arrival) - strtotime($process->departure)) - now()->timestamp}}"></span> - [{{date('H:i:s', strtotime($process->arrival) + (strtotime($process->arrival) - strtotime($process->departure)))}}]
                    </div>
                    <div class="col-9 sub-line" style="background-color:#001e3b;">
                        <span>Flotte von {{$process->sourceGalaxy}}:{{$process->sourceSystem}}:{{$process->sourcePlanet}} kehrt zurück [Mission abgebrochen]</span>
                    </div>
                    @else
                    @if(property_exists($process, 'aborted') && $process->aborted == 1)
                    <div class="col-3 text-right sub-line" style="background-color:#001e3b;">
                        <span class="js-add-countdown" data-seconds-to-count="{{strtotime($process->arrival) + (strtotime($process->arrival) - strtotime($process->departure)) - now()->timestamp}}"></span> - [{{date('H:i:s', strtotime($process->arrival) + (strtotime($process->arrival) - strtotime($process->departure)))}}]
                    </div>
                    <div class="col-9 sub-line" style="background-color:#001e3b;">
                        <span>Flotte von {{$process->sourceGalaxy}}:{{$process->sourceSystem}}:{{$process->sourcePlanet}} erreicht {{$process->targetGalaxy}}:{{$process->targetSystem}}:{{$process->targetPlanet}} ({{$process->mission == 1 ? 'Stationierung' : ($process->mission == 2 ? 'Transport' : ($process->mission == 3 ? 'Spionage' : ($process->mission == 4 ? 'Delta Scan' : ($process->mission == 5 ? 'Kolonisierung' : ($process->mission == 6 ? 'Angriff' : ($process->mission == 7 ? 'Invasion' : ''))))))}}) [Rückkehr]</span>
                    </div>
                    @endif
                    @endif
                @endif
            @endif
        @endforeach
        <div class="col-12 title-line mt-3">Account "{{ Auth::user()->username }}"</div>
        <div class="col-6 sub-line">Planeten</div>
        <div class="col-6 sub-line">{{count($allUserPlanets)}} (max. {{$maxPlanets}})</div>
        <div class="col-6 sub-line">Planetenpunkte</div>
        <div class="col-6 sub-line">{{number_format($allPlanetPoints, 0, ',', '.')}}</div>
        <div class="col-6 sub-line">Forschungspunkte</div>
        <div class="col-6 sub-line">{{number_format($allResearchPoints, 0, ',', '.')}}</div>
        <div class="col-6 sub-line">Accountpunkte</div>
        <div class="col-6 sub-line">{{number_format($allPlanetPoints + $allResearchPoints, 0, ',', '.')}}</div>
    </div>
</div>
