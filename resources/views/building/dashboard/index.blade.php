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
            <a href="/buildingdashboard/create" title="{{ __('New Building') }}">{{ __('New Building') }}</a>

            @if (count($buildings) > 0)
            <table>
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>Name</th>
                        <th>Description</th>
                        <th>Iron</th>
                        <th>{{ __('globals.lut') }}</th>
                        <th>Crystal</th>
                        <th>Water</th>
                        <th>Hydrogen</th>
                        <th></th>
                    </tr>
                </thead>
                @foreach($buildings as $building)
                    <tr>
                        <td>{{$building["id"]}}</td>
                        <td>{{$building["building_name"]}}</td>
                        <td>{{$building["description"]}}</td>
                        <td>{{$building["fe"]}}</td>
                        <td>{{$building["lut"]}}</td>
                        <td>{{$building["cry"]}}</td>
                        <td>{{$building["h2o"]}}</td>
                        <td>{{$building["h2"]}}</td>
                        <td><a href="/buildingdashboard/{{$building["id"]}}">Edit</a></td>
                    </tr>
                @endforeach
            </table>
            @endif
            <hr>
            <form action="/buildingdashboard/save" method="POST">
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
                    @foreach($buildings as $building)
                        <tr>
                            <td>{{$building["building_name"]}}</td>
                            @foreach($buildings as $buildingItem)
                                <td>
                                    @if($building["id"] == $buildingItem["id"])
                                        <input value="0" readonly type="text" name="building[{{$building["building_name"]}}][building_requirements][{{$buildingItem["building_name"]}}]">
                                    @else
                                        @if($building['building_requirements'])
                                            @foreach($building['building_requirements'] as $key => $req)
                                                @if($key == $buildingItem["building_name"])
                                                    <input value="{{$req == null ? 0 : $req}}" type="text" name="building[{{$building["building_name"]}}][building_requirements][{{$buildingItem["building_name"]}}]">
                                                @endif
                                            @endforeach
                                        @else
                                            <input value="0" type="text" name="building[{{$building["building_name"]}}][building_requirements][{{$buildingItem["building_name"]}}]">
                                        @endif
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
            <form action="/buildingdashboard/saveR" method="post">
                @csrf
                <table>
                    <thead>
                    <tr>
                        <th>Gebäude</th>
                        @foreach($researches as $research)
                            <th>{{$research["research_name"]}}</th>
                        @endforeach
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($buildings as $building)
                        <tr>
                            <td>{{$building["building_name"]}}</td>
                            @foreach($researches as $researchItem)
                                <td>
                                    @if($building['research_requirements'])
                                        @foreach($building['research_requirements'] as $key => $req)
                                            @if($key == $researchItem["research_name"])
                                                <input value="{{$req == null ? 0 : $req}}" type="text" name="building[{{$building["building_name"]}}][research_requirements][{{$researchItem["research_name"]}}]">
                                            @endif
                                        @endforeach
                                    @else
                                        <input value="0" type="text" name="building[{{$building["building_name"]}}][research_requirements][{{$researchItem["research_name"]}}]">
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
