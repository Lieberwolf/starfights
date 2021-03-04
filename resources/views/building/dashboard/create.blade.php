@extends('layouts.app')

@section('content')
<div class="container">
    <form action="/buildingdashboard" method="post">
        @csrf

        <input type="text" name="building_name" placeholder="building name"/>
        @error('building_name')
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
        @enderror
        <br/>

        <input type="text" name="description" placeholder="building description"/>
        @error('description')
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
        @enderror
        <br/>

        <input type="number" name="fe" placeholder="FE cost"/>
        @error('fe')
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
        @enderror
        <br/>

        <input type="number" name="lut" placeholder="Lut cost"/>
        @error('lut')
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
        @enderror
        <br/>

        <input type="number" name="cry" placeholder="Cry cost"/>
        @error('cry')
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
        @enderror
        <br/>

        <input type="number" name="h2o" placeholder="H2o cost"/>
        @error('h2o')
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
        @enderror
        <br/>

        <input type="number" name="h2" placeholder="h2 cost"/>
        @error('h2')
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
        @enderror
        <br/>


        <input type="number" name="prod_fe" placeholder="FE prod"/>
        @error('prod_fe')
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
        @enderror
        <br/>

        <input type="number" name="prod_lut" placeholder="Lut prod"/>
        @error('prod_lut')
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
        @enderror
        <br/>

        <input type="number" name="prod_cry" placeholder="Cry prod"/>
        @error('prod_cry')
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
        @enderror
        <br/>

        <input type="number" name="prod_h2o" placeholder="H2o prod"/>
        @error('prod_h2o')
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
        @enderror
        <br/>

        <input type="number" name="prod_h2" placeholder="h2 prod"/>
        @error('prod_h2')
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
        @enderror
        <br/>


        <input type="number" name="cost_fe" placeholder="FE cost"/>
        @error('cost_fe')
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
        @enderror
        <br/>

        <input type="number" name="cost_lut" placeholder="Lut cost"/>
        @error('cost_lut')
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
        @enderror
        <br/>

        <input type="number" name="cost_cry" placeholder="Cry cost"/>
        @error('cost_cry')
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
        @enderror
        <br/>

        <input type="number" name="cost_h2o" placeholder="H2o cost"/>
        @error('cost_h2o')
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
        @enderror
        <br/>

        <input type="number" name="cost_h2" placeholder="h2 cost"/>
        @error('cost_h2')
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
        @enderror
        <br/>

        <input type="number" name="store_fe" placeholder="FE store"/>
        @error('store_fe')
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
        @enderror
        <br/>

        <input type="number" name="store_lut" placeholder="Lut store"/>
        @error('store_lut')
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
        @enderror
        <br/>

        <input type="number" name="store_cry" placeholder="Cry store"/>
        @error('store_cry')
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
        @enderror
        <br/>

        <input type="number" name="store_h2o" placeholder="H2o store"/>
        @error('store_h2o')
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
        @enderror
        <br/>

        <input type="number" name="store_h2" placeholder="h2 store"/>
        @error('store_h2')
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
        @enderror
        <br/>

        <input type="number" name="decrease_research_timeBy" placeholder="decrease_research_timeBy"/>
        @error('decrease_research_timeBy')
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
        @enderror
        <br/>

        <input type="number" name="decrease_ships_timeBy" placeholder="decrease_ships_timeBy"/>
        @error('decrease_ships_timeBy')
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
        @enderror
        <br/>

        <input type="number" name="decrease_defense_timeBy" placeholder="decrease_defense_timeBy"/>
        @error('decrease_defense_timeBy')
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
        @enderror
        <br/>

        <input type="number" name="decrease_building_timeBy" placeholder="decrease_building_timeBy"/>
        @error('decrease_building_timeBy')
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
        @enderror
        <br/>

        <input type="number" name="dynamic_buildtime" placeholder="dynamic_buildtime"/>
        @error('dynamic_buildtime')
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
        @enderror
        <br/>

        <input type="number" name="initial_buildtime" placeholder="initial_buildtime"/>
        @error('initial_buildtime')
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
        @enderror
        <br/>

        <input type="checkbox" name="allows_research"/>allows_research
        @error('allows_research')
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
        @enderror
        <br/>

        <input type="checkbox" name="allows_ships"/>allows_ships
        @error('allows_ships')
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
        @enderror
        <br/>

        <input type="checkbox" name="allows_defense"/>allows_defense
        @error('allows_defense')
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
        @enderror
        <br/>

        <input type="number" name="points" placeholder="points"/>
        @error('points')
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
        @enderror
        <br/>

        <input type="submit" value="Submit"/>
    </form>
</div>
@endsection
