@extends('layouts.app')

@section('content')
<div class="container">
    <form action="/researchdashboard" method="post">
        @csrf

        <input type="number" name="order" placeholder="order"/>
        @error('order')
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
        @enderror
        <br/>

        <input type="text" name="research_name" placeholder="research name"/>
        @error('research_name')
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
        @enderror
        <br/>

        <input type="submit" value="Submit"/>
    </form>
</div>
@endsection
