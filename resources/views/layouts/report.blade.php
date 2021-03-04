@if($report)
    <div class="container-fluid">
        <div class="row">

            <div class="col-12">
                <h6>Schlachtresultate</h6>
            </div>

            <div class="col-6">Zeitpunkt</div>
            <div class="col-6">{{date('d.m.Y H:i:s', $report->created_at->timestamp)}}</div>

            <div class="col-6">Angreifer</div>
            <div class="col-6">Verteidiger</div>

            <div class="col-6">{{$report->attacker_name->username}}</div>
            <div class="col-6">{{$report->defender_name->username}}</div>

            <div class="col-6">{{$report->attacker_planet->galaxy .':'. $report->attacker_planet->system . ':' . $report->attacker_planet->planet}}</div>
            <div class="col-6">{{$report->defender_planet->galaxy .':'. $report->defender_planet->system . ':' . $report->defender_planet->planet}}</div>

            <div class="col-12">
                Flotten
            </div>

            <div class="col-6">
                <div class="row">
                    @if(count($report->attacker_fleet) > 0)
                        <div class="col-4">Schiffstyp</div>
                        <div class="col-4">Eingesetzt</div>
                        <div class="col-4">Verbleibend</div>
                        @foreach($report->attacker_fleet as $ship)
                            <div class="col-4">{{$ship->ship_name}}</div>
                            <div class="col-4">{{$ship->amount}}</div>
                            <div class="col-4">{{$ship->newAmount}}</div>
                        @endforeach
                    @endif
                </div>
            </div>

            <div class="col-6">
                <div class="row">
                    @if($report->defender_fleet)
                        @if(count($report->defender_fleet) > 0)
                            <div class="col-4">Schiffstyp</div>
                            <div class="col-4">Eingesetzt</div>
                            <div class="col-4">Verbleibend</div>
                            @foreach($report->defender_fleet as $ship)
                                <div class="col-4">{{$ship->ship_name}}</div>
                                <div class="col-4">{{$ship->amount}}</div>
                                <div class="col-4">{{$ship->newAmount}}</div>
                            @endforeach
                        @endif
                    @else
                        <div class="col-12">
                            - keine -
                        </div>
                    @endif
                </div>
            </div>

            <div class="col-12">
                Rohstoffe
            </div>
            @foreach($report->resources as $key => $resource)
                @if($resource > 0)
                    <div class="col-6">
                        {{$key}}
                    </div>
                    <div class="col-6">
                        {{$resource}}
                    </div>
                @endif
            @endforeach
        </div>
    </div>
@endif
