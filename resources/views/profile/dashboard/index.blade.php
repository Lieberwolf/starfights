@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Defense Dashboard') }}</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
                    <a href="/defensedashboard/create" title="{{ __('New Defense') }}">{{ __('New Defense') }}</a>

                    @if (count($defenses) > 0)
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
                        @foreach($defenses as $defense)
                            <tr>
                                <td>{{$defense["id"]}}</td>
                                <td>{{$defense["order"]}}</td>
                                <td>{{$defense["name"]}}</td>
                                <td>{{$defense["description"]}}</td>
                                <td>{{$defense["speed"]}}</td>
                                <td>{{$defense["attack"]}}</td>
                                <td>{{$defense["defend"]}}</td>
                                <td>{{$defense["cargo"]}}</td>
                                <td>{{$defense["consumption"]}}</td>
                                <td>{{$defense["spy"]}}</td>
                                <td>{{$defense["stealth"]}}</td>
                                <td>{{$defense["fe"]}}</td>
                                <td>{{$defense["lut"]}}</td>
                                <td>{{$defense["cry"]}}</td>
                                <td>{{$defense["h2o"]}}</td>
                                <td>{{$defense["h2"]}}</td>
                                <td><a href="/defensedashboard/{{$defense["id"]}}">Edit</a></td>
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
