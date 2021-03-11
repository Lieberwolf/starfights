<div class="container">
    <div class="row">
        <div class="col-12 col-md-10 offset-md-1 title-line">Flottenliste</div>
        @if($fleetsOnMission)
            <div class="col-6 col-md-2 offset-md-1 sub-line">Startplanet</div>
            <div class="col-6 col-md-2 sub-line">Zielplanet</div>
            <div class="col-6 col-md-2 sub-line">Missionstyp</div>
            <div class="col-6 col-md-2 sub-line">Ankunft</div>
            <div class="col-6 col-md-2 sub-line">Aktionen</div>
            @foreach($fleetsOnMission as $fleets)
                @foreach($fleets as $fleet)
                    <div class="col-6 col-md-2 offset-md-1 sub-line">
                        {{$fleet->readableSource->galaxy}}:{{$fleet->readableSource->system}}:{{$fleet->readableSource->planet}}
                    </div>
                    <div class="col-6 col-md-2 sub-line">
                        {{$fleet->readableTarget->galaxy}}:{{$fleet->readableTarget->system}}:{{$fleet->readableTarget->planet}}
                    </div>
                    <div class="col-6 col-md-2 sub-line">
                        {{$fleet->mission == 1 ? 'Stationierung' : ($fleet->mission == 2 ? 'Transport' : ($fleet->mission == 3 ? 'Spionage' : ($fleet->mission == 4 ? 'Delta Scan' : ($fleet->mission == 5 ? 'Kolonisierung' : ($fleet->mission == 6 ? 'Angriff' : ($fleet->mission == 7 ? 'Invasion' : ''))))))}}
                    </div>
                    <div class="col-6 col-md-2 sub-line">
                        <span class="js-add-countdown" data-seconds-to-count="{{strtotime($fleet->arrival) - now()->timestamp}}"></span> - [{{date('H:i:s', strtotime($fleet->arrival))}}]
                    </div>
                    <div class="col-6 col-md-2 sub-line">
                        <a href="" style="display: none;">abbrechen</a>
                    </div>
                @endforeach
            @endforeach
        @endif
    </div>
</div>
