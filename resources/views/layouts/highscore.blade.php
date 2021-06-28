<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <table class="highscore-table">
                <thead>
                    <tr>
                        <th class="title-line">Nr.</th>
                        <th class="title-line">Name</th>
                        <th class="title-line">Planetenpunkte</th>
                        <th class="title-line">Forschungspunkte</th>
                        <th class="title-line">Gesamtpunkte</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $key => $user)
                        <tr>
                            <td class="title-line">{{($key+1)}}.</td>
                            <td class="sub-line"><a href="/profile/{{$user->user_id}}">{{$user->nickname}}</a></td>
                            <td class="sub-line">{{number_format($user->planetPoints, 0, ',', '.')}}</td>
                            <td class="sub-line">{{number_format($user->researchPoints, 0, ',', '.')}}</td>
                            <td class="sub-line">{{number_format($user->totalPoints, 0, ',', '.')}}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
