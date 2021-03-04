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
            <a href="/shipdashboard/create" title="{{ __('New Ship') }}">{{ __('New Ship') }}</a>

            @if (count($ships) > 0)
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
                        <th>Invasion</th>
                        <th>Delta Scan</th>
                        <th>Colonization</th>
                        <th>Iron</th>
                        <th>Lutinum</th>
                        <th>Crystal</th>
                        <th>Water</th>
                        <th>Hydrogen</th>
                        <th></th>
                    </tr>
                </thead>
                @foreach($ships as $ship)
                    <tr>
                        <td>{{$ship["id"]}}</td>
                        <td>{{$ship["order"]}}</td>
                        <td>{{$ship["ship_name"]}}</td>
                        <td>{{$ship["description"]}}</td>
                        <td>{{$ship["speed"]}}</td>
                        <td>{{$ship["attack"]}}</td>
                        <td>{{$ship["defend"]}}</td>
                        <td>{{$ship["cargo"]}}</td>
                        <td>{{$ship["consumption"]}}</td>
                        <td>{{$ship["spy"]}}</td>
                        <td>{{$ship["stealth"]}}</td>
                        <td>{{$ship["invasion"]}}</td>
                        <td>{{$ship["delta_scan"]}}</td>
                        <td>{{$ship["colonization"]}}</td>
                        <td>{{$ship["fe"]}}</td>
                        <td>{{$ship["lut"]}}</td>
                        <td>{{$ship["cry"]}}</td>
                        <td>{{$ship["h2o"]}}</td>
                        <td>{{$ship["h2"]}}</td>
                        <td><a href="/shipdashboard/{{$ship["id"]}}">Edit</a></td>
                    </tr>
                @endforeach
            </table>
            @endif
            <hr>
            <form action="/shipdashboard/saveR" method="post">
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
                    @foreach($ships as $ship)
                        <tr>
                            <td>{{$ship["ship_name"]}}</td>
                            @foreach($researches as $researchItem)
                                <td>
                                    @if($ship['research_requirements'])
                                        @foreach($ship['research_requirements'] as $key => $req)
                                            @if($key == $researchItem["research_name"])
                                                <input value="{{$req == null ? 0 : $req}}" type="text" name="ship[{{$ship["ship_name"]}}][research_requirements][{{$researchItem["research_name"]}}]">
                                            @endif
                                        @endforeach
                                    @else
                                        <input value="0" type="text" name="ship[{{$ship["ship_name"]}}][research_requirements][{{$researchItem["research_name"]}}]">
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
            <form action="/shipdashboard/saveB" method="post">
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
                    @foreach($ships as $ship)
                        <tr>
                            <td>{{$ship["ship_name"]}}</td>
                            @foreach($buildings as $buildingItem)
                                <td>
                                    @if($ship['building_requirements'])
                                        @foreach($ship['building_requirements'] as $key => $req)
                                            @if($key == $buildingItem["building_name"])
                                                <input value="{{$req == null ? 0 : $req}}" type="text" name="ship[{{$ship["ship_name"]}}][building_requirements][{{$buildingItem["building_name"]}}]">
                                            @endif
                                        @endforeach
                                    @else
                                        <input value="0" type="text" name="ship[{{$ship["ship_name"]}}][building_requirements][{{$buildingItem["building_name"]}}]">
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
