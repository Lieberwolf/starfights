<div class="container-fluid">
    <form action="/simulation/{{$activePlanet}}/calc" method="post">
        @csrf
        <div class="container-fluid">
            @if($report)
                <div class="row">
                    <div class="col-12">
                        <h6>Ergebnis</h6>
                    </div>
                    <div class="col-4 offset-4">Angreifer</div>
                    <div class="col-4">Verteidiger</div>
                    <div class="col-4">Angriffspunkte</div>
                    <div class="col-4">{{$report[0]["final_attack_value"]}}</div>
                    <div class="col-4">{{$report[1]["final_attack_value"]}}</div>
                    <div class="col-4">Verteidigungspunkte</div>
                    <div class="col-4">{{$report[0]["final_defense_value"]}}</div>
                    <div class="col-4">{{$report[1]["final_defense_value"]}}</div>
                    <div class="col-4">Verbleibende Schiffe (in %)</div>
                    <div class="col-4">~ {{ceil($report[0]["survivedAttRatio"])}}</div>
                    <div class="col-4">~ {{ceil($report[1]["survivedDefRatio"])}}</div>
                    <div class="col-4">Schiffsliste</div>
                    <div class="col-4">
                        <div class="row">
                            @if($report[0]["ship"])
                                <div class="col-4">Schiffstyp</div>
                                <div class="col-4">Eingesetzt</div>
                                <div class="col-4">Verbleibend</div>
                                @foreach($report[0]["ship"] as $ship)
                                    <div class="col-4">{{$ship->ship_name}}</div>
                                    <div class="col-4">{{$ship->amount}}</div>
                                    <div class="col-4">{{$ship->newAmount}}</div>
                                @endforeach
                            @else
                                <div class="col-12">
                                    <span>- keine -</span>
                                </div>
                            @endif
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="row">
                            @if($report[1]["ship"])
                                <div class="col-4">Schiffstyp</div>
                                <div class="col-4">Eingesetzt</div>
                                <div class="col-4">Verbelibend</div>
                                @foreach($report[1]["ship"] as $ship)
                                    <div class="col-4">{{$ship->ship_name}}</div>
                                    <div class="col-4">{{$ship->amount}}</div>
                                    <div class="col-4">{{$ship->newAmount}}</div>
                                @endforeach
                            @else
                                <div class="col-12">
                                    <span>- keine -</span>
                                </div>
                            @endif
                        </div>
                    </div>
                    <div class="col-4 col-md-4 offset-md-8">
                        <div class="row">
                            @if($report[1]["turrets"])
                                <div class="col-4">Verteidigung</div>
                                <div class="col-4">Eingesetzt</div>
                                <div class="col-4">Verbelibend</div>
                                @foreach($report[1]["turrets"] as $turret)
                                    <div class="col-4">{{$turret->turret_name}}</div>
                                    <div class="col-4">{{$turret->amount}}</div>
                                    <div class="col-4">{{$turret->newAmount}}</div>
                                @endforeach
                            @else
                                <div class="col-12">
                                    <span>- keine -</span>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
                <hr/>
            @endif
            <div class="row">
                <div class="col-6">
                    <h6>Angreifer</h6>
                </div>
                <div class="col-6">
                    <h6>Verteidiger</h6>
                </div>
                <div class="col-12">
                    <h6>Schiffe</h6>
                </div>
                @foreach($allShips as $ship)
                    <div class="col-4">
                        <span>{{$ship->ship_name}}</span>
                    </div>
                    <div class="col-4">
                        <div class="form-group">
                            <input value="{{$ship->attackerAmount != null ? $ship->attackerAmount : 0 }}" class="form-control" id="ship-att-{{$ship->id}}" type="number" min="0" step="1" name="sim[att][ship][{{$ship->id}}]"/>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="form-group">
                            <input value="{{$ship->defenderAmount != null ? $ship->defenderAmount : 0 }}" class="form-control" id="ship-def-{{$ship->id}}" type="number" min="0" step="1" name="sim[def][ship][{{$ship->id}}]"/>
                        </div>
                    </div>
                @endforeach
                <div class="col-12">
                    <h6>Verteidigung</h6>
                </div>
                <div class="col-6 offset-6">
                    @foreach($allDefense as $defense)
                        <div class="form-group">
                            <label for="def-def-{{$defense->id}}">{{$defense->turret_name}}</label>
                            <input value="{{$defense->amount != null ? $defense->amount : 0 }}" class="form-control" id="def-def-{{$defense->id}}" type="number" min="0" step="1" name="sim[def][def][{{$defense->id}}]"/>
                        </div>
                    @endforeach
                </div>
                <div class="col-12">
                    <h6>Forschung</h6>
                </div>
                @foreach($allResearch as $research)
                    <div class="col-4">
                        <span>{{$research->research_name}}</span>
                    </div>
                    <div class="col-4">
                        <div class="form-group">
                            <input value="{{$research->attLevel != null ? $research->attLevel : 0 }}" class="form-control" id="research-att-{{$research->id}}" type="number" min="0" step="1" name="sim[att][research][{{$research->id}}]"/>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="form-group">
                            <input value="{{$research->defLevel != null ? $research->defLevel : 0 }}" class="form-control" id="research-def-{{$research->id}}" type="number" min="0" step="1" name="sim[def][research][{{$research->id}}]"/>
                        </div>
                    </div>
                @endforeach
                <div class="col-12">
                    <input type="submit" value="Simulieren" class="btn btn-secondary">
                </div>
            </div>
        </div>
    </form>
</div>
