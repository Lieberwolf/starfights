@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Universe Dashboard') }}</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <a href="/universedashboard/create">Create new Universe</a>
                        @if (count($planets) > 0)
                            <table>
                                <thead>
                                <tr>
                                    <th>Planet</th>
                                    <th>Diameter</th>
                                    <th>Temperature</th>
                                    <th>Atmosphere</th>
                                    <th>Resource Bonus</th>
                                    <th>Username</th>
                                    <th></th>
                                </tr>
                                </thead>
                                @foreach($planets as $planet)
                                    <tr>
                                        <td>{{$planet->planet}}</td>
                                        <td>{{$planet->diameter}}</td>
                                        <td>{{$planet->temperature}}</td>
                                        <td>{{$planet->atmosphere}}</td>
                                        <td>{{$planet->resource_bonus}}</td>
                                        <td>{{$planet->username}}</td>
                                        <td><a href="/planetdashboard/{{$planet->id}}">Edit</a></td>
                                    </tr>
                                @endforeach
                            </table>
                        @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
