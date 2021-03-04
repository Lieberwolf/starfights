@extends('layouts.app')

@section('content')
<div class="container">
    <form action="/defensedashboard/{{$defense['id']}}/edit" method="post">
        @csrf

        <input value="{{$defense['order']}}" type="number" name="order" placeholder="order"/>
        @error('order')
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
        @enderror
        <br/>

        <input value="{{$defense['name']}}" type="text" name="name" placeholder="name"/>
        @error('name')
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
        @enderror
        <br/>

        <textarea type="text" name="description" placeholder="description">{{$defense['description']}}</textarea>
        @error('description')
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
        @enderror
        <br/>

        <input value="{{$defense['speed']}}" type="number" name="speed" placeholder="speed"/>
        @error('speed')
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
        @enderror
        <br/>

        <input value="{{$defense['attack']}}" type="number" name="attack" placeholder="attack"/>
        @error('attack')
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
        @enderror
        <br/>

        <input value="{{$defense['defend']}}" type="number" name="defend" placeholder="defend"/>
        @error('defend')
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
        @enderror
        <br/>

        <input value="{{$defense['cargo']}}" type="number" name="cargo" placeholder="cargo"/>
        @error('cargo')
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
        @enderror
        <br/>

        <input value="{{$defense['consumption']}}" type="number" name="consumption" placeholder="consumption"/>
        @error('consumption')
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
        @enderror
        <br/>

        <input {{$defense['spy'] == 'on' ? 'checked="checked"' : ''}} type="checkbox" name="spy"/>spy
        @error('spy')
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
        @enderror
        <br/>

        <input {{$defense['stealth'] == 'on' ? 'checked="checked"' : ''}} type="checkbox" name="stealth"/>stealth
        @error('stealth')
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
        @enderror
        <br/>

        <input value="{{$defense['fe']}}" type="number" name="fe" placeholder="fe"/>
        @error('fe')
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
        @enderror
        <br/>

        <input value="{{$defense['lut']}}" type="number" name="lut" placeholder="lut"/>
        @error('lut')
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
        @enderror
        <br/>

        <input value="{{$defense['cry']}}" type="number" name="cry" placeholder="cry"/>
        @error('cry')
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
        @enderror
        <br/>

        <input value="{{$defense['h2o']}}" type="number" name="h2o" placeholder="h2o"/>
        @error('h2o')
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
        @enderror
        <br/>

        <input value="{{$defense['h2']}}" type="number" name="h2" placeholder="h2"/>
        @error('h2')
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
        @enderror
        <br/>

        <input type="submit" value="Submit"/>
    </form>
</div>
@endsection
