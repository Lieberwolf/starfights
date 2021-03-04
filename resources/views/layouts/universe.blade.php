<div class="">

    <form>
        @csrf
        <label for="galaxy">Galaxie</label>
        <a href="/universe/{{$defaultPlanet}}/{{request()->galaxy - 1 < 1 ? 1 : request()->galaxy - 1 }}/{{request()->system}}">&lt;&lt;</a><input type="number" name="galaxy" id="galaxy" value="{{request()->galaxy}}" min="1"/><a href="/universe/{{$defaultPlanet}}/{{request()->galaxy + 1 > 30 ? 30 : request()->galaxy + 1 }}/{{request()->system}}">&gt;&gt;</a>
        <label for="system">System</label>
        <a href="/universe/{{$defaultPlanet}}/{{request()->galaxy}}/{{request()->system - 1 < 1 ? 1 : request()->system - 1 }}">&lt;&lt;</a><input type="number" name="system" id="system" value="{{request()->system}}" min="1"/><a href="/universe/{{$defaultPlanet}}/{{request()->galaxy}}/{{request()->system + 1 > 300 ? 300 : request()->system + 1 }}">&gt;&gt;</a>
        <button type="button" id="showSystem" class="btn btn-primary" onclick="window.location.href='/universe/{{$defaultPlanet}}/'+document.getElementById('galaxy').value+'/'+document.getElementById('system').value">Anzeigen</button>
    </form>

    @if (count($planets) > 0)
        <table>
            <thead>
            <tr>
                <th colspan="3">System {{request()->galaxy . ':' . request()->system}}</th>
            </tr>
            <tr>
                <th>Nr.</th>
                <th>Planeten</th>
                <th>Punkte</th>
            </tr>
            </thead>
            @foreach($planets as $planet)
                <tr>
                    @if($planet->username == null)
                    <td>{{$planet->planet}}</td>
                    <td>--</td>
                    <td>0</td>
                    @else
                        <td>{{$planet->planet}}</td>
                        <td>{{$planet->planet_name != null ? $planet->planet_name : 'Unbenannter Planet'}} <a href="/profile/{{$planet->user_id}}">{{$planet->username}}</a></td>
                        <td>{{number_format($planet->points, 0, ',', '.')}}</td>
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
