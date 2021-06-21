<div class="container">
    <div class="row">
        @if($currentTurrets)
            @foreach($currentTurrets->nextTurretIn as $next)
                @if($next->planet == $activePlanet)
                    <div class="col-8 current-process process-entry">
                        <span>Aktuell in Bau: {{$currentTurrets->turret_name}} ({{$currentTurrets->amount_left}} Stück)</span>
                        <br/>
                        <span>Nächste Anlage fertig in: </span>
                        <span class="js-add-countdown" data-seconds-to-count="{{$next->seconds}}">{{$next->buildtime != false ? $next->buildtime : ''}}</span>
                        <br/>
                        <span>Fertigstellung: {{ date("d.m.Y H:m:s", strtotime($currentTurrets->finished_at)) }}</span>
                    </div>
                    <div class="col-4 process-action">
                        <a href="/defense/{{$activePlanet}}/edit">Abbrechen</a><br/>
                    </div>
                @endif
            @endforeach
        @endif
        @if(count($turretList) > 0)
            @foreach($turretList as $key => $turret)
                @if($turret->buildable)
                    <div class="col-8 process-entry">
                        <span>{{$turret->turret_name}}</span>
                        <span>{{$turret->description}}</span>
                        <span>Baukosten:</span>
                        <span>
                            @if($turret->fe > 0)
                                Eisen: {{number_format($turret->fe,0, ',', '.')}}
                            @endif
                            @if($turret->lut > 0)
                                Lutinum: {{number_format($turret->lut,0, ',', '.')}}
                            @endif
                            @if($turret->cry > 0)
                                Kristalle: {{number_format($turret->cry,0, ',', '.')}}
                            @endif
                            @if($turret->h2o > 0)
                                Wasser: {{number_format($turret->h2o,0, ',', '.')}}
                            @endif
                            @if($turret->h2 > 0)
                                Wasserstoff: {{number_format($turret->h2,0, ',', '.')}}
                            @endif
                        </span>
                        <span>Dauer: {{$turret->current_buildtime_readable}}</span>
                    </div>
                    @if(!$currentTurrets && $planetaryResources->fe >= $turret->fe && $planetaryResources->lut >= $turret->lut && $planetaryResources->cry >= $turret->cry && $planetaryResources->h2o >= $turret->h2o && $planetaryResources->h2 >= $turret->h2)
                        <div class="col-4 process-action">
                            <form style="width: 100%;" action="/defense/{{$activePlanet}}/build" method="post">
                                @csrf
                                <div class="form-group">
                                    <label for="{{$turret->turret_name}}">{{$turret->turret_name}} <br/>(max. {{number_format($turret->max_amount,0, ',', '.')}})</label>
                                    <input class="form-control" id="{{$turret->turret_name}}" type="number" min="1" max="{{$turret->max_amount}}" step="1" name="turret[{{$turret->turret_name}}]"/>
                                </div>
                                <input type="submit" class="btn btn-block btn-primary" value="Bauen"/>
                            </form>
                        </div>
                    @else
                        <div class="col-4 process-action">
                            --
                        </div>
                    @endif
                @endif
            @endforeach
        @else
            <div class="col-12">
                Keine Verteidigung Verfügbar
            </div>
        @endif
    </div>
</div>
