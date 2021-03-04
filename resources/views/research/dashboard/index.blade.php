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
            <a href="/researchdashboard/create" title="{{ __('New Research') }}">{{ __('New Research') }}</a>

            @if (count($researches) > 0)
            <table>
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>Name</th>
                        <th>Description</th>
                        <th>Iron</th>
                        <th>Lutinum</th>
                        <th>Crystal</th>
                        <th>Water</th>
                        <th>Hydrogen</th>
                        <th></th>
                    </tr>
                </thead>
                @foreach($researches as $research)
                    <tr>
                        <td>{{$research["id"]}}</td>
                        <td>{{$research["research_name"]}}</td>
                        <td>{{$research["description"]}}</td>
                        <td>{{$research["fe"]}}</td>
                        <td>{{$research["lut"]}}</td>
                        <td>{{$research["cry"]}}</td>
                        <td>{{$research["h2o"]}}</td>
                        <td>{{$research["h2"]}}</td>
                        <td><a href="/researchdashboard/{{$research["id"]}}">Edit</a></td>
                    </tr>
                @endforeach
            </table>
            @endif
            <hr>
            <form action="/researchdashboard/save" method="post">
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
                    @foreach($researches as $research)
                        <tr>
                            <td>{{$research["research_name"]}}</td>
                            @foreach($researches as $researchItem)
                                <td>
                                    @if($research["id"] == $researchItem["id"])
                                        <input value="0" readonly type="text" name="research[{{$research["research_name"]}}][research_requirements][{{$researchItem["research_name"]}}]">
                                    @else
                                        @if($research['research_requirements'])
                                            @foreach($research['research_requirements'] as $key => $req)
                                                @if($key == $researchItem["research_name"])
                                                    <input value="{{$req == null ? 0 : $req}}" type="text" name="research[{{$research["research_name"]}}][research_requirements][{{$researchItem["research_name"]}}]">
                                                @endif
                                            @endforeach
                                        @else
                                            <input value="0" type="text" name="research[{{$research["research_name"]}}][research_requirements][{{$researchItem["research_name"]}}]">
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
            <form action="/researchdashboard/saveB" method="post">
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
                    @foreach($researches as $research)
                        <tr>
                            <td>{{$research["research_name"]}}</td>
                            @foreach($buildings as $buildingItem)
                                <td>
                                    @if($research['building_requirements'])
                                        @foreach($research['building_requirements'] as $key => $req)
                                            @if($key == $buildingItem["building_name"])
                                                <input value="{{$req == null ? 0 : $req}}" type="text" name="research[{{$research["research_name"]}}][building_requirements][{{$buildingItem["building_name"]}}]">
                                            @endif
                                        @endforeach
                                    @else
                                        <input value="0" type="text" name="research[{{$research["research_name"]}}][building_requirements][{{$buildingItem["building_name"]}}]">
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
