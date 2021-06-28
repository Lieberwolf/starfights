<div class="container">
    <div class="row">
        <div class="col-12 title-line mb-1"><span>Missions Übersicht</span></div>
        <div class="col-12">
            <form action="/mission/{{$activePlanet}}/liftoff" method="post">
                @csrf
                <div class="row">
                    <div class="col-6 sub-line p-1">Ziel:</div>
                    <div class="col-6 sub-line p-1">{{$target->galaxy < 10 ? '0' . $target->galaxy : $target->galaxy}}:{{$target->system < 10 ? '0' . $target->system : $target->system}}:{{$target->planet < 10 ? '0' . $target->planet : $target->planet}}</div>
                    <div class="col-6 sub-line p-1">Besitzer:</div>
                    <div class="col-6 sub-line p-1">{{$target->username != null ? $target->username : 'niemand'}} {{$targetProtection ? '(Raidschutz aktiv)': ''}}</div>
                    <div class="col-6 sub-line p-1">Verfügbare Mission:</div>
                    <div class="col-6 sub-line p-1" id="parent">
                        @foreach($allowedMissions as $key => $mission)
                            @if($mission == 1)
                                <div class="form-check">
                                    <input data-toggle="collapse" data-target="#missionCollapse{{$mission}}" name="mission" value="{{$mission}}" type="radio" class="form-check-input" id="missionCheckbox{{$mission}}">
                                    <label class="form-check-label" for="missionCheckbox{{$mission}}">Stationierung</label>
                                </div>
                                <div data-parent="#parent" class="collapse" id="missionCollapse{{$mission}}">
                                    <div class="form-group">
                                        <input class="form-control" type="number" name="mission[{{$mission}}][fe]" placeholder="Eisen" step="1"/>
                                    </div>
                                    <div class="form-group">
                                        <input class="form-control" type="number" name="mission[{{$mission}}][lut]" placeholder="Lutinum" step="1"/>
                                    </div>
                                    <div class="form-group">
                                        <input class="form-control" type="number" name="mission[{{$mission}}][cry]" placeholder="Kristalle" step="1"/>
                                    </div>
                                    <div class="form-group">
                                        <input class="form-control" type="number" name="mission[{{$mission}}][h2o]" placeholder="Wasser" step="1"/>
                                    </div>
                                    <div class="form-group">
                                        <input class="form-control" type="number" name="mission[{{$mission}}][h2]" placeholder="Wasserstoff" step="1"/>
                                    </div>
                                </div>
                            @endif
                            @if($mission == 2)
                                <div class="form-check">
                                    <input data-toggle="collapse" data-target="#missionCollapse{{$mission}}" name="mission" value="{{$mission}}" type="radio" class="form-check-input" id="missionCheckbox{{$mission}}">
                                    <label class="form-check-label" for="missionCheckbox{{$mission}}">Transport</label>
                                </div>
                                <div data-parent="#parent" class="collapse" id="missionCollapse{{$mission}}">
                                    <div class="form-group">
                                        <input class="form-control" type="number" name="mission[{{$mission}}][fe]" placeholder="Eisen" step="1"/>
                                    </div>
                                    <div class="form-group">
                                        <input class="form-control" type="number" name="mission[{{$mission}}][lut]" placeholder="Lutinum" step="1"/>
                                    </div>
                                    <div class="form-group">
                                        <input class="form-control" type="number" name="mission[{{$mission}}][cry]" placeholder="Kristalle" step="1"/>
                                    </div>
                                    <div class="form-group">
                                        <input class="form-control" type="number" name="mission[{{$mission}}][h2o]" placeholder="Wasser" step="1"/>
                                    </div>
                                    <div class="form-group">
                                        <input class="form-control" type="number" name="mission[{{$mission}}][h2]" placeholder="Wasserstoff" step="1"/>
                                    </div>
                                </div>
                            @endif
                            @if($mission == 3)
                                <div class="form-check">
                                    <input name="mission" value="{{$mission}}" type="radio" class="form-check-input" id="missionCheckbox{{$mission}}">
                                    <label class="form-check-label" for="missionCheckbox{{$mission}}">Spionage</label>
                                </div>
                            @endif
                            @if($mission == 4)
                                <div class="form-check">
                                    <input name="mission" value="{{$mission}}" type="radio" class="form-check-input" id="missionCheckbox{{$mission}}">
                                    <label class="form-check-label" for="missionCheckbox{{$mission}}">Delta Scan</label>
                                </div>
                            @endif
                            @if($mission == 5)
                                <div class="form-check">
                                    <input name="mission" value="{{$mission}}" type="radio" class="form-check-input" id="missionCheckbox{{$mission}}">
                                    <label class="form-check-label" for="missionCheckbox{{$mission}}">Kolonisierung</label>
                                </div>
                            @endif
                            @if($mission == 6)
                                <div class="form-check">
                                    <input name="mission" value="{{$mission}}" type="radio" class="form-check-input" id="missionCheckbox{{$mission}}">
                                    <label class="form-check-label" for="missionCheckbox{{$mission}}">Angriff</label>
                                </div>
                            @endif
                            @if($mission == 7)
                                <div class="form-check">
                                    <input name="mission" value="{{$mission}}" type="radio" class="form-check-input" id="missionCheckbox{{$mission}}">
                                    <label class="form-check-label" for="missionCheckbox{{$mission}}">Invasion</label>
                                </div>
                            @endif
                        @endforeach
                    </div>
                    <div class="col-6 sub-line p-1">Ladekapazität:</div>
                    <div class="col-6 sub-line p-1">{{$cargo}}</div>
                    <div class="col-6 sub-line p-1">Benötigter Treibstoff:</div>
                    <div class="col-6 sub-line p-1">{{$fuel}}</div>
                    <div class="col-6 sub-line p-1">Freier Laderaum</div>
                    <div class="col-6 sub-line p-1">{{($cargo-$fuel)}}</div>
                    <div class="col-6 sub-line p-1">Entfernung:</div>
                    <div class="col-6 sub-line p-1">{{$distance}} km</div>
                    <div class="col-6 sub-line p-1">Maximale Geschwindigkeit:</div>
                    <div class="col-6 sub-line p-1">{{$maxSpeed}} km/h</div>
                    <div class="col-6 sub-line p-1">Flugdauer:</div>
                    <div class="col-6 sub-line p-1">{{$duration}}</div>
                    <div class="col-6 sub-line p-1">Ankunft:</div>
                    <div class="col-6 sub-line p-1">{{date('d.m.Y H:i:s', $arrival)}}</div>
                    <div class="col-6 sub-line p-1">Rückkehr:</div>
                    <div class="col-6 sub-line p-1">{{date('d.m.Y H:i:s', $return)}}</div>
                    <div class="col-12 title-line mt-1 p-1">
                        <button type="submit" class="btn btn-secondary">Flotte starten</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
