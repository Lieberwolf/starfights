@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12 col-md-3">
            <h4>Building Data</h4>
            <form action="/buildingdashboard/{{$building->id}}/edit" method="post">
                @csrf
                <div class="form-group">
                    <label>Building Name</label>
                    <input class="form-control" value="{{$building->building_name}}" type="text" name="building_name" placeholder="building name"/>
                    @error('building_name')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
                <div class="form-group">
                    <label>building description</label>
                    <input class="form-control" value="{{$building->description}}" type="text" name="description" placeholder="building description"/>
                    @error('description')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
                <div class="form-group">
                    <label>FE cost</label>
                    <input class="form-control" value="{{$building->fe}}" type="number" name="fe" placeholder="FE cost"/>
                    @error('fe')
                    <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
                    @enderror
                </div>
                <div class="form-group">
                    <label>Lut cost</label>
                    <input class="form-control" value="{{$building->lut}}" type="number" name="lut" placeholder="Lut cost"/>
                    @error('lut')
                    <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
                    @enderror
                </div>
                <div class="form-group">
                    <label>Cry cost</label>
                    <input class="form-control" value="{{$building->cry}}" type="number" name="cry" placeholder="Cry cost"/>
                    @error('cry')
                    <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
                    @enderror
                </div>
                <div class="form-group">
                    <label>H2o cost</label>
                    <input class="form-control" value="{{$building->h2o}}" type="number" name="h2o" placeholder="H2o cost"/>
                    @error('h2o')
                    <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
                    @enderror
                </div>
                <div class="form-group">
                    <label>h2 cost</label>
                    <input class="form-control" value="{{$building->h2}}" type="number" name="h2" placeholder="h2 cost"/>
                    @error('h2')
                    <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
                    @enderror
                </div>
                <div class="form-group">
                    <label>FE prod</label>
                    <input class="form-control" value="{{$building->prod_fe}}" type="number" name="prod_fe" placeholder="FE prod"/>
                    @error('prod_fe')
                    <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
                    @enderror
                </div>
                <div class="form-group">
                    <label>Lut prod</label>
                    <input class="form-control" value="{{$building->prod_lut}}" type="number" name="prod_lut" placeholder="Lut prod"/>
                    @error('prod_lut')
                    <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
                    @enderror
                </div>
                <div class="form-group">
                    <label>Cry prod</label>
                    <input class="form-control" value="{{$building->prod_cry}}" type="number" name="prod_cry" placeholder="Cry prod"/>
                    @error('prod_cry')
                    <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
                    @enderror
                </div>
                <div class="form-group">
                    <label>H2o prod</label>
                    <input class="form-control" value="{{$building->prod_h2o}}" type="number" name="prod_h2o" placeholder="H2o prod"/>
                    @error('prod_h2o')
                    <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
                    @enderror
                </div>
                <div class="form-group">
                    <label>h2 prod</label>
                    <input class="form-control" value="{{$building->prod_h2}}" type="number" name="prod_h2" placeholder="h2 prod"/>
                    @error('prod_h2')
                    <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
                    @enderror
                </div>
                <div class="form-group">
                    <label>FE cost</label>
                    <input class="form-control" value="{{$building->cost_fe}}" type="number" name="cost_fe" placeholder="FE cost"/>
                    @error('cost_fe')
                    <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
                    @enderror
                </div>
                <div class="form-group">
                    <label>Lut cost</label>
                    <input class="form-control" value="{{$building->cost_lut}}" type="number" name="cost_lut" placeholder="Lut cost"/>
                    @error('cost_lut')
                    <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
                    @enderror
                </div>
                <div class="form-group">
                    <label>Cry cost</label>
                    <input class="form-control" value="{{$building->cost_cry}}" type="number" name="cost_cry" placeholder="Cry cost"/>
                    @error('cost_cry')
                    <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
                    @enderror
                </div>
                <div class="form-group">
                    <label>H2o cost</label>
                    <input class="form-control" value="{{$building->cost_h2o}}" type="number" name="cost_h2o" placeholder="H2o cost"/>
                    @error('cost_h2o')
                    <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
                    @enderror
                </div>
                <div class="form-group">
                    <label>h2 cost</label>
                    <input class="form-control" value="{{$building->cost_h2}}" type="number" name="cost_h2" placeholder="h2 cost"/>
                    @error('cost_h2')
                    <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
                    @enderror
                </div>
                <div class="form-group">
                    <label>FE store</label>
                    <input class="form-control" value="{{$building->store_fe}}" type="number" name="store_fe" placeholder="FE store"/>
                    @error('store_fe')
                    <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
                    @enderror
                </div>
                <div class="form-group">
                    <label>Lut store</label>
                    <input class="form-control" value="{{$building->store_lut}}" type="number" name="store_lut" placeholder="Lut store"/>
                    @error('store_lut')
                    <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
                    @enderror
                </div>
                <div class="form-group">
                    <label>Cry store</label>
                    <input class="form-control" value="{{$building->store_cry}}" type="number" name="store_cry" placeholder="Cry store"/>
                    @error('store_cry')
                    <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
                    @enderror
                </div>
                <div class="form-group">
                    <label>H2o store</label>
                    <input class="form-control" value="{{$building->store_h2o}}" type="number" name="store_h2o" placeholder="H2o store"/>
                    @error('store_h2o')
                    <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
                    @enderror
                </div>
                <div class="form-group">
                    <label>h2 store</label>
                    <input class="form-control" value="{{$building->store_h2}}" type="number" name="store_h2" placeholder="h2 store"/>
                    @error('store_h2')
                    <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
                    @enderror
                </div>
                <div class="form-group">
                    <label>decrease_research_timeBy</label>
                    <input class="form-control" value="{{$building->decrease_research_timeBy}}" type="number" name="decrease_research_timeBy" placeholder="decrease_research_timeBy"/>
                    @error('decrease_research_timeBy')
                    <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
                    @enderror
                </div>
                <div class="form-group">
                    <label>decrease_ships_timeBy</label>
                    <input class="form-control" value="{{$building->decrease_ships_timeBy}}" type="number" name="decrease_ships_timeBy" placeholder="decrease_ships_timeBy"/>
                    @error('decrease_ships_timeBy')
                    <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
                    @enderror
                </div>
                <div class="form-group">
                    <label>decrease_defense_timeBy</label>
                    <input class="form-control" value="{{$building->decrease_defense_timeBy}}" type="number" name="decrease_defense_timeBy" placeholder="decrease_defense_timeBy"/>
                    @error('decrease_defense_timeBy')
                    <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
                    @enderror
                </div>
                <div class="form-group">
                    <label>decrease_building_timeBy</label>
                    <input class="form-control" value="{{$building->decrease_building_timeBy}}" type="number" name="decrease_building_timeBy" placeholder="decrease_building_timeBy"/>
                    @error('decrease_building_timeBy')
                    <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
                    @enderror
                </div>
                <div class="form-group">
                    <label>dynamic_buildtime</label>
                    <input class="form-control" value="{{$building->dynamic_buildtime}}" type="number" name="dynamic_buildtime" placeholder="dynamic_buildtime"/>
                    @error('dynamic_buildtime')
                    <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
                    @enderror
                </div>
                <div class="form-group">
                    <label>initial_buildtime</label>
                    <input id="initial_buildtime" class="form-control" value="{{$building->initial_buildtime}}" type="number" name="initial_buildtime" placeholder="initial_buildtime"/>
                    @error('initial_buildtime')
                    <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
                    @enderror
                </div>
                <div class="form-check">
                    <input class="form-check-input" id="allows_research" {{$building->allows_research == 'on' ? 'checked="checked"' : ''}} type="checkbox" name="allows_research"/>
                    <label class="form-check-label" for="allows_research">allows_research</label>
                    @error('allows_research')
                    <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
                    @enderror
                </div>
                <div class="form-check">
                    <input class="form-check-input" id="allows_ships" {{$building->allows_ships == 'on' ? 'checked="checked"' : ''}} type="checkbox" name="allows_ships"/>
                    <label class="form-check-label" for="allows_ships">allows_ships</label>
                    @error('allows_ships')
                    <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
                    @enderror
                </div>
                <div class="form-check">
                    <input class="form-check-input" id="allows_defense" {{$building->allows_defense == 'on' ? 'checked="checked"' : ''}} type="checkbox" name="allows_defense"/>
                    <label class="form-check-label" for="allows_defense">allows_defense</label>
                    @error('allows_defense')
                    <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
                    @enderror
                </div>
                <div class="form-group">
                    <label>points</label>
                    <input class="form-control" value="{{$building->points}}" type="number" name="points" placeholder="points"/>
                    @error('points')
                    <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
                    @enderror
                </div>

                <input class="btn btn-primary" type="submit" value="Submit"/>
            </form>
        </div>
        <div class="col-12 col-md-3">
            <h4>Factors</h4>
            <form action="/buildingdashboard/{{$building->id}}/edit/factors" method="post">
                @csrf
                <div class="form-group">
                    <label for="factor_1">Buildtime Factor 1</label>
                    <input type="number" value="{{$building->factor_1 != null ? $building->factor_1 : 0}}" min="0.0000" name="factor_1" id="factor_1" placeholder="Buildtime Factor 1" class="form-control" step=".0001"/>
                    @error('factor_1')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="factor_2">Buildtime Factor 2</label>
                    <input type="number" value="{{$building->factor_2 != null ? $building->factor_2 : 0}}" min="0.0000" name="factor_2" id="factor_2" placeholder="Buildtime Factor 2" class="form-control" step=".0001"/>
                    @error('factor_2')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="factor_3">Buildtime Factor 3</label>
                    <input type="number" value="{{$building->factor_3 != null ? $building->factor_3 : 0}}" min="0.0000" name="factor_3" id="factor_3" placeholder="Buildtime Factor 3" class="form-control" step=".0001"/>
                    @error('factor_3')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="fe_factor_1">fe_factor 1</label>
                    <input type="number" value="{{$building->fe_factor_1 != null ? $building->fe_factor_1 : 0}}" min="0.0000" name="fe_factor_1" id="fe_factor_1" placeholder="fe_factor 1" class="form-control" step=".0001"/>
                    @error('fe_factor_1')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="fe_factor_2">fe_factor 2</label>
                    <input type="number" value="{{$building->fe_factor_2 != null ? $building->fe_factor_2 : 0}}" min="0.0000" name="fe_factor_2" id="fe_factor_2" placeholder="fe_factor 2" class="form-control" step=".0001"/>
                    @error('fe_factor_2')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="fe_factor_3">fe_factor 3</label>
                    <input type="number" value="{{$building->fe_factor_3 != null ? $building->fe_factor_3 : 0}}" min="0.0000" name="fe_factor_3" id="fe_factor_3" placeholder="fe_factor 3" class="form-control" step=".0001"/>
                    @error('fe_factor_3')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="lut_factor_1">lut_factor 1</label>
                    <input type="number" value="{{$building->lut_factor_1 != null ? $building->lut_factor_1 : 0}}" min="0.0000" name="lut_factor_1" id="lut_factor_1" placeholder="lut_factor 1" class="form-control" step=".0001"/>
                    @error('lut_factor_1')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="lut_factor_2">lut_factor 2</label>
                    <input type="number" value="{{$building->lut_factor_2 != null ? $building->lut_factor_2 : 0}}" min="0.0000" name="lut_factor_2" id="lut_factor_2" placeholder="lut_factor 2" class="form-control" step=".0001"/>
                    @error('lut_factor_2')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="lut_factor_3">lut_factor 3</label>
                    <input type="number" value="{{$building->lut_factor_3 != null ? $building->lut_factor_3 : 0}}" min="0.0000" name="lut_factor_3" id="lut_factor_3" placeholder="lut_factor 3" class="form-control" step=".0001"/>
                    @error('lut_factor_3')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="cry_factor_1">cry_factor 1</label>
                    <input type="number" value="{{$building->cry_factor_1 != null ? $building->cry_factor_1 : 0}}" min="0.0000" name="cry_factor_1" id="cry_factor_1" placeholder="cry_factor 1" class="form-control" step=".0001"/>
                    @error('cry_factor_1')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="cry_factor_2">cry_factor 2</label>
                    <input type="number" value="{{$building->cry_factor_2 != null ? $building->cry_factor_2 : 0}}" min="0.0000" name="cry_factor_2" id="cry_factor_2" placeholder="cry_factor 2" class="form-control" step=".0001"/>
                    @error('cry_factor_2')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="cry_factor_3">cry_factor 3</label>
                    <input type="number" value="{{$building->cry_factor_3 != null ? $building->cry_factor_3 : 0}}" min="0.0000" name="cry_factor_3" id="cry_factor_3" placeholder="cry_factor 3" class="form-control" step=".0001"/>
                    @error('cry_factor_3')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="h2o_factor_1">h2o_factor 1</label>
                    <input type="number" value="{{$building->h2o_factor_1 != null ? $building->h2o_factor_1 : 0}}" min="0.0000" name="h2o_factor_1" id="h2o_factor_1" placeholder="h2o_factor 1" class="form-control" step=".0001"/>
                    @error('h2o_factor_1')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="h2o_factor_2">h2o_factor 2</label>
                    <input type="number" value="{{$building->h2o_factor_2 != null ? $building->h2o_factor_2 : 0}}" min="0.0000" name="h2o_factor_2" id="h2o_factor_2" placeholder="h2o_factor 2" class="form-control" step=".0001"/>
                    @error('h2o_factor_2')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="h2o_factor_3">h2o_factor 3</label>
                    <input type="number" value="{{$building->h2o_factor_3 != null ? $building->h2o_factor_3 : 0}}" min="0.0000" name="h2o_factor_3" id="h2o_factor_3" placeholder="h2o_factor 3" class="form-control" step=".0001"/>
                    @error('h2o_factor_3')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="h2_factor_1">h2_factor 1</label>
                    <input type="number" value="{{$building->h2_factor_1 != null ? $building->h2_factor_1 : 0}}" min="0.0000" name="h2_factor_1" id="h2_factor_1" placeholder="h2_factor 1" class="form-control" step=".0001"/>
                    @error('h2_factor_1')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="h2_factor_2">h2_factor 2</label>
                    <input type="number" value="{{$building->h2_factor_2 != null ? $building->h2_factor_2 : 0}}" min="0.0000" name="h2_factor_2" id="h2_factor_2" placeholder="h2_factor 2" class="form-control" step=".0001"/>
                    @error('h2_factor_2')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="h2_factor_3">h2_factor 3</label>
                    <input type="number" value="{{$building->h2_factor_3 != null ? $building->h2_factor_3 : 0}}" min="0.0000" name="h2_factor_3" id="h2_factor_3" placeholder="h2_factor 3" class="form-control" step=".0001"/>
                    @error('h2_factor_3')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
                <input type="submit" class="btn btn-primary" value="save"/>
            </form>
        </div>
        <div class="col-12 col-md-6">
            <h4>Level Data</h4>
            <table>
                <thead>
                <th>Level</th>
                <th>Seconds</th>
                <th>Time</th>
                <th>Eisen</th>
                <th>Lutinum</th>
                <th>Kristalle</th>
                <th>Wasser</th>
                <th>Wasserstoff</th>
                </thead>
                <tbody id="levelMap"></tbody>
            </table>
            <script>
                function component(x, v) {
                    return Math.floor(x / v);
                }

                var Grundzeit = parseInt(document.getElementById('initial_buildtime').value),
                    GrundFe = parseInt(document.getElementsByName('fe')[0].value),
                    GrundLut = parseInt(document.getElementsByName('lut')[0].value),
                    GrundCry = parseInt(document.getElementsByName('cry')[0].value),
                    GrundH2o = parseInt(document.getElementsByName('h2o')[0].value),
                    GrundH2 = parseInt(document.getElementsByName('h2')[0].value),
                    Modifikator1 = parseFloat(document.getElementById('factor_1').value),
                    Modifikator2 = parseFloat(document.getElementById('factor_2').value),
                    Modifikator3 = parseFloat(document.getElementById('factor_3').value),
                    FeModifikator1 = parseFloat(document.getElementById('fe_factor_1').value),
                    FeModifikator2 = parseFloat(document.getElementById('fe_factor_2').value),
                    FeModifikator3 = parseFloat(document.getElementById('fe_factor_3').value),
                    LutModifikator1 = parseFloat(document.getElementById('lut_factor_1').value),
                    LutModifikator2 = parseFloat(document.getElementById('lut_factor_2').value),
                    LutModifikator3 = parseFloat(document.getElementById('lut_factor_3').value),
                    CryModifikator1 = parseFloat(document.getElementById('cry_factor_1').value),
                    CryModifikator2 = parseFloat(document.getElementById('cry_factor_2').value),
                    CryModifikator3 = parseFloat(document.getElementById('cry_factor_3').value),
                    H2oModifikator1 = parseFloat(document.getElementById('h2o_factor_1').value),
                    H2oModifikator2 = parseFloat(document.getElementById('h2o_factor_2').value),
                    H2oModifikator3 = parseFloat(document.getElementById('h2o_factor_3').value),
                    H2Modifikator1 = parseFloat(document.getElementById('h2_factor_1').value),
                    H2Modifikator2 = parseFloat(document.getElementById('h2_factor_2').value),
                    H2Modifikator3 = parseFloat(document.getElementById('h2_factor_3').value);

                for(var i = 1; i <= 100; i++) {
                    var mod1 = (i/Modifikator1) + Modifikator2,
                        mod2 = i * Modifikator3,
                        femod1 = (i/FeModifikator1) + FeModifikator2,
                        femod2 = i * FeModifikator3,
                        lutmod1 = (i/LutModifikator1) + LutModifikator2,
                        lutmod2 = i * LutModifikator3,
                        crymod1 = (i/CryModifikator1) + CryModifikator2,
                        crymod2 = i * CryModifikator3,
                        h2omod1 = (i/H2oModifikator1) + H2oModifikator2,
                        h2omod2 = i * H2oModifikator3,
                        h2mod1 = (i/H2Modifikator1) + H2Modifikator2,
                        h2mod2 = i * H2Modifikator3;

                    var timestamp = Grundzeit * mod1 * mod2,
                        days    = component(timestamp, 24 * 60 * 60),
                        hours   = component(timestamp,      60 * 60) % 24,
                        minutes = component(timestamp,           60) % 60,
                        seconds = component(timestamp,            1) % 60,
                        suffix  = ':',
                        newFE = GrundFe * femod1 * femod2,
                        newLut = GrundLut * lutmod1 * lutmod2,
                        newCry = GrundCry * crymod1 * crymod2,
                        newH2o = GrundH2o * h2omod1 * h2omod2,
                        newH2 = GrundH2 * h2mod1 * h2mod2;

                    if(days > 0)
                    {
                        days =  days < 10 ? '0' + days +" d, " : days +" d, ";
                    } else {
                        days = '';
                    }

                    hours = hours < 10 ? '0' + hours + suffix : hours + suffix;
                    minutes = minutes < 10 ? '0' + minutes + suffix : minutes + suffix;
                    seconds = seconds < 10 ? '0' + seconds : seconds;

                    document.getElementById('levelMap').innerHTML += '<tr>' +
                        '<td>'+i+'</td>' +
                        '<td>'+component(timestamp,1)+'</td>' +
                        '<td>' + days + hours + minutes + seconds + '</td>' +
                        '<td>' + Math.floor(newFE) + '</td>' +
                        '<td>' + Math.floor(newLut) + '</td>' +
                        '<td>' + Math.floor(newCry) + '</td>' +
                        '<td>' + Math.floor(newH2o) + '</td>' +
                        '<td>' + Math.floor(newH2) + '</td>' +
                        '</tr>';
                }

            </script>
        </div>
    </div>
</div>
@endsection
