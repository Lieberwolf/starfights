<div class="">
    <table>
        <thead>
            <tr>
                <th></th>
                <th>Eisen</th>
                <th>Lutinum</th>
                <th>Kristalle</th>
                <th>Wasser</th>
                <th>Wasserstoff</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Basisförderung</td>
                <td class="alert-success">10,00</td>
                <td class="alert-success">10,00</td>
                <td class="alert-info">0,00</td>
                <td class="alert-success">10,00</td>
                <td class="alert-info">0,00</td>
            </tr>
            @foreach($resourceBuildings as $building)
                <tr>
                    <td>{{$building->building_name}} (Stufe: {{$building->infrastructure->level}})</td>
                    @if(($building->prod_fe - $building->cost_fe) > 0)
                        <td class="alert-success">{{number_format($building->prod_fe - $building->cost_fe, 2, ',', '.')}}</td>
                    @elseif($building->prod_fe - $building->cost_fe < 0)
                        <td class="alert-danger">{{number_format($building->prod_fe - $building->cost_fe, 2, ',', '.')}}</td>
                    @else
                        <td class="alert-info">0</td>
                    @endif

                    @if(($building->prod_lut - $building->cost_lut) > 0)
                        <td class="alert-success">{{number_format($building->prod_lut - $building->cost_lut, 2, ',', '.')}}</td>
                    @elseif($building->prod_lut - $building->cost_lut < 0)
                    <td class="alert-danger">{{number_format($building->prod_lut - $building->cost_lut, 2, ',', '.')}}</td>
                    @else
                        <td class="alert-info">0</td>
                    @endif

                    @if(($building->prod_cry - $building->cost_cry) > 0)
                        <td class="alert-success">{{number_format($building->prod_cry - $building->cost_cry, 2, ',', '.')}}</td>
                    @elseif($building->prod_cry - $building->cost_cry < 0)
                    <td class="alert-danger">{{number_format($building->prod_cry - $building->cost_cry, 2, ',', '.')}}</td>
                    @else
                        <td class="alert-info">0</td>
                    @endif

                    @if(($building->prod_h2o - $building->cost_h2o) > 0)
                        <td class="alert-success">{{number_format($building->prod_h2o - $building->cost_h2o, 2, ',', '.')}}</td>
                    @elseif($building->prod_h2o - $building->cost_h2o < 0)
                    <td class="alert-danger">{{number_format($building->prod_h2o - $building->cost_h2o, 2, ',', '.')}}</td>
                    @else
                        <td class="alert-info">0</td>
                    @endif

                    @if(($building->prod_h2 - $building->cost_h2) > 0)
                        <td class="alert-success">{{number_format($building->prod_h2 - $building->cost_h2, 2, ',', '.')}}</td>
                    @elseif($building->prod_h2 - $building->cost_h2 < 0)
                    <td class="alert-danger">{{number_format($building->prod_h2 - $building->cost_h2, 2, ',', '.')}}</td>
                    @else
                        <td class="alert-info">0</td>
                    @endif
                </tr>
            @endforeach
            <tr>
                <td>Planeten Bonus ({{$planetInfo->resource_bonus}}%)</td>
                @if($bonusValues->fe > 0)
                    <td class="alert-success">{{number_format($bonusValues->fe, 2, ',', '.')}}</td>
                @elseif($bonusValues->fe < 0)
                    <td class="alert-danger">{{number_format($bonusValues->fe, 2, ',', '.')}}</td>
                @else
                    <td class="alert-info">0</td>
                @endif
                @if($bonusValues->lut > 0)
                    <td class="alert-success">{{number_format($bonusValues->lut, 2, ',', '.')}}</td>
                @elseif($bonusValues->lut < 0)
                    <td class="alert-danger">{{number_format($bonusValues->lut, 2, ',', '.')}}</td>
                @else
                    <td class="alert-info">0</td>
                @endif
                <td class="alert-info">0</td>
                @if($bonusValues->h2o > 0)
                    <td class="alert-success">{{number_format($bonusValues->h2o, 2, ',', '.')}}</td>
                @elseif($bonusValues->h2o < 0)
                    <td class="alert-danger">{{number_format($bonusValues->h2o, 2, ',', '.')}}</td>
                @else
                    <td class="alert-info">0</td>
                @endif
                <td class="alert-info">0</td>
            </tr>
            <tr>
                <td>Gesamt</td>
                @if($rates->rate_fe > 0)
                    <td class="alert-success">{{number_format($rates->rate_fe, 2, ',', '.')}}</td>
                @elseif($rates->rate_fe < 0)
                    <td class="alert-danger">{{number_format($rates->rate_fe, 2, ',', '.')}}</td>
                @else
                    <td class="alert-info">0</td>
                @endif
                @if($rates->rate_lut > 0)
                    <td class="alert-success">{{number_format($rates->rate_lut, 2, ',', '.')}}</td>
                @elseif($rates->rate_lut < 0)
                    <td class="alert-danger">{{number_format($rates->rate_lut, 2, ',', '.')}}</td>
                @else
                    <td class="alert-info">0</td>
                @endif
                @if($rates->rate_cry > 0)
                    <td class="alert-success">{{number_format($rates->rate_cry, 2, ',', '.')}}</td>
                @elseif($rates->rate_cry < 0)
                    <td class="alert-danger">{{number_format($rates->rate_cry, 2, ',', '.')}}</td>
                @else
                    <td class="alert-info">0</td>
                @endif
                @if($rates->rate_h2o > 0)
                    <td class="alert-success">{{number_format($rates->rate_h2o, 2, ',', '.')}}</td>
                @elseif($rates->rate_h2o < 0)
                    <td class="alert-danger">{{number_format($rates->rate_h2o, 2, ',', '.')}}</td>
                @else
                    <td class="alert-info">0</td>
                @endif
                @if($rates->rate_h2 > 0)
                    <td class="alert-success">{{number_format($rates->rate_h2, 2, ',', '.')}}</td>
                @elseif($rates->rate_h2 < 0)
                    <td class="alert-danger">{{number_format($rates->rate_h2, 2, ',', '.')}}</td>
                @else
                    <td class="alert-info">0</td>
                @endif
            </tr>
            <tr>
                <td>Lagerkapazität</td>
                <td>{{$storage->fe ? number_format(floor($storage->fe), 0, ',', '.') : 0}}</td>
                <td>{{$storage->lut ? number_format(floor($storage->lut), 0, ',', '.') : 0}}</td>
                <td>{{$storage->cry ? number_format(floor($storage->cry), 0, ',', '.') : 0}}</td>
                <td>{{$storage->h2o ? number_format(floor($storage->h2o), 0, ',', '.') : 0}}</td>
                <td>{{$storage->h2 ? number_format(floor($storage->h2), 0, ',', '.') : 0}}</td>
            </tr>
            <tr>
                <td>Davon sicher (4%)</td>
                <td>{{$storage->fe ? number_format(floor($storage->fe * 0.04), 0, ',', '.') : 0}}</td>
                <td>{{$storage->lut ? number_format(floor($storage->lut * 0.04), 0, ',', '.') : 0}}</td>
                <td>{{$storage->cry ? number_format(floor($storage->cry * 0.04), 0, ',', '.') : 0}}</td>
                <td>{{$storage->h2o ? number_format(floor($storage->h2o * 0.04), 0, ',', '.') : 0}}</td>
                <td>{{$storage->h2 ? number_format(floor($storage->h2 * 0.04), 0, ',', '.') : 0}}</td>
            </tr>
        </tbody>
    </table>
</div>
