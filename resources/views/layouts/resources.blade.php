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
                <td>10</td>
                <td>10</td>
                <td>0</td>
                <td>10</td>
                <td>0</td>
            </tr>
            @foreach($resourceBuildings as $building)
                <tr>
                    <td>{{$building->building_name}} (Stufe: {{$building->infrastructure->level}})</td>
                    @if($building->rate_fe > 0)
                        <td class="alert-success">{{number_format(floor($building->rate_fe), 0, ',', '.')}}</td>
                    @endif
                    @if($building->cost_fe > 0)
                        <td class="alert-danger">{{number_format(floor($building->cost_fe), 0, ',', '.')}}</td>
                    @endif
                    @if($building->rate_fe == 0 && $building->cost_fe == 0)
                        <td class="alert-info">0</td>
                    @endif

                    @if($building->rate_lut > 0)
                        <td class="alert-success">{{number_format(floor($building->rate_lut), 0, ',', '.')}}</td>
                    @endif
                    @if($building->cost_lut > 0)
                        <td class="alert-danger">{{number_format(floor($building->cost_lut), 0, ',', '.')}}</td>
                    @endif
                    @if($building->rate_lut == 0 && $building->cost_lut == 0)
                        <td class="alert-info">0</td>
                    @endif

                    @if($building->rate_cry > 0)
                        <td class="alert-success">{{number_format(floor($building->rate_cry), 0, ',', '.')}}</td>
                    @endif
                    @if($building->cost_cry > 0)
                        <td class="alert-danger">{{number_format(floor($building->cost_cry), 0, ',', '.')}}</td>
                    @endif
                    @if($building->rate_cry == 0 && $building->cost_cry == 0)
                        <td class="alert-info">0</td>
                    @endif

                    @if($building->rate_h2o > 0)
                        <td class="alert-success">{{number_format(floor($building->rate_h2o), 0, ',', '.')}}</td>
                    @endif
                    @if($building->cost_h2o > 0)
                        <td class="alert-danger">{{number_format(floor($building->cost_h2o), 0, ',', '.')}}</td>
                    @endif
                    @if($building->rate_h2o == 0 && $building->cost_h2o == 0)
                        <td class="alert-info">0</td>
                    @endif

                    @if($building->rate_h2 > 0)
                        <td class="alert-success">{{number_format(floor($building->rate_h2), 0, ',', '.')}}</td>
                    @endif
                    @if($building->cost_h2 > 0)
                        <td class="alert-danger">{{number_format(floor($building->cost_h2), 0, ',', '.')}}</td>
                    @endif
                    @if($building->rate_h2 == 0 && $building->cost_h2 == 0)
                        <td class="alert-info">0</td>
                    @endif
                </tr>
            @endforeach
            <tr>
                <td>Gesamt</td>
                <td>{{number_format(floor($rates->fe + 10), 0, ',', '.')}}</td>
                <td>{{number_format(floor($rates->lut + 10), 0, ',', '.')}}</td>
                <td>{{number_format(floor($rates->cry), 0, ',', '.')}}</td>
                <td>{{number_format(floor($rates->h2o + 10), 0, ',', '.')}}</td>
                <td>{{number_format(floor($rates->h2), 0, ',', '.')}}</td>
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
