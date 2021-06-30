@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-2">
                @include('layouts.menu')
            </div>
            <div class="col-md-10">
                <script>
                    function allowDrop(ev) {
                        ev.preventDefault();
                    }

                    function drag(ev) {
                        ev.dataTransfer.setData("text", ev.target.id);
                    }

                    function drop(ev, el) {
                        ev.preventDefault();
                        var data = ev.dataTransfer.getData("text");
                        el.appendChild(document.getElementById(data));
                    }
                </script>
                <div class="row">
                    <div class="col-12 js-drop" style="background: blue; padding: 15px;" ondrop="drop(event, this)" ondragover="allowDrop(event)">
                        @foreach($allUserPlanets as $planet)
                        <button title="" style="margin: 5px;" class="btn btn-danger js-planet" id="planet{{$planet->id}}" ondragstart="drag(event)" draggable="true" type="button" data-planet-id="{{$planet->id}}">
                            <span>{{$planet->galaxy}}:{{$planet->system}}:{{$planet->planet}}</span><br/>
                            <span class="time">-</span>
                        </button>
                        @endforeach
                    </div>
                    @foreach($allBuildings as $building)
                    <div class="col-3">
                        <div class="card">
                            <div class="card-header">{{$building->building_name}}</div>
                            <div class="card-body js-drop" data-building-id="{{$building->id}}" style="background: pink;padding:15px;" ondrop="drop(event, this)" ondragover="allowDrop(event)">

                            </div>
                        </div>
                    </div>
                    @endforeach

                </div>

            </div>

        </div>
    </div>
@endsection
