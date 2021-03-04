@extends('layouts.app')

@section('content')
<div class="container">
    <form action="/shipdashboard/{{$ship['id']}}/edit" method="post">
        @csrf

        <input value="{{$ship['order']}}" type="number" name="order" placeholder="order"/>
        @error('order')
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
        @enderror
        <br/>

        <input value="{{$ship['ship_name']}}" type="text" name="ship_name" placeholder="ship_name"/>
        @error('ship_name')
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
        @enderror
        <br/>

        <textarea type="text" name="description" placeholder="description">{{$ship['description']}}</textarea>
        @error('description')
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
        @enderror
        <br/>

        <input value="{{$ship['speed']}}" type="number" name="speed" placeholder="speed"/>
        @error('speed')
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
        @enderror
        <br/>

        <input value="{{$ship['attack']}}" type="number" name="attack" placeholder="attack"/>
        @error('attack')
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
        @enderror
        <br/>

        <input value="{{$ship['defend']}}" type="number" name="defend" placeholder="defend"/>
        @error('defend')
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
        @enderror
        <br/>

        <input value="{{$ship['cargo']}}" type="number" name="cargo" placeholder="cargo"/>
        @error('cargo')
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
        @enderror
        <br/>

        <input value="{{$ship['consumption']}}" type="number" name="consumption" placeholder="consumption"/>
        @error('consumption')
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
        @enderror
        <br/>

        <input {{$ship['spy'] == 'on' ? 'checked="checked"' : ''}} type="checkbox" name="spy"/>spy
        @error('spy')
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
        @enderror
        <br/>

        <input {{$ship['stealth'] == 'on' ? 'checked="checked"' : ''}} type="checkbox" name="stealth"/>stealth
        @error('stealth')
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
        @enderror
        <br/>

        <input {{$ship['delta_scan'] == 'on' ? 'checked="checked"' : ''}} type="checkbox" name="delta_scan"/>delta_scan
        @error('delta_scan')
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
        @enderror
        <br/>

        <input {{$ship['invasion'] == 'on' ? 'checked="checked"' : ''}} type="checkbox" name="invasion"/>invasion
        @error('invasion')
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
        @enderror
        <br/>

        <input {{$ship['stealth'] == 'on' ? 'checked="checked"' : ''}} type="checkbox" name="stealth"/>stealth
        @error('stealth')
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
        @enderror
        <br/>

        <input value="{{$ship['fe']}}" type="number" name="fe" placeholder="fe"/>
        @error('fe')
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
        @enderror
        <br/>

        <input value="{{$ship['lut']}}" type="number" name="lut" placeholder="lut"/>
        @error('lut')
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
        @enderror
        <br/>

        <input value="{{$ship['cry']}}" type="number" name="cry" placeholder="cry"/>
        @error('cry')
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
        @enderror
        <br/>

        <input value="{{$ship['h2o']}}" type="number" name="h2o" placeholder="h2o"/>
        @error('h2o')
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
        @enderror
        <br/>

        <input value="{{$ship['h2']}}" type="number" name="h2" placeholder="h2"/>
        @error('h2')
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
        @enderror
        <br/>

        <input value="{{$ship['initial_buildtime']}}" type="number" name="initial_buildtime" placeholder="initial_buildtime"/>
        @error('initial_buildtime')
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
        @enderror
        <br/>

        <input type="submit" value="Submit"/>
    </form>
</div>
@endsection
