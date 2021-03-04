@extends('layouts.app')

@section('content')
<div class="container">
    <form action="/racedashboard/{{$race['id']}}/edit" method="post">
        @csrf

        <input value="{{$race['order']}}" type="number" name="order" placeholder="order"/>
        @error('order')
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
        @enderror
        <br/>

        <input value="{{$race['name']}}" type="text" name="name" placeholder="name"/>
        @error('name')
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
        @enderror
        <br/>

        <textarea type="text" name="description" placeholder="description">{{$race['description']}}</textarea>
        @error('description')
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
        @enderror
        <br/>

        <input value="{{$race['speed']}}" type="number" name="speed" placeholder="speed"/>
        @error('speed')
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
        @enderror
        <br/>

        <input value="{{$race['attack']}}" type="number" name="attack" placeholder="attack"/>
        @error('attack')
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
        @enderror
        <br/>

        <input value="{{$race['defend']}}" type="number" name="defend" placeholder="defend"/>
        @error('defend')
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
        @enderror
        <br/>

        <input value="{{$race['cargo']}}" type="number" name="cargo" placeholder="cargo"/>
        @error('cargo')
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
        @enderror
        <br/>

        <input value="{{$race['consumption']}}" type="number" name="consumption" placeholder="consumption"/>
        @error('consumption')
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
        @enderror
        <br/>

        <input {{$race['spy'] == 'on' ? 'checked="checked"' : ''}} type="checkbox" name="spy"/>spy
        @error('spy')
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
        @enderror
        <br/>

        <input {{$race['stealth'] == 'on' ? 'checked="checked"' : ''}} type="checkbox" name="stealth"/>stealth
        @error('stealth')
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
        @enderror
        <br/>

        <input value="{{$race['fe']}}" type="number" name="fe" placeholder="fe"/>
        @error('fe')
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
        @enderror
        <br/>

        <input value="{{$race['lut']}}" type="number" name="lut" placeholder="lut"/>
        @error('lut')
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
        @enderror
        <br/>

        <input value="{{$race['cry']}}" type="number" name="cry" placeholder="cry"/>
        @error('cry')
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
        @enderror
        <br/>

        <input value="{{$race['h2o']}}" type="number" name="h2o" placeholder="h2o"/>
        @error('h2o')
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
        @enderror
        <br/>

        <input value="{{$race['h2']}}" type="number" name="h2" placeholder="h2"/>
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
