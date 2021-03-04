@extends('layouts.app')

@section('content')
<div class="container">
    <form action="/racedashboard" method="post">
        @csrf

        <input type="text" name="race_name" placeholder="race name"/>
        @error('race_name')
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
        @enderror
        <br/>

        <input type="text" name="description" placeholder="description"/>
        @error('description')
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
        @enderror
        <br/>

        <input type="submit" value="Submit"/>
    </form>
</div>
@endsection
