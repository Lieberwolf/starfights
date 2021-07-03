<div class="container-fluid">
    <div class="row">
        @if($isBuilding)
        <div class="col-12 title-line">
            <span>Datenbankeinträge für Gebäude</span>
        </div>
        <div class="col-12">
            <div class="accordion" id="buildingAccordionParent">
                <div class="row">
                    @foreach($data as $building)
                    <div class="col-12 sub-line text-left">
                        <div id="buildingHeading{{$building->id}}" class="mb-0" data-toggle="collapse" data-target="#building{{$building->id}}" aria-expanded="true" aria-controls="building{{$building->id}}">
                            <span>{{$building->building_name}}</span>
                        </div>
                        <div id="building{{$building->id}}" class="collapse" aria-labelledby="buildingHeading{{$building->id}}" data-parent="#buildingAccordionParent">
                            <div class="container mb-1" style="border: 1px solid black;">
                                <div class="row">
                                    <div class="col-12 title-line">
                                        <span>Eintrag "{{$building->building_name}}"</span>
                                    </div>
                                    <div class="col-12">
                                        <p class="mb-0">{{array_key_exists(1, explode(' --- ', $building->description)) != null ? explode(' --- ', $building->description)[1] : ''}}</p>
                                    </div>
                                    @if($building->decrease_building_timeBy > 0)
                                    <div class="col-6">Reduziert Gebäudebauzeiten je Stufe um:</div>
                                    <div class="col-6">{{$building->decrease_building_timeBy}}%</div>
                                    @endif
                                    @if($building->decrease_research_timeBy > 0)
                                    <div class="col-6">Reduziert Forschungszeiten je Stufe um:</div>
                                    <div class="col-6">{{$building->decrease_research_timeBy}}%</div>
                                    @endif
                                    @if($building->decrease_ships_timeBy > 0)
                                    <div class="col-6">Reduziert Schiffsbauzeiten je Stufe um:</div>
                                    <div class="col-6">{{$building->decrease_ships_timeBy}}%</div>
                                    @endif
                                    @if($building->decrease_defense_timeBy > 0)
                                    <div class="col-6">Reduziert Verteidigungsbauzeiten je Stufe um:</div>
                                    <div class="col-6">{{$building->decrease_defense_timeBy}}%</div>
                                    @endif
                                    <div class="col-6">Bauzeit steigt je Stufe:</div>
                                    <div class="col-6">{{$building->dynamic_buildtime ? 'ja' : 'nein'}}</div>
                                    <div class="col-6">Punkte:</div>
                                    <div class="col-6">{{$building->points}}</div>

                                    @if($building->allows_research || $building->allows_ships || $building->allows_defense || $building->allows_radar)
                                        <div class="col-6">Ermöglicht:</div>
                                        <div class="col-6">
                                            <ul class="m-0 p-0">
                                                {!!$building->allows_research ? '<li>Forschung</li>' : ''!!}
                                                {!!$building->allows_ships ? '<li>Schiffsbau</li>' : ''!!}
                                                {!!$building->allows_defense ? '<li>Verteidigung</li>' : ''!!}
                                                {!!$building->allows_radar ? '<li>Radarüberwachung</li>' : ''!!}
                                            </ul>
                                        </div>
                                    @endif

                                    @if($building->prod_fe)
                                        <div class="col-6">Basisproduktion Eisen (Stufe 1):</div>
                                        <div class="col-6">{{$building->prod_fe}}</div>
                                    @endif
                                    @if($building->prod_lut)
                                    <div class="col-6">Basisproduktion Lutinum (Stufe 1):</div>
                                    <div class="col-6">{{$building->prod_lut}}</div>
                                    @endif
                                    @if($building->prod_cry)
                                    <div class="col-6">Basisproduktion Kristalle (Stufe 1):</div>
                                    <div class="col-6">{{$building->prod_cry}}</div>
                                    @endif
                                    @if($building->prod_h2o)
                                    <div class="col-6">Basisproduktion Wasser (Stufe 1):</div>
                                    <div class="col-6">{{$building->prod_h2o}}</div>
                                    @endif
                                    @if($building->prod_h2)
                                    <div class="col-6">Basisproduktion Wasserstoff (Stufe 1):</div>
                                    <div class="col-6">{{$building->prod_h2}}</div>
                                    @endif

                                    @if($building->cost_fe)
                                    <div class="col-6">Basiskosten Eisen (Stufe 1):</div>
                                    <div class="col-6">{{$building->cost_fe}}</div>
                                    @endif
                                    @if($building->cost_lut)
                                    <div class="col-6">Basiskosten Lutinum (Stufe 1):</div>
                                    <div class="col-6">{{$building->cost_lut}}</div>
                                    @endif
                                    @if($building->cost_cry)
                                    <div class="col-6">Basiskosten Kristalle (Stufe 1):</div>
                                    <div class="col-6">{{$building->cost_cry}}</div>
                                    @endif
                                    @if($building->cost_h2o)
                                    <div class="col-6">Basiskosten Wasser (Stufe 1):</div>
                                    <div class="col-6">{{$building->cost_h2o}}</div>
                                    @endif
                                    @if($building->cost_h2)
                                    <div class="col-6">Basiskosten Wasserstoff (Stufe 1):</div>
                                    <div class="col-6">{{$building->cost_h2}}</div>
                                    @endif

                                    @if($building->store_fe)
                                    <div class="col-6">Erhöht Lagerkapazität für Eisen:</div>
                                    <div class="col-6">{{number_format($building->store_fe,0, ',', '.')}}</div>
                                    @endif
                                    @if($building->store_lut)
                                    <div class="col-6">Erhöht Lagerkapazität für Lutinum:</div>
                                    <div class="col-6">{{number_format($building->store_lut,0, ',', '.')}}</div>
                                    @endif
                                    @if($building->store_cry)
                                    <div class="col-6">Erhöht Lagerkapazität für Kristalle:</div>
                                    <div class="col-6">{{number_format($building->store_cry,0, ',', '.')}}</div>
                                    @endif
                                    @if($building->store_h2o)
                                    <div class="col-6">Erhöht Lagerkapazität für Wasser:</div>
                                    <div class="col-6">{{number_format($building->store_h2o,0, ',', '.')}}</div>
                                    @endif
                                    @if($building->store_h2)
                                    <div class="col-6">Erhöht Lagerkapazität für Wasserstoff:</div>
                                    <div class="col-6">{{number_format($building->store_h2,0, ',', '.')}}</div>
                                    @endif

                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        @endif
        @if($isResearch)
        <div class="col-12 title-line">
            <span>Datenbankeinträge für Forschungen</span>
        </div>
        <div class="col-12">
            <div class="accordion" id="researchAccordionParent">
                <div class="row">
                    @foreach($data as $research)
                    <div class="col-12 sub-line text-left">
                        <div id="researchHeading{{$research->id}}" class="mb-0" data-toggle="collapse" data-target="#research{{$research->id}}" aria-expanded="true" aria-controls="research{{$research->id}}">
                            <span>{{$research->research_name}}</span>
                        </div>
                        <div id="research{{$research->id}}" class="collapse" aria-labelledby="researchHeading{{$research->id}}" data-parent="#researchAccordionParent">
                            <div class="container mb-1" style="border: 1px solid black;">
                                <div class="row">
                                    <div class="col-12 title-line">
                                        <span>Eintrag "{{$research->research_name}}"</span>
                                    </div>
                                    <div class="col-12">
                                        <p class="mb-0">{{array_key_exists(1, explode(' --- ', $research->description)) != null ? explode(' --- ', $research->description)[1] : ''}}</p>
                                    </div>
                                    <div class="col-6">Punkte:</div>
                                    <div class="col-6">{{$research->points}}</div>
                                    @if($research->increase_spy)
                                    <div class="col-6">Steigert die Erfolgswahrscheinlichkeit für Spionagen um:</div>
                                    <div class="col-6">{{$research->increase_spy}}%</div>
                                    @endif
                                    @if($research->increase_counter_spy)
                                    <div class="col-6">Steigert die Erfolgswahrscheinlichkeit feindliche Spionage zu verhindern um:</div>
                                    <div class="col-6">{{$research->increase_counter_spy}}%</div>
                                    @endif
                                    @if($research->increase_ship_attack)
                                    <div class="col-6">Steigert den Angriff von Schiffen um:</div>
                                    <div class="col-6">{{$research->increase_ship_attack}}%</div>
                                    @endif
                                    @if($research->increase_ship_defense)
                                    <div class="col-6">Steigert die Verteidigung von Schiffen um:</div>
                                    <div class="col-6">{{$research->increase_ship_defense}}%</div>
                                    @endif
                                    @if($research->increase_shield_defense)
                                    <div class="col-6">Steigert die Schilde von Schiffen und Schildgenerator um:</div>
                                    <div class="col-6">{{$research->increase_shield_defense}}%</div>
                                    @endif
                                    @if($research->increase_rocket_drive)
                                    <div class="col-6">Steigert die Geschwindigkeit von Raketenantrieben um:</div>
                                    <div class="col-6">{{$research->increase_rocket_drive}}%</div>
                                    @endif
                                    @if($research->increase_turbine_drive)
                                    <div class="col-6">Steigert die Geschwindigkeit von Turbinenantrieben um:</div>
                                    <div class="col-6">{{$research->increase_turbine_drive}}%</div>
                                    @endif
                                    @if($research->increase_warp_drive)
                                    <div class="col-6">Steigert die Geschwindigkeit von Warpantrieben um:</div>
                                    <div class="col-6">{{$research->increase_warp_drive}}%</div>
                                    @endif
                                    @if($research->increase_transwarp_drive)
                                    <div class="col-6">Steigert die Geschwindigkeit von Transwarpantrieben um:</div>
                                    <div class="col-6">{{$research->increase_transwarp_drive}}%</div>
                                    @endif
                                    @if($research->increase_ion_drive)
                                    <div class="col-6">Steigert die Geschwindigkeit von Ionenantrieben um:</div>
                                    <div class="col-6">{{$research->increase_ion_drive}}%</div>
                                    @endif
                                    @if($research->increase_max_planets)
                                    <div class="col-6">Steigert die maximale Anzahl der Planeten um:</div>
                                    <div class="col-6">{{$research->increase_max_planets}}</div>
                                    @endif
                                    @if($research->increase_cargo)
                                    <div class="col-6">Steigert die maximale Ladekapazität von Schiffen um:</div>
                                    <div class="col-6">{{$research->increase_cargo}}%</div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        @endif
        @if($isShips)
        <div class="col-12 title-line">
            <span>Datenbankeinträge für Schiffe</span>
        </div>
        <div class="col-12">
            <div class="accordion" id="shipAccordionParent">
                <div class="row">
                    @foreach($data as $ship)
                    <div class="col-12 sub-line text-left">
                        <div id="shipHeading{{$ship->id}}" class="mb-0" data-toggle="collapse" data-target="#ship{{$ship->id}}" aria-expanded="true" aria-controls="ship{{$ship->id}}">
                            <span>{{$ship->ship_name}}</span>
                        </div>
                        <div id="ship{{$ship->id}}" class="collapse" aria-labelledby="shipHeading{{$ship->id}}" data-parent="#shipAccordionParent">
                            <div class="container mb-1" style="border: 1px solid black;">
                                <div class="row">
                                    <div class="col-12 title-line">
                                        <span>Eintrag "{{$ship->ship_name}}"</span>
                                    </div>
                                    <div class="col-12">
                                        <p class="mb-0">{{array_key_exists(1, explode(' --- ', $ship->description)) != null ? explode(' --- ', $ship->description)[1] : ''}}</p>
                                    </div>
                                    <div class="col-6">Angriffswert (Basiswert):</div>
                                    <div class="col-6">{{number_format($ship->attack,0, ',', '.')}}</div>
                                    <div class="col-6">Verteidigungswert (Basiswert):</div>
                                    <div class="col-6">{{number_format($ship->defend,0, ',', '.')}}</div>
                                    <div class="col-6">Ladakapazität (Basiswert):</div>
                                    <div class="col-6">{{number_format($ship->cargo,0, ',', '.')}}</div>
                                    <div class="col-6">Kosten:</div>
                                    <div class="col-6">
                                        <ul class="m-0 p-0">
                                            @if($ship->fe > 0)
                                            <li>{{number_format($ship->fe,0, ',', '.')}} Eisen</li>
                                            @endif
                                            @if($ship->lut > 0)
                                            <li>{{number_format($ship->lut,0, ',', '.')}} Lutinum</li>
                                            @endif
                                            @if($ship->cry > 0)
                                            <li>{{number_format($ship->cry,0, ',', '.')}} Kristalle</li>
                                            @endif
                                            @if($ship->h2o > 0)
                                            <li>{{number_format($ship->h2o,0, ',', '.')}} Wasser</li>
                                            @endif
                                            @if($ship->h2 > 0)
                                            <li>{{number_format($ship->h2,0, ',', '.')}} Wasserstoff</li>
                                            @endif
                                        </ul>
                                    </div>
                                    <div class="col-6">Verbrauch:</div>
                                    <div class="col-6">{{number_format($ship->consumption,0, ',', '.')}}</div>
                                    <div class="col-6">Geschwindigkeit:</div>
                                    <div class="col-6">{{number_format($ship->speed,0, ',', '.')}} RM/h</div>
                                    <div class="col-6">Spionagetauglich:</div>
                                    <div class="col-6">{{$ship->spy ? 'Ja' : 'Nein'}}</div>
                                    <div class="col-6">Tarnfähig:</div>
                                    <div class="col-6">{{$ship->stealth ? 'Ja' : 'Nein'}}</div>
                                    <div class="col-6">Kann Planeten erobern:</div>
                                    <div class="col-6">{{$ship->invasion ? 'Ja' : 'Nein'}}</div>
                                    <div class="col-6">Delta Scan möglich:</div>
                                    <div class="col-6">{{$ship->delta_scan ? 'Ja' : 'Nein'}}</div>
                                    <div class="col-6">Kann Planeten kolonisieren:</div>
                                    <div class="col-6">{{$ship->colonization ? 'Ja' : 'Nein'}}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        @endif
        @if($isDefense)
        <div class="col-12 title-line">
            <span>Datenbankeinträge für Verteidigung</span>
        </div>
        <div class="col-12">
            <div class="accordion" id="turretAccordionParent">
                <div class="row">
                    @foreach($data as $turret)
                    <div class="col-12 sub-line text-left">
                        <div id="turretHeading{{$turret->id}}" class="mb-0" data-toggle="collapse" data-target="#turret{{$turret->id}}" aria-expanded="true" aria-controls="turret{{$turret->id}}">
                            <span>{{$turret->turret_name}}</span>
                        </div>
                        <div id="turret{{$turret->id}}" class="collapse" aria-labelledby="turretHeading{{$turret->id}}" data-parent="#turretAccordionParent">
                            <div class="container mb-1" style="border: 1px solid black;">
                                <div class="row">
                                    <div class="col-12 title-line">
                                        <span>Eintrag "{{$turret->turret_name}}"</span>
                                    </div>
                                    <div class="col-12">
                                        <p class="mb-0">{{array_key_exists(1, explode(' --- ', $turret->description)) != null ? explode(' --- ', $turret->description)[1] : ''}}</p>
                                    </div>
                                    <div class="col-6">Angriffswert (Basiswert):</div>
                                    <div class="col-6">{{number_format($turret->attack,0, ',', '.')}}</div>
                                    <div class="col-6">Verteidigungswert (Basiswert):</div>
                                    <div class="col-6">{{number_format($turret->defend,0, ',', '.')}}</div>
                                    <div class="col-6">Kosten:</div>
                                    <div class="col-6">
                                        <ul class="m-0 p-0">
                                            @if($turret->fe > 0)
                                                <li>{{number_format($turret->fe,0, ',', '.')}} Eisen</li>
                                            @endif
                                            @if($turret->lut > 0)
                                            <li>{{number_format($turret->lut,0, ',', '.')}} Lutinum</li>
                                            @endif
                                            @if($turret->cry > 0)
                                            <li>{{number_format($turret->cry,0, ',', '.')}} Kristalle</li>
                                            @endif
                                            @if($turret->h2o > 0)
                                            <li>{{number_format($turret->h2o,0, ',', '.')}} Wasser</li>
                                            @endif
                                            @if($turret->h2 > 0)
                                            <li>{{number_format($turret->h2,0, ',', '.')}} Wasserstoff</li>
                                            @endif
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        @endif
    </div>
</div>
