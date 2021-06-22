<div class="container">
    <div class="row">
        <div class="col-12 title-line">Account "{{$profileData->nickname}}"</div>
        <table style="width: 100%;">
            <thead>
            <tr>
                <th class="title-line">Nr.</th>
                <th class="title-line">Koordinaten</th>
                <th class="title-line">Planet</th>
                <th class="title-line">Punkte</th>
                <th class="title-line">Aktionen</th>
            </tr>
            </thead>
            <tbody>
            @foreach($profileData->planetsList as $key => $planet)
                <tr>
                    <td class="title-line">{{$key + 1}}</td>
                    <td class="sub-line">
                        <a href="/universe/{{$activePlanet}}/{{$planet->galaxy}}/{{$planet->system}}">
                            {{$planet->galaxy . ':' . $planet->system . ':' . $planet->planet}}
                        </a>
                    </td>
                    <td class="sub-line">{{$planet->data->planet_name != null ? $planet->data->planet_name : 'Unbenannter Planet'}}</td>
                    <td class="sub-line">{{number_format($planet->points, 0, ',', '.')}}</td>
                    <td class="sub-line">
                        @if($activePlanet != $planet->id)
                            <a href="/mission/{{$activePlanet}}/withdata/{{$planet->galaxy}}/{{$planet->system}}/{{$planet->planet}}">[M]</a>
                        @endif
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
        <div class="col-12 col-md-6 offset-md-3 title-line" style="margin-top: 30px;">Accountinformationen</div>
        <div class="col-12 col-md-6 offset-md-3 sub-line">
            Angemeldet seit: {{date('d.m.Y H:i:s', $profileData->created_at->timestamp)}}<br/>
        </div>
        <div class="col-12 col-md-6 offset-md-3 sub-line">
            Accountpunkte: {{number_format($totalPoints, 0, ',', '.')}}
        </div>
        @if($alliance->id != null)
        <div class="col-12 col-md-6 offset-md-3 sub-line">
            Allianz: <a href="/alliance/{{$activePlanet}}/{{$alliance->id}}">{{$alliance->alliance_name}} [{{$alliance->alliance_tag}}]</a>
        </div>
        @endif
        <div class="col-12 col-md-6 offset-md-3 sub-line">
            <a href="/statistics/{{$activePlanet}}/user/{{$profileData->user_id}}">Kampfstatistik</a>
        </div>
        @if(!$ownProfile)
            <div class="col-12 col-md-6 offset-md-3 sub-line">
                <a href="/messages/send/{{$profileData->user_id}}">Nachricht senden</a>
            </div>
        @endif
    </div>
</div>
