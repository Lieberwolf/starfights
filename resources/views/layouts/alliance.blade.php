<div class="container">
    <div class="row">
        @if($alliance != null)
            <div class="col-12 title-line">Allianz "{{$alliance->alliance_name}}"</div>
            <div class="col-6 sub-line" style="margin-top: 1px;">Mitglieder</div>
            <div class="col-6 sub-line" style="margin-top: 1px;">{{$alliance->members}} (<a href="/alliance/{{$activePlanet}}/memberslist/{{$alliance->alliance_id}}">Liste</a>)</div>
            <div class="col-6 sub-line" style="margin-top: 1px;">Gründer</div>
            <div class="col-6 sub-line" style="margin-top: 1px;"><a href="/profile/{{$alliance->founder_id}}">{{$alliance->nickname}}</a></div>
            <div class="col-12 title-line" style="margin-top: 1px;">Allianzlogo</div>
            <div class="col-12 sub-line" style="margin-top: 1px;">
                @if($alliance->alliance_logo != null)
                    <img class="img-fluid" src="{{$alliance->alliance_logo}}" alt="{{$alliance->alliance_name}} Logo"/>
                    @else
                    - keins -
                @endif
            </div>
            <div class="col-12 title-line" style="margin-top: 1px;">Allianzbeschreibung</div>
            <div class="col-12 sub-line" style="margin-top: 1px;">
                <div class="row">
                    <div class="col-12 col-sm-10 offset-sm-1">
                        @if($alliance->alliance_description != null)
                            <span>
                                {{$alliance->alliance_description}}
                            </span>
                            @else
                            <span>- keine -</span>
                        @endif
                    </div>
                </div>
            </div>
            @if($alliance->own)
                <div class="col-12 title-line" style="margin-top: 1px;">Allianznachrichten</div>
                @if($alliance->alliance_messages != null)
                    <!-- foreach -->
                    @else
                    <div class="col-12 sub-line" style="margin-top: 1px;">- keine -</div>
                @endif
                <div class="col-12 title-line" style="margin-top: 1px;">Neue Nachricht</div>
                <div class="col-12 sub-line">
                    <form class="col-12 col-sm-6 offset-sm-3" action="/alliance/send" method="post">
                        <div class="form-group">
                            <textarea name="message" id="message" rows="10" class="form-control" style="margin: 1rem 0;"></textarea>
                            <button type="submit" class="btn btn-primary">Absenden</button>
                        </div>
                    </form>
                </div>
                @else
                @if(!$userData->alliance_id)
                    <div class="col-12 sub-line"><a href="/alliance/apply/{{$alliance->id}}">Bewerben</a></div>
                @endif
            @endif


            @else
            <div class="col-12 sub-line">
                <form method="post" action="/alliance/{{$activePlanet}}/option">
                    @csrf
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="target" id="targetRadios1" value="new">
                        <label class="form-check-label" for="targetRadios1">Neue Allianz gründen</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="target" id="targetRadios2" value="search">
                        <label class="form-check-label" for="targetRadios2">Allianz suchen</label>
                    </div>
                    <button type="submit" class="btn btn-primary">Weiter</button>
                </form>
            </div>
        @endif
    </div>
</div>
