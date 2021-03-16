@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
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
                        <th>Attack</th>
                        <th>Defend</th>
                        <th>Iron</th>
                        <th>Lutinum</th>
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
                        <td>{{$defense["turret_name"]}}</td>
                        <td>{{$defense["description"]}}</td>
                        <td>{{$defense["attack"]}}</td>
                        <td>{{$defense["defend"]}}</td>
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
            <hr>
            <form action="/defensedashboard/saveR" method="post">
                @csrf
                <table>
                    <thead>
                    <tr>
                        <th>Forschung</th>
                        @foreach($researches as $research)
                            <th>{{$research["research_name"]}}</th>
                        @endforeach
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($defenses as $defense)
                        <tr>
                            <td>{{$defense["turret_name"]}}</td>
                            @foreach($researches as $researchItem)
                                <td>
                                    @if($defense['research_requirements'])
                                        @foreach($defense['research_requirements'] as $key => $req)
                                            @if($key == $researchItem["research_name"])
                                                <input value="{{$req == null ? 0 : $req}}" type="text" name="turret[{{$defense["turret_name"]}}][research_requirements][{{$researchItem["research_name"]}}]">
                                            @endif
                                        @endforeach
                                    @else
                                        <input value="0" type="text" name="turret[{{$defense["turret_name"]}}][research_requirements][{{$researchItem["research_name"]}}]">
                                    @endif
                                </td>
                            @endforeach
                        </tr>
                    @endforeach
                    </tbody>
                </table>
                <button type="submit">Speichern</button>
            </form>
            <hr>
            <form action="/defensedashboard/saveB" method="post">
                @csrf
                <table>
                    <thead>
                    <tr>
                        <th>Gebäude</th>
                        @foreach($buildings as $building)
                            <th>{{$building["building_name"]}}</th>
                        @endforeach
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($defenses as $defense)
                        <tr>
                            <td>{{$defense["turret_name"]}}</td>
                            @foreach($buildings as $buildingItem)
                                <td>
                                    @if($defense['building_requirements'])
                                        @foreach($defense['building_requirements'] as $key => $req)
                                            @if($key == $buildingItem["building_name"])
                                                <input value="{{$req == null ? 0 : $req}}" type="text" name="turret[{{$defense["turret_name"]}}][building_requirements][{{$buildingItem["building_name"]}}]">
                                            @endif
                                        @endforeach
                                    @else
                                        <input value="0" type="text" name="turret[{{$defense["turret_name"]}}][building_requirements][{{$buildingItem["building_name"]}}]">
                                    @endif
                                </td>
                            @endforeach
                        </tr>
                    @endforeach
                    </tbody>
                </table>
                <button type="submit">Speichern</button>
            </form>
        </div>
    </div>
</div>
@endsection
