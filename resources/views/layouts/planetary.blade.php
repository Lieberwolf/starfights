<div class="container-fluid">
    <div class="row">
        <div class="col-12 title-line">Planetenübersicht</div>
        <table style="width: 100%;">
            <thead>
            <tr class="title-line">
                <th>Planet</th>
                <th>Konstruktion</th>
                <th>Forschung</th>
                <th>Schiffswerft</th>
                <th>Verteidigung</th>
            </tr>
            </thead>
            <tbody>
            @foreach($allUserPlanets as $key => $planet)
                <tr>
                    <td class="title-line">
                        <a href="/overview/{{$planet->id}}">{{$planet->galaxy}}:{{$planet->system}}:{{$planet->planet}}</a>
                    </td>
                    {{--
                    @if($buildings[$key] != null)
                        <td class="sub-line">
                            <span class="js-add-countdown" data-seconds-to-count="{{strtotime($buildings[$key]->finished_at) - now()->timestamp}}"></span>
                        </td>
                        @else
                        <td class="sub-line"></td>
                    @endif
                    @if($research[$key] != null)
                        <td class="sub-line">
                            <span class="js-add-countdown" data-seconds-to-count="{{strtotime($research[$key]->finished_at) - now()->timestamp}}"></span>
                        </td>
                    @else
                        <td class="sub-line"></td>
                    @endif

                    @if($planet->nextShip && $planet->nextShip != null)
                        <td class="sub-line">
                            <span class="js-add-countdown" data-seconds-to-count="{{$planet->nextShip->seconds}}"></span>
                        </td>
                    @else
                        <td class="sub-line"></td>
                    @endif
                    @if($planet->nextTurret && $planet->nextTurret != null)
                        <td class="sub-line">
                            <span class="js-add-countdown" data-seconds-to-count="{{$planet->nextTurret->seconds}}"></span>
                        </td>
                    @else
                        <td class="sub-line"></td>
                    @endif
                    --}}
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</div>
