<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <form>
                @csrf
                <div class="row">
                    <div class="col-12 col-md-3">
                        <label for="galaxy">Galaxie</label>
                    </div>
                    <div class="col-12 col-md-3">
                        <a href="/universe/{{$defaultPlanet}}/{{request()->galaxy - 1 < 1 ? 1 : request()->galaxy - 1 }}/{{request()->system}}">&lt;&lt;</a><input type="number" name="galaxy" id="galaxy" value="{{request()->galaxy}}" min="1"/><a href="/universe/{{$defaultPlanet}}/{{request()->galaxy + 1 > 30 ? 30 : request()->galaxy + 1 }}/{{request()->system}}">&gt;&gt;</a>
                    </div>
                    <div class="col-12 col-md-3">
                        <label for="system">System</label>
                    </div>
                    <div class="col-12 col-md-3">
                        <a href="/universe/{{$defaultPlanet}}/{{request()->galaxy}}/{{request()->system - 1 < 1 ? 1 : request()->system - 1 }}">&lt;&lt;</a><input type="number" name="system" id="system" value="{{request()->system}}" min="1"/><a href="/universe/{{$defaultPlanet}}/{{request()->galaxy}}/{{request()->system + 1 > 300 ? 300 : request()->system + 1 }}">&gt;&gt;</a>
                    </div>
                    <div class="col-12">
                        <button type="button" id="showSystem" class="btn btn-secondary" onclick="window.location.href='/universe/{{$defaultPlanet}}/'+document.getElementById('galaxy').value+'/'+document.getElementById('system').value">Anzeigen</button>
                    </div>
                </div>
            </form>
        </div>
        <div class="col-12 col-md-6 offset-md-3">
            @if (count($planets) > 0)
                <table style="width: 100%;">
                    <thead>
                    <tr>
                        <th colspan="4" class="title-line">System {{request()->galaxy . ':' . request()->system}}</th>
                    </tr>
                    <tr>
                        <th class="title-line">Nr.</th>
                        <th class="title-line">Planeten</th>
                        <th class="title-line">Punkte</th>
                        <th class="title-line">Aktionen</th>
                    </tr>
                    </thead>
                    @foreach($planets as $planet)
                        <tr>
                            @if($planet->username == null)
                                <td class="title-line">{{$planet->planet}}</td>
                                <td class="sub-line">--</td>
                                <td class="sub-line">0</td>
                                <td class="sub-line"></td>
                            @else
                                <td class="title-line">{{$planet->planet}}</td>
                                <td class="sub-line">
                                    {{$planet->planet_name != null ? $planet->planet_name : 'Unbenannter Planet'}}
                                    <a href="/profile/{{$planet->user_id}}">({{$planet->username}})</a>
                                    @if($planet->alliance_id != null)
                                        <a href="/alliance/{{$activePlanet}}/{{$planet->alliance_id}}">[{{$planet->alliance_tag}}]</a></td>
                                    @endif
                                <td class="sub-line">{{number_format($planet->points, 0, ',', '.')}}</td>
                                <td class="sub-line">
                                    @if($activePlanet != $planet->id)
                                        <a href="/mission/{{$activePlanet}}/withdata/{{$planet->galaxy}}/{{$planet->system}}/{{$planet->planet}}">[M]</a>
                                    @endif
                                </td>
                            @endif
                        </tr>
                    @endforeach
                </table>
            @else
                <div class="">
                    Unendliche Weiten...
                </div>
            @endif
        </div>
    </div>
</div>
