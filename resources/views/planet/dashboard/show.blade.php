@extends('layouts.app')

@section('content')
<div class="container">
    <form action="/planetdashboard/{{$planet['id']}}/edit" method="post">
        @csrf

        <input value="{{$planet['galaxy']}}" type="number" name="galaxy" placeholder="order"/>
        @error('galaxy')
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
        @enderror
        <br/>
        <input value="{{$planet['system']}}" type="number" name="system" placeholder="order"/>
        @error('system')
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
        @enderror
        <br/>
        <input value="{{$planet['planet']}}" type="number" name="planet" placeholder="order"/>
        @error('planet')
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
        @enderror
        <br/>
        <input value="{{$planet['diameter']}}" type="number" name="diameter" placeholder="order"/>
        @error('diameter')
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
        @enderror
        <br/>
        <input value="{{$planet['temperature']}}" type="number" name="temperature" placeholder="order"/>
        @error('temperature')
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
        @enderror
        <br/>
        <input value="{{$planet['resource_bonus']}}" type="number" name="resource_bonus" placeholder="order"/>
        @error('resource_bonus')
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
        @enderror
        <br/>

        <input value="{{$planet['planet_name']}}" type="text" name="planet_name" placeholder="planet_name"/>
        @error('planet_name')
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
        @enderror
        <br/>

        <input value="{{$planet['user_id']}}" type="text" name="user_id" placeholder="user_id"/>
        @error('user_id')
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
        @enderror
        <br/>

        <input value="{{$planet['fe']}}" type="text" name="fe" placeholder="fe"/>
        @error('fe')
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
        @enderror
        <br/>

        <input value="{{$planet['lut']}}" type="text" name="lut" placeholder="lut"/>
        @error('lut')
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
        @enderror
        <br/>

        <input value="{{$planet['cry']}}" type="text" name="cry" placeholder="crystal"/>
        @error('fe')
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
        @enderror
        <br/>

        <input value="{{$planet['h2o']}}" type="text" name="h2o" placeholder="water"/>
        @error('h2o')
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
        @enderror
        <br/>

        <input value="{{$planet['h2']}}" type="text" name="h2" placeholder="hydrogen"/>
        @error('h2')
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
        @enderror
        <br/>

        <input value="{{$planet['rate_fe']}}" type="text" name="rate_fe" placeholder="rate_fe"/>
        @error('rate_fe')
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
        @enderror
        <br/>

        <input value="{{$planet['rate_lut']}}" type="text" name="rate_lut" placeholder="rate_lut"/>
        @error('rate_lut')
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
        @enderror
        <br/>

        <input value="{{$planet['rate_cry']}}" type="text" name="rate_cry" placeholder="rate_cry"/>
        @error('rate_cry')
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
        @enderror
        <br/>

        <input value="{{$planet['rate_h2o']}}" type="text" name="rate_h2o" placeholder="rate_water"/>
        @error('rate_h2o')
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
        @enderror
        <br/>

        <input value="{{$planet['rate_h2']}}" type="text" name="rate_h2" placeholder="rate_h2"/>
        @error('rate_h2')
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
        @enderror
        <br/>

        <input type="submit" value="Submit"/>
    </form>
</div>
@endsection
