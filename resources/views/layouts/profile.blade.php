<div class="">
    <div class="">
        <h4>Account "{{$profileData->nickname}}"</h4>
        <div class="">
            Angemeldet seit: {{date('d.m.Y H:i:s', $profileData->created_at->timestamp)}}<br/>
        </div>
        <div class="">
            <table>
                <thead>
                <tr>
                    <th>Nr.</th>
                    <th>Planet</th>
                    <th>Punkte</th>
                </tr>
                </thead>
                <tbody>
                @foreach($profileData->planetsList as $key => $planet)
                    <tr>
                        <td>{{$key + 1}}</td>
                        <td>{{$planet->data->planet_name != null ? $planet->data->planet_name : 'Unbenannter Planet'}}</td>
                        <td>{{number_format($planet->points, 0, ',', '.')}}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @if(!$ownProfile)
        <a href="/messages/send/{{$profileData->user_id}}">Nachricht senden</a>
    @endif
</div>
