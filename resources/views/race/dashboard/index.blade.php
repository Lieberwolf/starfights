@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Race Dashboard') }}</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
                    <a href="/racedashboard/create" title="{{ __('New Race') }}">{{ __('New Race') }}</a>

                    @if (count($races) > 0)
                    <table>
                        <thead>
                            <tr>
                                <th>No.</th>
                                <th>Order</th>
                                <th>Name</th>
                                <th>Description</th>
                                <th>Speed</th>
                                <th>Attack</th>
                                <th>Defend</th>
                                <th>Cargo</th>
                                <th>Consumption</th>
                                <th>Spy</th>
                                <th>Stealth</th>
                                <th>Iron</th>
                                <th>{{ __('globals.lut') }}</th>
                                <th>Crystal</th>
                                <th>Water</th>
                                <th>Hydrogen</th>
                                <th></th>
                            </tr>
                        </thead>
                        @foreach($races as $race)
                            <tr>
                                <td>{{$race["id"]}}</td>
                                <td>{{$race["order"]}}</td>
                                <td>{{$race["name"]}}</td>
                                <td>{{$race["description"]}}</td>
                                <td>{{$race["speed"]}}</td>
                                <td>{{$race["attack"]}}</td>
                                <td>{{$race["defend"]}}</td>
                                <td>{{$race["cargo"]}}</td>
                                <td>{{$race["consumption"]}}</td>
                                <td>{{$race["spy"]}}</td>
                                <td>{{$race["stealth"]}}</td>
                                <td>{{$race["fe"]}}</td>
                                <td>{{$race["lut"]}}</td>
                                <td>{{$race["cry"]}}</td>
                                <td>{{$race["h2o"]}}</td>
                                <td>{{$race["h2"]}}</td>
                                <td><a href="/racedashboard/{{$race["id"]}}">Edit</a></td>
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
