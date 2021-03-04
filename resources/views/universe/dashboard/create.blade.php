@extends('layouts.app')

@section('content')
<div class="container">
    <form action="/universedashboard" method="post">
        @csrf

        <input type="number" name="galaxies" placeholder="Galaxies"/>
        @error('galaxies')
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
        @enderror
        <br/>
        <input type="number" name="systems" placeholder="Systems"/>
        @error('systems')
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
        @enderror
        <br/>
        <input type="number" name="planets" placeholder="Planets"/>
        @error('planets')
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
        @enderror
        <br/>

        <input type="submit" value="Submit"/>
    </form>
</div>
@endsection
