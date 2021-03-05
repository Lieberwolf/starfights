<div class="container event-log">
    <div class="row">
        <div class="col-12 title-line">
            Planet "{{$planetInformation->planet_name != null ? $planetInformation->planet_name : 'Unbenannter Planet'}}"
            <a href="/universe/{{$defaultPlanet}}/{{$planetInformation->galaxy}}/{{$planetInformation->system}}">({{$planetInformation->galaxy}}:{{$planetInformation->system}}:{{$planetInformation->planet}})</a>
            <a href="/details/{{$planetInformation->id}}">[Details]</a>
        </div>
        <div class="col-12 sub-line">
            <div class="row">
                <div class="col-6 col-md-2 offset-md-1">
                    <a href="">Planetenübersicht</a>
                </div>
                <div class="col-6 col-md-2">
                    <a href="">Schiffsübersicht</a>
                </div>
                <div class="col-12 col-md-2">
                    <a href="">Notizblock</a>
                </div>
                <div class="col-6 col-md-2">
                    <a href="">Kampfstatistik</a>
                </div>
                <div class="col-6 col-md-2">
                    <a href="">Handel</a>
                </div>
            </div>
        </div>
        <div class="col-6 sub-line">Serverzeit</div>
        <div class="col-6 sub-line">{{now()}}</div>
        <div class="col-6 sub-line">Automatischer Logout</div>
        <div class="col-6 sub-line">{{now()->addHours(4)}}</div>
        <div class="col-12 title-line mt-3">Planetenbild <a href="/details/{{$activePlanet}}">[ändern]</a></div>
        <div class="col-12 sub-line py-2">
            <img class="img-fluid" src="{{$planetInformation->image != null ? $planetInformation->image : 'https://i.pinimg.com/originals/b7/8e/31/b78e312d9ca9152233d995d2803e1b8d.jpg'}}" title="Toller Planet" alt="Kaputt" width="480" height="270"/>
        </div>
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
        <div class="col-12 sub-line">- keine -</div>
        <div class="col-12 title-line mt-3">Ereignisse</div>
        @if(count($planetaryProcesses) > 0 || $fleetsOnMission)
            <div class="col-3 text-right sub-line">Restzeit - [Uhrzeit]</div>
            <div class="col-9 sub-line">Ereignis</div>
        @else
            <div class="col-12 sub-line">- keine -</div>
        @endif
        @if($foreignFleets)
            @foreach($foreignFleets as $incomingFleet)
                @foreach($incomingFleet as $fleet)
                    @if(strtotime($fleet->arrival) > now()->timestamp)
                        <div class="col-3 text-right {{($fleet->mission == 2 ? 'transport' : ($fleet->mission == 3 ? 'espionage' : ($fleet->mission == 4 ? 'espionage' : ($fleet->mission == 6 ? 'attack' : ($fleet->mission == 7 ? 'invasion' : '')))))}}">
                            <span class="js-add-countdown" data-seconds-to-count="{{strtotime($fleet->arrival) - now()->timestamp}}"></span> - [{{date('H:i:s', strtotime($fleet->arrival) - now()->timestamp)}}]
                        </div>
                        <div class="col-9 {{($fleet->mission == 2 ? 'transport' : ($fleet->mission == 3 ? 'espionage' : ($fleet->mission == 4 ? 'espionage' : ($fleet->mission == 6 ? 'attack' : ($fleet->mission == 7 ? 'invasion' : '')))))}}">
                            <span>Flotte von {{$fleet->galaxy}}:{{$fleet->system}}:{{$fleet->planet}} erreicht {{$fleet->targetPlanet->galaxy}}:{{$fleet->targetPlanet->system}}:{{$fleet->targetPlanet->planet}} ({{$fleet->mission == 1 ? 'Stationierung' : ($fleet->mission == 2 ? 'Transport' : ($fleet->mission == 3 ? 'Spionage' : ($fleet->mission == 4 ? 'Delta Scan' : ($fleet->mission == 5 ? 'Kolonisierung' : ($fleet->mission == 6 ? 'Angriff' : ($fleet->mission == 7 ? 'Invasion' : ''))))))}})</span>
                        </div>
                    @endif
                @endforeach
            @endforeach
        @endif
        @if($fleetsOnMission)
            @foreach($fleetsOnMission as $fleets)
                @foreach($fleets as $fleet)
                    @if(strtotime($fleet->arrival) > now()->timestamp)
                        <div class="col-3 text-right" style="background-color:#102f4c;">
                            <span class="js-add-countdown" data-seconds-to-count="{{strtotime($fleet->arrival) - now()->timestamp}}"></span> - [{{date('H:i:s', strtotime($fleet->arrival) - now()->timestamp)}}]
                        </div>
                        <div class="col-9" style="background-color:#102f4c;">
                            <span>Flotte von {{$fleet->readableSource->galaxy}}:{{$fleet->readableSource->system}}:{{$fleet->readableSource->planet}} erreicht {{$fleet->readableTarget->galaxy}}:{{$fleet->readableTarget->system}}:{{$fleet->readableTarget->planet}} ({{$fleet->mission == 1 ? 'Stationierung' : ($fleet->mission == 2 ? 'Transport' : ($fleet->mission == 3 ? 'Spionage' : ($fleet->mission == 4 ? 'Delta Scan' : ($fleet->mission == 5 ? 'Kolonisierung' : ($fleet->mission == 6 ? 'Angriff' : ($fleet->mission == 7 ? 'Invasion' : ''))))))}})</span>
                        </div>
                    @endif
                    @if($fleet->mission != 1 && $fleet->mission != 3 && $fleet->mission != 5)
                        <div class="col-3 text-right" style="background-color:#001e3b;">
                            <span class="js-add-countdown" data-seconds-to-count="{{strtotime($fleet->arrival) + (strtotime($fleet->arrival) - strtotime($fleet->departure)) - now()->timestamp}}"></span> - [{{date('H:i:s', strtotime($fleet->arrival) + (strtotime($fleet->arrival) - strtotime($fleet->departure)) - now()->timestamp)}}]
                        </div>
                        <div class="col-9" style="background-color:#001e3b;">
                            <span>Flotte von {{$fleet->readableSource->galaxy}}:{{$fleet->readableSource->system}}:{{$fleet->readableSource->planet}} erreicht {{$fleet->readableTarget->galaxy}}:{{$fleet->readableTarget->system}}:{{$fleet->readableTarget->planet}} ({{$fleet->mission == 1 ? 'Stationierung' : ($fleet->mission == 2 ? 'Transport' : ($fleet->mission == 3 ? 'Spionage' : ($fleet->mission == 4 ? 'Delta Scan' : ($fleet->mission == 5 ? 'Kolonisierung' : ($fleet->mission == 6 ? 'Angriff' : ($fleet->mission == 7 ? 'Invasion' : ''))))))}}) [Rückkehr]</span>
                        </div>
                    @endif
                @endforeach
            @endforeach
        @endif
        @foreach($planetaryProcesses as $process)
            @if($process)
                @if($process->type == 'building')
                    <div class="col-3 text-right" style="background-color:#BD5C03;"><span class="js-add-countdown" data-seconds-to-count="{{strtotime($process->finished_at) - now()->timestamp}}"></span> - [{{date('H:i:s', strtotime($process->finished_at))}}]</div>
                    <div class="col-9" style="background-color:#BD5C03;">
                        <span>Konstruktion von {{$process->building_name}} Stufe {{$process->level != null ? $process->level + 1 : 1}} auf {{$process->galaxy}}:{{$process->system}}:{{$process->planet}}</span>
                    </div>
                @endif
                @if($process->type == 'research')
                    <div class="col-3 text-right" style="background-color:#067D06;"><span class="js-add-countdown" data-seconds-to-count="{{strtotime($process->finished_at) - now()->timestamp}}"></span> - [{{date('H:i:s', strtotime($process->finished_at))}}]</div>
                    <div class="col-9" style="background-color:#067D06;">
                        <span>Forschung von {{$process->research_name}} Stufe {{$process->level != null ? $process->level + 1 : 1}} auf {{$process->galaxy}}:{{$process->system}}:{{$process->planet}}</span>
                    </div>
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
