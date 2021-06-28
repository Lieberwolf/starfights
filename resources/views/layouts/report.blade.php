@if($report)
    <div class="container-fluid report">
        <div class="row">
        @if($report->report_type == 0)
            <div class="col-12 alert-danger">
                <span>Mission fehlgeschlagen</span>
            </div>
        @endif
        @if($report->report_type == 1)
            <div class="col-12 title-line">
                Delta Scan von {{$report->defender_planet->galaxy .':'. $report->defender_planet->system . ':' . $report->defender_planet->planet}}
            </div>
            <div class="col-6 sub-line">Zeitpunkt</div>
            <div class="col-6 sub-line">{{date('d.m.Y H:i:s', $report->created_at->timestamp)}}</div>
            @if($report->defender_id != null && $report->defender_id != 0)
                <div class="col-12 title-line">Schiffe am Planeten</div>
                <div class="col-12 report-ship-wrapper">
                    <div class="row">
                        @if($report->defender_fleet == null)
                            <div class="col-12 sub-line">- keine -</div>
                        @else
                            @foreach($report->defender_fleet as $ship)
                                @if($ship->amount > 0)
                                    <div class="col-6 sub-line">
                                        {{$ship->ship_name}}
                                    </div>
                                    <div class="col-6 sub-line">
                                        {{number_format(floor($ship->amount), 0, ',', '.')}}
                                    </div>
                                @endif
                            @endforeach
                        @endif
                    </div>
                </div>
                <div class="col-12 title-line">Verteidigungsanlagen</div>
                @if($report->defender_defense == null)
                    <div class="col-12 sub-line">- keine -</div>
                @else
                    @foreach($report->defender_defense as $defense)
                        @if($defense->amount > 0)
                            <div class="col-6 sub-line">
                                {{$defense->turret_name}}
                            </div>
                            <div class="col-6 sub-line">
                                {{number_format(floor($defense->amount), 0, ',', '.')}}
                            </div>
                        @endif
                    @endforeach
                @endif

                <div class="col-12 title-line">Gebäude auf dem Planeten</div>
                @foreach($report->planet_infrastructure as $building)
                    <div class="col-6 sub-line">
                        {{$building->building_name}}
                    </div>
                    <div class="col-6 sub-line">
                        Stufe: {{$building->level}}
                    </div>
                @endforeach

                <div class="col-12 title-line">Forschungsstufen</div>
                    @foreach($report->defender_knowledge as $research)
                        <div class="col-6 sub-line">
                            {{$research->research_name}}
                        </div>
                        <div class="col-6 sub-line">
                            Stufe: {{$research->level}}
                        </div>
                    @endforeach

                <div class="col-12 col-md-6 offset-md-3 title-line">Rohstoffe</div>
                @if($report->resources == null)
                    <div class="col-12 sub-line">- keine -</div>
                @else
                    @foreach($report->resources as $key => $resource)
                        <div class="col-6 col-md-3 offset-md-3 sub-line">
                            @if($key == 'fe')
                                Eisen
                            @endif
                            @if($key == 'lut')
                                Lutinum
                            @endif
                            @if($key == 'cry')
                                Kristalle
                            @endif
                            @if($key == 'h2o')
                                Wasser
                            @endif
                            @if($key == 'h2')
                                Wasserstoff
                            @endif
                        </div>
                        <div class="col-6 col-md-3 sub-line">
                            {{number_format(floor($resource), 0, ',', '.')}}
                        </div>
                    @endforeach
                @endif
            @endif
            <div class="col-12 col-md-6 offset-md-3 title-line">Planeten Informationen</div>
            @foreach($report->planet_info as $key => $info)
                <div class="col-6 col-md-3 offset-md-3 sub-line">
                    @if($key == 'diameter')
                        Durchmesser in KM
                    @endif
                    @if($key == 'temperature')
                        Durchschnittliche Temperatur
                    @endif
                    @if($key == 'atmosphere')
                        Atmosphäre
                    @endif
                    @if($key == 'resource_bonus')
                        Resourcen Bonus
                    @endif
                </div>
                <div class="col-6 col-md-3 sub-line">
                    @if($key == 'atmosphere')
                        @if($key == 0)
                            Nein
                        @else
                            Ja
                        @endif
                    @else
                        {{$info}}
                    @endif
                </div>
            @endforeach

        @endif
        @if($report->report_type == 2)
            <div class="col-12 title-line">
                Spionagebericht von {{$report->defender_planet->galaxy .':'. $report->defender_planet->system . ':' . $report->defender_planet->planet}}
            </div>
            <div class="col-6 sub-line">Zeitpunkt</div>
            <div class="col-6 sub-line">{{date('d.m.Y H:i:s', $report->created_at->timestamp)}}</div>

            <div class="col-12 title-line">Schiffe am Planeten</div>
                <div class="col-12 report-ship-wrapper">
                    <div class="row">
                        @if($report->defender_fleet == null)
                            <div class="col-12 sub-line">- keine -</div>
                        @else
                            @foreach($report->defender_fleet as $ship)
                                @if($ship->amount > 0)
                                    <div class="col-6 sub-line">
                                        {{$ship->ship_name}}
                                    </div>
                                    <div class="col-6 sub-line">
                                        {{number_format(floor($ship->amount), 0, ',', '.')}}
                                    </div>
                                @endif
                            @endforeach
                        @endif
                    </div>
                </div>
            <div class="col-12 title-line">Verteidigungsanlagen</div>
            @if($report->defender_defense == null)
                <div class="col-12 sub-line">- keine -</div>
            @else
                @foreach($report->defender_defense as $defense)
                    @if($defense->amount > 0)
                        <div class="col-6 sub-line">
                            {{$defense->turret_name}}
                        </div>
                        <div class="col-6 sub-line">
                            {{number_format(floor($defense->amount), 0, ',', '.')}}
                        </div>
                    @endif
                @endforeach
            @endif
            <div class="col-12 col-md-6 offset-md-3 title-line">Rohstoffe</div>
            @if($report->resources == null)
                <div class="col-12 sub-line">- keine -</div>
            @else
                @foreach($report->resources as $key => $resource)
                    <div class="col-6 col-md-3 offset-md-3 sub-line">
                        @if($key == 'fe')
                            Eisen
                        @endif
                        @if($key == 'lut')
                            Lutinum
                        @endif
                        @if($key == 'cry')
                            Kristalle
                        @endif
                        @if($key == 'h2o')
                            Wasser
                        @endif
                        @if($key == 'h2')
                            Wasserstoff
                        @endif
                    </div>
                    <div class="col-6 col-md-3 sub-line">
                        {{number_format(floor($resource), 0, ',', '.')}}
                    </div>
                @endforeach
            @endif
        @endif
        @if($report->report_type == 3)
            <div class="col-12 title-line">Schlachtresultate</div>

            <div class="col-6 sub-line">Zeitpunkt</div>
            <div class="col-6 sub-line">{{date('d.m.Y H:i:s', $report->created_at->timestamp)}}</div>

            <div class="col-6 title-line">Angreifer</div>
            <div class="col-6 title-line">Verteidiger</div>

            <div class="col-6 sub-line">{{$report->attacker_name->username}}</div>
            <div class="col-6 sub-line">{{$report->defender_name->username}}</div>

            <div class="col-6 sub-line">{{$report->attacker_planet->galaxy .':'. $report->attacker_planet->system . ':' . $report->attacker_planet->planet}}</div>
            <div class="col-6 sub-line">{{$report->defender_planet->galaxy .':'. $report->defender_planet->system . ':' . $report->defender_planet->planet}}</div>

            <div class="col-12 title-line">Flotten</div>

                <div class="col-6">
                    <div class="row">
                        @if(count($report->attacker_fleet) > 0)
                            <div class="col-4 sub-line">Schiffstyp</div>
                            <div class="col-4 sub-line">Eingesetzt</div>
                            <div class="col-4 sub-line">Verbleibend</div>
                            @foreach($report->attacker_fleet as $ship)
                                @if($ship->amount > 0)
                                    <div class="col-4 sub-line">{{$ship->ship_name}}</div>
                                    <div class="col-4 sub-line">{{number_format($ship->amount, 0, ',', '.')}}</div>
                                    <div class="col-4 sub-line">{{number_format($ship->newAmount, 0, ',', '.')}}</div>
                                @endif
                            @endforeach
                        @endif
                    </div>
                </div>

                <div class="col-6">
                    <div class="row">
                        @if($report->defender_fleet)
                            @if(count($report->defender_fleet) > 0)
                                <div class="col-4 sub-line">Schiffstyp</div>
                                <div class="col-4 sub-line">Eingesetzt</div>
                                <div class="col-4 sub-line">Verbleibend</div>
                                @foreach($report->defender_fleet as $ship)
                                    @if($ship->amount > 0)
                                        <div class="col-4 sub-line">{{$ship->ship_name}}</div>
                                        <div class="col-4 sub-line">{{number_format($ship->amount, 0, ',', '.')}}</div>
                                        <div class="col-4 sub-line">{{number_format($ship->newAmount, 0, ',', '.')}}</div>
                                    @endif
                                @endforeach
                            @endif
                        @else
                            <div class="col-12 sub-line">- keine -</div>
                        @endif
                    </div>
                </div>

            <div class="col-12 col-md-6 offset-md-6 title-line">Verteidigungsanlagen</div>
            <div class="col-12 col-md-6 offset-md-6">
                <div class="row">
                    @if($report->defender_defense)
                        @if(count($report->defender_defense) > 0)
                            <div class="col-4 sub-line">Verteidigung</div>
                            <div class="col-4 sub-line">Eingesetzt</div>
                            <div class="col-4 sub-line">Verbleibend</div>
                            @foreach($report->defender_defense as $turret)
                                @if($turret->amount > 0)
                                    <div class="col-4 sub-line">{{$turret->turret_name}}</div>
                                    <div class="col-4 sub-line">{{number_format($turret->amount, 0, ',', '.')}}</div>
                                    <div class="col-4 sub-line">{{number_format($turret->newAmount, 0, ',', '.')}}</div>
                                @endif
                            @endforeach
                        @endif
                    @else
                        <div class="col-12 sub-line">- keine -</div>
                    @endif
                </div>
            </div>

            <div class="col-12 title-line">Rohstoffe</div>
            @if($report->resources != null)
                @foreach($report->resources as $key => $resource)
                    @if($resource > 0)
                        <div class="col-6 sub-line">
                            @if($key == 'fe')
                                Eisen
                            @endif
                            @if($key == 'lut')
                                Lutinum
                            @endif
                            @if($key == 'cry')
                                Kristalle
                            @endif
                            @if($key == 'h2o')
                                Wasser
                            @endif
                            @if($key == 'h2')
                                Wasserstoff
                            @endif
                        </div>
                        <div class="col-6 sub-line">
                            {{number_format(floor($resource), 0, ',', '.')}}
                        </div>
                    @endif
                @endforeach
            @else
                <div class="col-12 sub-line">
                    <span>- keine -</span>
                </div>
            @endif
        @endif
        @if($report->report_type == 4)
                <div class="col-12 title-line">Schlachtresultate</div>

                <div class="col-6 sub-line">Zeitpunkt</div>
                <div class="col-6 sub-line">{{date('d.m.Y H:i:s', $report->created_at->timestamp)}}</div>

                <div class="col-6 title-line">Angreifer</div>
                <div class="col-6 title-line">Verteidiger</div>

                <div class="col-6 sub-line">{{$report->attacker_name->username}}</div>
                <div class="col-6 sub-line">{{$report->defender_name->username}}</div>

                <div class="col-6 sub-line">{{$report->attacker_planet->galaxy .':'. $report->attacker_planet->system . ':' . $report->attacker_planet->planet}}</div>
                <div class="col-6 sub-line">{{$report->defender_planet->galaxy .':'. $report->defender_planet->system . ':' . $report->defender_planet->planet}}</div>

                <div class="col-12 title-line">Flotten</div>

                <div class="col-6">
                    <div class="row">
                        @if(count($report->attacker_fleet) > 0)
                            <div class="col-4 sub-line">Schiffstyp</div>
                            <div class="col-4 sub-line">Eingesetzt</div>
                            <div class="col-4 sub-line">Verbleibend</div>
                            @foreach($report->attacker_fleet as $ship)
                                @if($ship->amount > 0)
                                    <div class="col-4 sub-line">{{$ship->ship_name}}</div>
                                    <div class="col-4 sub-line">{{number_format($ship->amount, 0, ',', '.')}}</div>
                                    <div class="col-4 sub-line">{{number_format($ship->newAmount, 0, ',', '.')}}</div>
                                @endif
                            @endforeach
                        @endif
                    </div>
                </div>

                <div class="col-6">
                    <div class="row">
                        @if($report->defender_fleet)
                            @if(count($report->defender_fleet) > 0)
                                <div class="col-4 sub-line">Schiffstyp</div>
                                <div class="col-4 sub-line">Eingesetzt</div>
                                <div class="col-4 sub-line">Verbleibend</div>
                                @foreach($report->defender_fleet as $ship)
                                    @if($ship->amount > 0)
                                        <div class="col-4 sub-line">{{$ship->ship_name}}</div>
                                        <div class="col-4 sub-line">{{number_format($ship->amount, 0, ',', '.')}}</div>
                                        <div class="col-4 sub-line">{{number_format($ship->newAmount, 0, ',', '.')}}</div>
                                    @endif
                                @endforeach
                            @endif
                        @else
                            <div class="col-12 sub-line">- keine -</div>
                        @endif
                    </div>
                </div>

                <div class="col-12 col-md-6 offset-md-6 title-line">Verteidigungsanlagen</div>
                <div class="col-12 col-md-6 offset-md-6">
                    <div class="row">
                        @if($report->defender_defense)
                            @if(count($report->defender_defense) > 0)
                                <div class="col-4 sub-line">Verteidigung</div>
                                <div class="col-4 sub-line">Eingesetzt</div>
                                <div class="col-4 sub-line">Verbleibend</div>
                                @foreach($report->defender_defense as $turret)
                                    @if($turret->amount > 0)
                                        <div class="col-4 sub-line">{{$turret->turret_name}}</div>
                                        <div class="col-4 sub-line">{{number_format($turret->amount, 0, ',', '.')}}</div>
                                        <div class="col-4 sub-line">{{number_format($turret->newAmount, 0, ',', '.')}}</div>
                                    @endif
                                @endforeach
                            @endif
                        @else
                            <div class="col-12 sub-line">- keine -</div>
                        @endif
                    </div>
                </div>

                <div class="col-12 title-line">Rohstoffe</div>
                @if($report->resources != null)
                    @foreach($report->resources as $key => $resource)
                        @if($resource > 0)
                            <div class="col-6 sub-line">
                                @if($key == 'fe')
                                    Eisen
                                @endif
                                @if($key == 'lut')
                                    Lutinum
                                @endif
                                @if($key == 'cry')
                                    Kristalle
                                @endif
                                @if($key == 'h2o')
                                    Wasser
                                @endif
                                @if($key == 'h2')
                                    Wasserstoff
                                @endif
                            </div>
                            <div class="col-6 sub-line">
                                {{number_format(floor($resource), 0, ',', '.')}}
                            </div>
                        @endif
                    @endforeach
                @else
                    <div class="col-12 sub-line">
                        <span>- keine -</span>
                    </div>
                @endif

            <div class="col-12 alert-danger">
                Der Angreifer hat den Planeten eingenommen!
            </div>
        @endif
        </div>
    </div>
@endif
