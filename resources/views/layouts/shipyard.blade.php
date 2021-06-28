<div class="container-fluid">
    <div class="row">
        <div class="col-12 title-line">
            @if($prevPlanet)
            <span>
                <a href="/shipyard/{{$prevPlanet->id}}"><<</a>
            </span>
            @endif
            <span>Verfügbare Schiffe auf {{$planetInformation->galaxy}}:{{$planetInformation->system}}:{{$planetInformation->planet}}</span>
            @if($nextPlanet)
            <span>
                <a href="/shipyard/{{$nextPlanet->id}}">>></a>
            </span>
            @endif
        </div>
        @if($currentShips)
            @foreach($currentShips->nextShipIn as $next)
                @if($next->planet == $activePlanet)
                    <div class="col-8 current-process process-entry">
                        <span>Aktuell in Bau: {{$currentShips->ship_name}} ({{$currentShips->amount_left}} Stück)</span>
                        <br/>
                        <span>Nächstes Schiff fertig in: </span>
                        <span class="js-add-countdown" data-seconds-to-count="{{$next->seconds}}">{{$next->buildtime != false ? $next->buildtime : ''}}</span>
                        <br/>
                        <span>Fertigstellung: {{ date("d.m.Y H:i:s", strtotime($currentShips->finished_at)) }}</span>
                    </div>
                    <div class="col-4 process-action">
                        <a href="/shipyard/{{$activePlanet}}/edit">Abbrechen</a><br/>
                    </div>
                @endif
            @endforeach
        @endif
        @if(count($shipList) > 0)
            @foreach($shipList as $key => $ship)
                @if($ship->buildable)
                    <div class="col-8 process-entry">
                        <span>{{$ship->ship_name}} <a data-toggle="collapse" href="#collapse{{$ship->id}}" aria-expanded="false" aria-controls="collapse{{$ship->id}}">[Info]</a></span>
                        <div class="collapse" id="collapse{{$ship->id}}">
                            <span>{{$ship->description ?? $ship->ship_name}}</span>
                        </div>
                        <span>Baukosten:</span>
                        <span>
                            @if($ship->fe > 0)
                                Eisen: {{number_format($ship->fe,0, ',', '.')}}
                            @endif
                            @if($ship->lut > 0)
                                Lutinum: {{number_format($ship->lut,0, ',', '.')}}
                            @endif
                            @if($ship->cry > 0)
                                Kristalle: {{number_format($ship->cry,0, ',', '.')}}
                            @endif
                            @if($ship->h2o > 0)
                                Wasser: {{number_format($ship->h2o,0, ',', '.')}}
                            @endif
                            @if($ship->h2 > 0)
                                Wasserstoff: {{number_format($ship->h2,0, ',', '.')}}
                            @endif
                        </span>
                        <span>Dauer: {{$ship->current_buildtime_readable}}</span>
                    </div>
                    @if(!$currentShips && $planetaryResources->fe >= $ship->fe && $planetaryResources->lut >= $ship->lut && $planetaryResources->cry >= $ship->cry && $planetaryResources->h2o >= $ship->h2o && $planetaryResources->h2 >= $ship->h2)
                        <div class="col-4 process-action">
                            <form style="width: 100%;" action="/shipyard/{{$activePlanet}}/build" method="post">
                                @csrf
                                <div class="form-group">
                                    <label for="{{$ship->ship_name}}">{{$ship->ship_name}} <a class="js-add-to-field">(max. <span>{{number_format($ship->max_amount,0, ',', '.')}}</span>)</a></label>
                                    <input class="form-control" id="{{$ship->ship_name}}" type="number" min="1" max="{{$ship->max_amount}}" step="1" name="ship[{{$ship->ship_name}}]"/>
                                </div>
                                <input type="submit" class="btn btn-block btn-secondary" value="Bauen"/>
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
                Keine Schiffe Verfügbar
            </div>
        @endif
    </div>
</div>
