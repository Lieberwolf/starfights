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
                    @foreach($highscoreList as $key => $entry)
                        <tr>
                            <td class="title-line">{{($key+1)}}.</td>
                            <td class="sub-line"><a href="/profile/{{$entry->user_id}}">{{$entry->nickname}}</a></td>
                            <td class="sub-line">{{number_format($entry->Planetenpunkte, 0, ',', '.')}}</td>
                            <td class="sub-line">{{number_format($entry->Forschungspunkte, 0, ',', '.')}}</td>
                            <td class="sub-line">{{number_format(($entry->Planetenpunkte + $entry->Forschungspunkte), 0, ',', '.')}}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
