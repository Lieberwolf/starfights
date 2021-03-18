<div class="container" style="overflow-x: scroll;">
    <div class="row">
        <div class="col-12 title-line">Schiffsübersicht</div>
        <table style="width: 100%;">
                <thead>
                <tr class="title-line">
                    <th></th>
                    @foreach($allUserPlanets as $planet)
                        <th>{{$planet->galaxy}}:{{$planet->system}}:{{$planet->planet}}</th>
                    @endforeach
                </tr>
                </thead>
                <tbody>
                @foreach($allShips as $ship)
                    <tr>
                    <td class="title-line">{{$ship->ship_name}}</td>
                    @foreach($allUserPlanets as $planet)
                        @if($planet->fleet)
                            @foreach($planet->fleet as $shipAtPlanet)
                                @if($shipAtPlanet->ship_id == $ship->id)
                                    <td class="sub-line">{{$shipAtPlanet->amount > 0 ? $shipAtPlanet->amount : ''}}</td>
                                @endif
                            @endforeach
                        @else
                            <td class="sub-line"></td>
                        @endif
                    @endforeach
                    </tr>
                @endforeach
                </tbody>
            </table>
    </div>
</div>
