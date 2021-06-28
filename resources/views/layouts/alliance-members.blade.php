<div class="container-fluid">
    <div class="row">
        <div class="col-12 title-line">Mitglieder von "{{$allianceData->alliance_name}}"</div>
        <table style="width: 100%;margin-top: 1px;">
            <thead>
                <tr class="title-line">
                    <th></th>
                    <th>Name</th>
                    <th>Forschung-</th>
                    <th>Planeten-</th>
                    <th>Gesamtpunkte</th>
                </tr>
            </thead>
            <tbody>
            @foreach($members as $key => $member)
            <tr class="sub-line" style="margin-top: 1px;">
                <td>{{($key+1)}}.</td>
                <td><a href="/profile/{{$member->user_id}}">{{$member->nickname}}</a></td>
                <td>{{number_format($member->researchPoints, 0, ',', '.')}}</td>
                <td>{{number_format($member->planetPoints, 0, ',', '.')}}</td>
                <td>{{number_format($member->totalPoints, 0, ',', '.')}}</td>
            </tr>
            @endforeach
            </tbody>
        </table>

    </div>
</div>
