<div class="container-fluid">
    <div class="row">
        <div class="col-12 title-line">Flottenliste</div>
        @if($fleetsOnMission)
            <div class="col-2 sub-line">Info</div>
            <div class="col-5 col-md-1 sub-line">Startplanet</div>
            <div class="col-6 col-md-1 sub-line">Zielplanet</div>
            <div class="col-6 col-md-2 sub-line">Missionstyp</div>
            <div class="col-6 col-md-2 sub-line">Ankunft</div>
            <div class="col-6 col-md-2 sub-line">Rückkehr</div>
            <div class="col-6 col-md-2 sub-line">Aktionen</div>
            @foreach($fleetsOnMission as $fleets)
                @foreach($fleets as $fleet)
                    <div class="col-2 sub-line">
                        <i class="bi bi-plus-circle" data-toggle="collapse" data-target="#fleet{{$fleet->id}}" aria-expanded="false" aria-controls="fleet{{$fleet->id}}"></i>
                    </div>
                    <div class="col-5 col-md-1 sub-line">
                        {{$fleet->readableSource->galaxy}}:{{$fleet->readableSource->system}}:{{$fleet->readableSource->planet}}
                    </div>
                    <div class="col-6 col-md-1 sub-line">
                        {{$fleet->readableTarget->galaxy}}:{{$fleet->readableTarget->system}}:{{$fleet->readableTarget->planet}}
                    </div>
                    <div class="col-6 col-md-2 sub-line">
                        {{$fleet->mission == 1 ? 'Stationierung' : ($fleet->mission == 2 ? 'Transport' : ($fleet->mission == 3 ? 'Spionage' : ($fleet->mission == 4 ? 'Delta Scan' : ($fleet->mission == 5 ? 'Kolonisierung' : ($fleet->mission == 6 ? 'Angriff' : ($fleet->mission == 7 ? 'Invasion' : ''))))))}}
                    </div>
                    <div class="col-6 col-md-2 sub-line">
                        <span class="js-add-countdown" data-seconds-to-count="{{strtotime($fleet->arrival) - now()->timestamp}}"></span> - [{{date('H:i:s', strtotime($fleet->arrival))}}]
                    </div>
                    <div class="col-6 col-md-2 sub-line">
                        <span class="js-add-countdown" data-seconds-to-count="{{strtotime($fleet->arrival) + (strtotime($fleet->arrival) - strtotime($fleet->departure)) - now()->timestamp}}"></span> - [{{date('H:i:s', strtotime($fleet->arrival) + (strtotime($fleet->arrival) - strtotime($fleet->departure)))}}]
                    </div>
                    <div class="col-6 col-md-2 sub-line">
                        @if($fleet->mission != 0)
                            <a href="/fleetlist/{{$activePlanet}}/edit/{{$fleet->id}}">abbrechen</a>
                        @else
                            -
                        @endif
                    </div>
                    <div class="col-12 sub-line collapse" id="fleet{{$fleet->id}}">
                        <div class="row">
                            <div class="col-12">Flotte</div>
                            @foreach(json_decode($fleet->ship_types) as $ship)
                                @if($ship->amount > 0)
                                    <div class="col-6">{{$ship->ship_name}}</div>
                                    <div class="col-6">{{$ship->amount}}</div>
                                @endif
                            @endforeach
                        </div>
                    </div>
                @endforeach
            @endforeach
        @endif
    </div>
</div>
