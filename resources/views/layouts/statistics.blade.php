@if($stats)
<div class="container-fluid">
    <div class="row">
        <div class="col-12 title-line">
            <span>Kampfstatistik von {{$mode == 'u' ? $stats->nickname : $stats->alliance_name}}</span>
        </div>
        @if($stats->public == 0)
        <div class="col-12 alert alert-danger mt-1">Diese Statistik ist privat.</div>
        @else
            <div class="col-12 title-line mt-4">Schiffe</div>
            <div class="col-6 title-line mt-1">Schiff</div>
            <div class="col-2 title-line mt-1">Vernichtet</div>
            <div class="col-2 title-line mt-1">Verloren</div>
            <div class="col-2 title-line mt-1">Bilanz</div>
            @if($stats->ship_types != null)
                @foreach(json_decode($stats->ship_types) as $ship)
                    <div class="col-6 sub-line">{{$ship->ship_name}}</div>
                    <div class="col-2 sub-line">{{number_format($ship->destroyed, 0, ',', '.')}}</div>
                    <div class="col-2 sub-line">{{number_format($ship->lost, 0, ',', '.')}}</div>
                    <div class="col-2 sub-line {{$ship->destroyed - $ship->lost < 0 ? 'text-danger' : ($ship->destroyed - $ship->lost > 0 ? 'text-success' : 'text-neutral')}}">{{number_format($ship->destroyed - $ship->lost, 0, ',', '.')}}</div>
                @endforeach
            @else
                <div class="col-12 sub-line">Nichts</div>
            @endif

            <div class="col-12 title-line mt-4">Verteidigungsanlagen</div>
            <div class="col-6 title-line mt-1">Verteidigungsanlage</div>
            <div class="col-2 title-line mt-1">Vernichtet</div>
            <div class="col-2 title-line mt-1">Verloren</div>
            <div class="col-2 title-line mt-1">Bilanz</div>
            @if($stats->turret_types != null)
                @foreach(json_decode($stats->turret_types) as $turret)
                    <div class="col-6 sub-line">{{$turret->turret_name}}</div>
                    <div class="col-2 sub-line">{{number_format($turret->destroyed, 0, ',', '.')}}</div>
                    <div class="col-2 sub-line">{{number_format($turret->lost, 0, ',', '.')}}</div>
                    <div class="col-2 sub-line {{$turret->destroyed - $turret->lost < 0 ? 'text-danger' : ($turret->destroyed - $turret->lost > 0 ? 'text-success' : 'text-neutral')}}">{{number_format($turret->destroyed - $turret->lost, 0, ',', '.')}}</div>
                @endforeach
            @else
                <div class="col-12 sub-line">Nichts</div>
            @endif

            <div class="col-12 title-line mt-4">Rohstoffe</div>
            <div class="col-6 title-line mt-1">Rohstoff</div>
            <div class="col-2 title-line mt-1">Erbeutet</div>
            <div class="col-2 title-line mt-1">Verloren</div>
            <div class="col-2 title-line mt-1">Bilanz</div>
            @if($stats->resources_types != null)
                @foreach(json_decode($stats->resources_types) as $resource)
                    <div class="col-6 sub-line">
                        @if($resource->res_id == 'fe')
                            Eisen
                        @endif
                        @if($resource->res_id == 'lut')
                            Lutinum
                        @endif
                        @if($resource->res_id == 'cry')
                            Kristalle
                        @endif
                        @if($resource->res_id == 'h2o')
                            Wasser
                        @endif
                        @if($resource->res_id == 'h2')
                            Wasserstoff
                        @endif
                    </div>
                    <div class="col-2 sub-line">{{number_format($resource->caught, 0, ',', '.')}}</div>
                    <div class="col-2 sub-line">{{number_format($resource->lost, 0, ',', '.')}}</div>
                    <div class="col-2 sub-line {{$resource->caught - $resource->lost < 0 ? 'text-danger' : ($resource->caught - $resource->lost > 0 ? 'text-success' : 'text-neutral')}}">{{number_format($resource->caught - $resource->lost, 0, ',', '.')}}</div>
                @endforeach
            @else
                <div class="col-12 sub-line">Nichts</div>
            @endif

            <div class="col-12 col-md-6 offset-md-3 title-line mt-4">Informationen</div>
            <div class="col-6 col-md-3 offset-md-3 sub-line">Anzahl Angriffe:</div>
            <div class="col-6 col-md-3 sub-line">{{$stats->attacks}}</div>
            <div class="col-6 col-md-3 offset-md-3 sub-line">Anzahl Verteidigungen:</div>
            <div class="col-6 col-md-3 sub-line">{{$stats->defends}}</div>
        @endif
    </div>
</div>
@else
<div class="container">
    <div class="row">
        <div class="col-12 alert alert-danger mt-1">Diese Statistik existiert (noch) nicht.</div>
    </div>
</div>
@endif
