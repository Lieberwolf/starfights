<div class="container-fluid">
    <div class="row">
        <div class="col-12 title-line">Produktionsübersicht</div>
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
                    @if($rate['rate_fe'] > 0)
                        <td class="alert-success">{{number_format($rate['rate_fe'], 2, ',', '.')}}</td>
                    @elseif($rate['rate_fe'] < 0)
                        <td class="alert-danger">{{number_format($rate['rate_fe'], 2, ',', '.')}}</td>
                    @else
                        <td class="alert-info">0</td>
                    @endif
                    @if($rate['rate_lut'] > 0)
                        <td class="alert-success">{{number_format($rate['rate_lut'], 2, ',', '.')}}</td>
                    @elseif($rate['rate_lut'] < 0)
                        <td class="alert-danger">{{number_format($rate['rate_lut'], 2, ',', '.')}}</td>
                    @else
                        <td class="alert-info">0</td>
                    @endif
                    @if($rate['rate_cry'] > 0)
                        <td class="alert-success">{{number_format($rate['rate_cry'], 2, ',', '.')}}</td>
                    @elseif($rate['rate_cry'] < 0)
                        <td class="alert-danger">{{number_format($rate['rate_cry'], 2, ',', '.')}}</td>
                    @else
                        <td class="alert-info">0</td>
                    @endif
                    @if($rate['rate_h2o'] > 0)
                        <td class="alert-success">{{number_format($rate['rate_h2o'], 2, ',', '.')}}</td>
                    @elseif($rate['rate_h2o'] < 0)
                        <td class="alert-danger">{{number_format($rate['rate_h2o'], 2, ',', '.')}}</td>
                    @else
                        <td class="alert-info">0</td>
                    @endif
                    @if($rate['rate_h2'] > 0)
                        <td class="alert-success">{{number_format($rate['rate_h2'], 2, ',', '.')}}</td>
                    @elseif($rate['rate_h2'] < 0)
                        <td class="alert-danger">{{number_format($rate['rate_h2'], 2, ',', '.')}}</td>
                    @else
                        <td class="alert-info">0</td>
                    @endif
                </tr>
                @endforeach
                <tr style="border-top: 1px solid #000;">
                    <td class="title-line">Produktion Stunde</td>
                    <td class="title-line">{{$sumAllPlanetRates['rate_fe'] ? number_format(floor($sumAllPlanetRates['rate_fe']), 0, ',', '.') : 0}}</td>
                    <td class="title-line">{{$sumAllPlanetRates['rate_lut'] ? number_format(floor($sumAllPlanetRates['rate_lut']), 0, ',', '.') : 0}}</td>
                    <td class="title-line">{{$sumAllPlanetRates['rate_cry'] ? number_format(floor($sumAllPlanetRates['rate_cry']), 0, ',', '.') : 0}}</td>
                    <td class="title-line">{{$sumAllPlanetRates['rate_h2o'] ? number_format(floor($sumAllPlanetRates['rate_h2o']), 0, ',', '.') : 0}}</td>
                    <td class="title-line">{{$sumAllPlanetRates['rate_h2'] ? number_format(floor($sumAllPlanetRates['rate_h2']), 0, ',', '.') : 0}}</td>
                </tr>
                <tr>
                    <td class="title-line">Produktion Tag</td>
                    <td class="title-line">{{$sumAllPlanetRates['rate_fe'] ? number_format(floor($sumAllPlanetRates['rate_fe'] * 24), 0, ',', '.') : 0}}</td>
                    <td class="title-line">{{$sumAllPlanetRates['rate_lut'] ? number_format(floor($sumAllPlanetRates['rate_lut'] * 24), 0, ',', '.') : 0}}</td>
                    <td class="title-line">{{$sumAllPlanetRates['rate_cry'] ? number_format(floor($sumAllPlanetRates['rate_cry'] * 24), 0, ',', '.') : 0}}</td>
                    <td class="title-line">{{$sumAllPlanetRates['rate_h2o'] ? number_format(floor($sumAllPlanetRates['rate_h2o'] * 24), 0, ',', '.') : 0}}</td>
                    <td class="title-line">{{$sumAllPlanetRates['rate_h2'] ? number_format(floor($sumAllPlanetRates['rate_h2'] * 24), 0, ',', '.') : 0}}</td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
