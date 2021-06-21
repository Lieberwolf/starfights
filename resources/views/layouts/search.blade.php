<div class="container">
    <div class="row">
        <div class="col-12 title-line">
            <span>Suche</span>
        </div>
        <div class="col-12 sub-line mt-1">
            <form action="/search/{{$activePlanet}}/mode" method="post">
                @csrf
                <div class="form-check">
                    <input {{$searchResult && $searchResult->mode == 'p' ? 'checked' : ''}} class="form-check-input" type="radio" name="mode" id="targetRadios1" value="p">
                    <label class="form-check-label" for="targetRadios1">Spieler</label>
                </div>
                <div class="form-check">
                    <input {{$searchResult && $searchResult->mode == 'a' ? 'checked' : ''}} class="form-check-input" type="radio" name="mode" id="targetRadios2" value="a">
                    <label class="form-check-label" for="targetRadios2">Allianz Name</label>
                </div>
                <div class="form-check">
                    <input {{$searchResult && $searchResult->mode == 't' ? 'checked' : ''}} class="form-check-input" type="radio" name="mode" id="targetRadios3" value="t">
                    <label class="form-check-label" for="targetRadios2">Allianz Tag</label>
                </div>
                <div class="form-check">
                    <input {{$searchResult && $searchResult->mode == 'n' ? 'checked' : ''}} class="form-check-input" type="radio" name="mode" id="targetRadios4" value="n">
                    <label class="form-check-label" for="targetRadios1">Planeten Name</label>
                </div>
                <div class="form-group">
                    <label for="searchTerm">Suchbegriff</label>
                    <input id="searchTerm" type="text" class="form-control" placeholder="Suchbegriff" name="term" value="{{$searchResult ? $searchResult->term : ''}}" />
                    @error('term')
                    <div class="ui-state-error alert alert-danger mt-1" role="alert">
                        <strong>{{ $message }}</strong>
                    </div>
                    @enderror
                </div>
                <div class="col-12 sub-line p-2">
                    <button type="submit" class="btn btn-primary">Suchen</button>
                </div>
            </form>
        </div>
        @if($searchResult)
            <div class="col-12 mt-2 title-line">
                <span>Suchergebnisse für '{{$searchResult->term}}'</span>
            </div>
            @if($searchResult->result)
                @if($searchResult->mode == 'p')
                    @foreach($searchResult->result as $key => $result)
                        <div class="col-2 sub-line mt-1">
                            <span>{{$key+1}}.</span>
                        </div>
                        <div class="col-10 sub-line mt-1">
                            <a href="/profile/{{$result->user_id}}">{{$result->nickname}}</a>
                        </div>
                    @endforeach
                @endif
                @if($searchResult->mode == 'n')
                    @foreach($searchResult->result as $key => $result)
                    <div class="col-2 sub-line mt-1">
                        <span>{{$key+1}}.</span>
                    </div>
                    <div class="col-10 sub-line mt-1">
                        <a href="/universe/{{$activePlanet}}/{{$result->galaxy}}/{{$result->system}}">{{$result->planet_name}}</a>
                    </div>
                    @endforeach
                @endif
                @if($searchResult->mode == 'a' || $searchResult->mode == 't')
                    @foreach($searchResult->result as $key => $result)
                    <div class="col-2 sub-line mt-1">
                        <span>{{$key+1}}.</span>
                    </div>
                    <div class="col-10 sub-line mt-1">
                        <a href="/alliance/{{$activePlanet}}/{{$result->id}}">{{$result->alliance_name}}</a>
                    </div>
                    @endforeach
                @endif
            @endif
        @endif
    </div>
</div>
