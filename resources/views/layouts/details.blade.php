<div class="container-fluid">
    <div class="row">
        <div class="col-12 title-line">
            @if($prevPlanet)
            <span>
                <a href="/details/{{$prevPlanet->id}}"><<</a>
            </span>
            @endif
            <span>Planeten Informationen zu <a href="/universe/{{$activePlanet}}/{{$planetInfo->galaxy}}/{{$planetInfo->system}}">{{$planetInfo->galaxy . ':' . $planetInfo->system . ':' . $planetInfo->planet}}</a></span>
            @if($nextPlanet)
            <span>
                <a href="/details/{{$nextPlanet->id}}">>></a>
            </span>
            @endif
        </div>
        <div class="col-6 sub-line">
            Durchmesser
        </div>
        <div class="col-6 sub-line">
            {{number_format($planetInfo->diameter, 0, ',', '.')}} KM
        </div>
        <div class="col-6 sub-line">
            Durchschnittliche Temperatur
        </div>
        <div class="col-6 sub-line">
            {{$planetInfo->temperature}}
        </div>
        <div class="col-6 sub-line">
            Atmosphäre
        </div>
        <div class="col-6 sub-line">
            {{$planetInfo->atmosphere}}
        </div>
        <div class="col-6 sub-line">
            Resourcen Bonus
        </div>
        <div class="col-6 sub-line">
            {{$planetInfo->resource_bonus}}
        </div>
        <div class="col-12 title-line" style="margin-top: 30px;">
            Planeten Daten
        </div>
        <div class="col-12 col-md-6 sub-line">
            Planeten Name:
        </div>
        <div class="col-12 col-md-6 sub-line">
            <form action="/details/{{$activePlanet}}/name" method="post">
                @csrf
                <input type="text" value="{{$planetInfo->planet_name}}" class="form-control" name="planet_name"/>
                <input type="submit" class="btn btn-secondary" value="Speichern"/>
            </form>
        </div>

        <div class="col-12 col-md-6 sub-line">
            Planeten Bild (URL):
        </div>
        <div class="col-12 col-md-6 sub-line">
            @if(!$planetInfo->image)
            <form action="/details/{{$activePlanet}}/image" method="post">
                @csrf
                <input type="text" value="{{$planetInfo->image}}" class="form-control" name="image"/>
                <input type="submit" class="btn btn-secondary" value="Speichern"/>
            </form>
            @else
            <form action="/details/{{$activePlanet}}/image" method="post">
                @csrf
                <input type="text" value="{{$planetInfo->image}}" class="form-control" name="image"/>
                <input type="submit" class="btn btn-secondary" value="Speichern"/>
            </form>
            <form action="/details/{{$activePlanet}}/deleteImage" method="post">
                @csrf
                <input type="submit" class="btn btn-warning" value="Löschen"/>
            </form>
            @endif
        </div>
        <div class="col-12 col-md-6 mt-4 sub-line">
            Planet löschen:
        </div>
        <div class="col-12 col-md-6 mt-4 sub-line">
            @if($activePlanet != $startPlanet[0]->start_planet)
            <form action="/details/{{$activePlanet}}/delete" method="post">
                @csrf
                <button type="submit" class="btn btn-warning">Löschen</button>
            </form>
            @else
            <span>Startplaneten können nicht gelöscht werden.</span>
            @endif
        </div>
    </div>
</div>
