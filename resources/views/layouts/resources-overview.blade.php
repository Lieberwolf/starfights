<div class="container-fluid">
    <div class="row">
        <div class="col-12 title-line">Ressourcenübersicht</div>
            <table style="width: 100%;">
                <thead>
                <tr class="title-line">
                    <th class="col-md-2">Planet</th>
                    <th class="col-md-2">Eisen</th>
                    <th class="col-md-2">Lutinum</th>
                    <th class="col-md-2">Kristalle</th>
                    <th class="col-md-2">Wasser</th>
                    <th class="col-md-2">Wasserstoff</th>
                </tr>
                </thead>
                <tbody>
                @foreach($allPlanetRates as $rate)
                <tr style="border-top: 1px solid #000;">

                    <td class="title-line">{{$rate['galaxy']}}:{{$rate['system']}}:{{$rate['planet']}}</td>
                    @if($rate['fe'] < $rate['max_fe'])
                        <td class="alert-success">{{number_format($rate['fe'], 0, ',', '.')}}</td>
                    @else
                        <td class="alert-danger">{{number_format($rate['fe'], 0, ',', '.')}}</td>
                    @endif
                    @if($rate['lut'] < $rate['max_lut'])
                        <td class="alert-success">{{number_format($rate['lut'], 0, ',', '.')}}</td>
                    @else
                        <td class="alert-danger">{{number_format($rate['lut'], 0, ',', '.')}}</td>
                    @endif
                    @if($rate['cry'] < $rate['max_cry'])
                        <td class="alert-success">{{number_format($rate['cry'], 0, ',', '.')}}</td>
                    @else
                        <td class="alert-danger">{{number_format($rate['cry'], 0, ',', '.')}}</td>
                    @endif
                    @if($rate['h2o'] < $rate['max_h2o'])
                        <td class="alert-success">{{number_format($rate['h2o'], 0, ',', '.')}}</td>
                    @else
                        <td class="alert-danger">{{number_format($rate['h2o'], 0, ',', '.')}}</td>
                    @endif
                    @if($rate['h2'] < $rate['max_h2'])
                        <td class="alert-success">{{number_format($rate['h2'], 0, ',', '.')}}</td>
                    @else
                        <td class="alert-danger">{{number_format($rate['h2'], 0, ',', '.')}}</td>
                    @endif
                </tr>
                @endforeach
                <tr style="border-top: 1px solid #000;">
                    <td class="title-line">Gesamt</td>
                    <td class="title-line">{{$sumAllPlanetRates['fe'] ? number_format(floor($sumAllPlanetRates['fe']), 0, ',', '.') : 0}}</td>
                    <td class="title-line">{{$sumAllPlanetRates['lut'] ? number_format(floor($sumAllPlanetRates['lut']), 0, ',', '.') : 0}}</td>
                    <td class="title-line">{{$sumAllPlanetRates['cry'] ? number_format(floor($sumAllPlanetRates['cry']), 0, ',', '.') : 0}}</td>
                    <td class="title-line">{{$sumAllPlanetRates['h2o'] ? number_format(floor($sumAllPlanetRates['h2o']), 0, ',', '.') : 0}}</td>
                    <td class="title-line">{{$sumAllPlanetRates['h2'] ? number_format(floor($sumAllPlanetRates['h2']), 0, ',', '.') : 0}}</td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
