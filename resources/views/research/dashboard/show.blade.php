@extends('layouts.app')

@section('content')
<div class="container">
    <form action="/researchdashboard/{{$research['id']}}/edit" method="post">
        @csrf

        <input value="{{$research['research_name']}}" type="text" name="research_name" placeholder="research_name"/>
        @error('research_name')
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
        @enderror
        <br/>

        <textarea type="text" name="description" placeholder="description">{{$research['description']}}</textarea>
        @error('description')
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
        @enderror
        <br/>

        <input value="{{$research['fe']}}" type="number" name="fe" placeholder="fe"/>
        @error('fe')
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
        @enderror
        <br/>

        <input value="{{$research['lut']}}" type="number" name="lut" placeholder="lut"/>
        @error('lut')
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
        @enderror
        <br/>

        <input value="{{$research['cry']}}" type="number" name="cry" placeholder="cry"/>
        @error('cry')
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
        @enderror
        <br/>

        <input value="{{$research['h2o']}}" type="number" name="h2o" placeholder="h2o"/>
        @error('h2o')
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
        @enderror
        <br/>

        <input value="{{$research['h2']}}" type="number" name="h2" placeholder="h2"/>
        @error('h2')
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
        @enderror
        <br/>

        <input value="{{$research['increase_spy']}}" type="number" name="increase_spy" placeholder="increase_spy"/>
        @error('increase_spy')
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
        @enderror
        <br/>

        <input value="{{$research['increase_counter_spy']}}" type="number" name="increase_counter_spy" placeholder="increase_counter_spy"/>
        @error('increase_counter_spy')
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
        @enderror
        <br/>

        <input value="{{$research['increase_ship_attack']}}" type="number" name="increase_ship_attack" placeholder="increase_ship_attack"/>
        @error('increase_ship_attack')
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
        @enderror
        <br/>

        <input value="{{$research['increase_ship_defense']}}" type="number" name="increase_ship_defense" placeholder="increase_ship_defense"/>
        @error('increase_ship_defense')
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
        @enderror
        <br/>

        <input value="{{$research['increase_shield_defense']}}" type="number" name="increase_shield_defense" placeholder="increase_shield_defense"/>
        @error('increase_shield_defense')
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
        @enderror
        <br/>

        <input value="{{$research['increase_rocket_drive']}}" type="number" name="increase_rocket_drive" placeholder="increase_rocket_drive"/>
        @error('increase_rocket_drive')
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
        @enderror
        <br/>

        <input value="{{$research['increase_turbine_drive']}}" type="number" name="increase_turbine_drive" placeholder="increase_turbine_drive"/>
        @error('increase_turbine_drive')
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
        @enderror
        <br/>

        <input value="{{$research['increase_warp_drive']}}" type="number" name="increase_warp_drive" placeholder="increase_warp_drive"/>
        @error('increase_warp_drive')
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
        @enderror
        <br/>

        <input value="{{$research['increase_transwarp_drive']}}" type="number" name="increase_transwarp_drive" placeholder="increase_transwarp_drive"/>
        @error('increase_transwarp_drive')
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
        @enderror
        <br/>

        <input value="{{$research['increase_ion_drive']}}" type="number" name="increase_ion_drive" placeholder="increase_ion_drive"/>
        @error('increase_ion_drive')
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
        @enderror
        <br/>

        <input value="{{$research['increase_max_planets']}}" type="number" name="increase_max_planets" placeholder="increase_max_planets"/>
        @error('increase_max_planets')
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
        @enderror
        <br/>

        <input value="{{$research['increase_cargo']}}" type="number" name="increase_cargo" placeholder="increase_cargo"/>
        @error('increase_cargo')
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
        @enderror
        <br/>

        <input value="{{$research['static_bonus']}}" type="number" name="static_bonus" placeholder="static_bonus"/>
        @error('static_bonus')
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
        @enderror
        <br/>

        <input value="{{$research['points']}}" type="number" name="points" placeholder="points"/>
        @error('points')
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
        @enderror
        <br/>

        <input value="{{$research['initial_researchtime']}}" type="number" name="initial_researchtime" placeholder="initial_researchtime"/>
        @error('initial_researchtime')
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
        @enderror
        <br/>

        <input type="submit" value="Submit"/>
    </form>
</div>
@endsection
