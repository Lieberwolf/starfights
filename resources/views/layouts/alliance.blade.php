<div class="container-fluid">
    <div class="row">
        @if($alliance->id != null)
            <div class="col-12 title-line">Allianz "{{$alliance->alliance_name}}"</div>
            <div class="col-6 sub-line">Allianz Tag</div>
            <div class="col-6 sub-line">{{$alliance->alliance_tag}}</div>
            <div class="col-6 sub-line">Mitglieder</div>
            <div class="col-6 sub-line">{{$alliance->members}} (<a href="/alliance/{{$activePlanet}}/memberslist/{{$alliance->alliance_id}}">Liste</a>)</div>
            <div class="col-6 sub-line">Gründer</div>
            <div class="col-6 sub-line"><a href="/profile/{{$alliance->founder_id}}">{{$alliance->nickname}}</a></div>
            <div class="col-6 sub-line">Kampfstatistik</div>
            <div class="col-6 sub-line"><a href="/statistics/{{$activePlanet}}/ally/{{$alliance->alliance_id}}">ansehen</a></div>
            <div class="col-12 title-line mt-3">
                <span>Allianzlogo</span>
                @if($alliance->founder_id == Auth::id())
                <span style="cursor: pointer;" data-toggle="modal" data-target="#logoModal">[bearbeiten]</span>
                <div class="modal fade" id="logoModal" tabindex="-1" role="dialog" aria-labelledby="logoModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="logoModalLabel">Logo bearbeiten</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <form action="/alliance/{{$activePlanet}}/logo/{{$alliance->id}}" method="post">
                                    @csrf
                                    <div class="form-group row">
                                        <label for="logoUrl" class="col-md-4 col-form-label text-md-right">Logo URL:</label>

                                        <div class="col-md-8">
                                            <input value="{{$alliance->alliance_logo}}" id="logoUrl" type="text" class="form-control" name="logoUrl" required>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-6">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Abbrechen</button>
                                        </div>
                                        <div class="col-6">
                                            @if($alliance->alliance_logo != null)
                                            <a href="/alliance/{{$activePlanet}}/logo/{{$alliance->id}}/unset" class="btn btn-danger">Löschen</a>
                                            @endif
                                            <button type="submit" class="btn btn-primary">Speichern</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                @endif
            </div>
            <div class="col-12 sub-line">
                @if($alliance->alliance_logo != null)
                    <img class="img-fluid p-2" src="{{$alliance->alliance_logo}}" alt="{{$alliance->alliance_name}} Logo"/>
                    @else
                    - keins -
                @endif
            </div>
            <div class="col-12 title-line mt-3">
                <span>Allianzbeschreibung</span>
                @if($alliance->founder_id == Auth::id())
                <span style="cursor: pointer;" data-toggle="modal" data-target="#descriptionModal">[bearbeiten]</span>
                <div class="modal fade" id="descriptionModal" tabindex="-1" role="dialog" aria-labelledby="descriptionModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="descriptionModalLabel">Beschreibung bearbeiten</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <form action="/alliance/{{$activePlanet}}/description/{{$alliance->id}}" method="post">
                                    @csrf
                                    <div class="form-group">
                                        <div class="col-12">
                                            <textarea type="text" class="form-control" name="description" required>{{$alliance->alliance_description}}</textarea>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-6">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Abbrechen</button>
                                        </div>
                                        <div class="col-6">
                                            @if($alliance->alliance_description != null)
                                            <a href="/alliance/{{$activePlanet}}/description/{{$alliance->id}}/unset" class="btn btn-danger">Löschen</a>
                                            @endif
                                            <button type="submit" class="btn btn-primary">Speichern</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="modal-footer">
                                <i class="bi-info-circle"></i>
                                <a target="_blank" href="https://github.com/PheRum/laravel-bbcode/blob/master/src/BBCodeParser.php">BBCodes</a>
                            </div>
                        </div>
                    </div>
                </div>
                @endif
            </div>
            <div class="col-12 sub-line">
                <div class="row">
                    <div class="col-12 col-sm-10 offset-sm-1">
                        @if($alliance->alliance_description != null)
                            <span>
                                {!!$alliance->alliance_description_parsed!!}
                            </span>
                            @else
                            <span>- keine -</span>
                        @endif
                    </div>
                </div>
            </div>
            @if($alliance->own)
                <div class="col-12 title-line mt-3">Allianznachrichten</div>
                @if($alliance->alliance_messages != null)
                    @foreach(json_decode($alliance->alliance_messages) as $message)
                        <div class="col-6 sub-line text-left">{{date("H:i:s - d.m.Y", $message->date)}} - <a href="/profile/{{$message->user_id}}">{{$message->user_name}} sagt:</a></div>
                        <div class="col-6 sub-line text-right"></div>
                        <div class="col-12 sub-line text-left">{{{$message->message}}}</div>
                    @endforeach
                @else
                    <div class="col-12 sub-line">- keine -</div>
                @endif
                <div class="col-12 title-line mt-3">Neue Nachricht</div>
                <div class="col-12 sub-line">
                    <form class="col-12 col-sm-6 offset-sm-3" action="/alliance/{{$activePlanet}}/send/{{$alliance->alliance_id}}" method="post">
                        @csrf
                        <div class="form-group">
                            <textarea name="message" id="message" rows="10" class="form-control" style="margin: 1rem 0;"></textarea>
                            @error('message')
                            <span class="ui-state-error" role="alert">
                                <strong>{{ $message }}</strong>
                            </span><br/>
                            @enderror
                            <button type="submit" class="btn btn-secondary">Absenden</button>
                        </div>
                    </form>
                </div>
                @if($alliance->founder_id != $userData->user_id)
                    <div class="col-12 sub-line">
                        <a href="/alliance/{{$activePlanet}}/leave/{{$alliance->id}}" class="text-danger p1">Austreten</a>
                    </div>
                @endif
                @if($alliance->founder_id == $userData->user_id)
                    <div class="col-6 sub-line p-1">Bewerbungen:</div>
                    <div class="col-6 sub-line p-1">
                        @if(count($applications) > 0)
                            <button class="btn btn-secondary" type="button" data-toggle="collapse" data-target="#applicationCollapse" aria-expanded="false" aria-controls="applicationCollapse">({{count($applications)}}) Anzeigen</button>
                        @else
                            <span>- keine -</span>
                        @endif
                    </div>
                    @if(count($applications) > 0)
                        <div class="col-12 collapse pb-2" id="applicationCollapse">
                            <div class="row">
                                @foreach($applications as $application)
                                <div class="col-6 sub-line mt-1 p-1">
                                    <span><a href="/profile/{{$application->user_id}}">{{$application->nickname}}</a></span>
                                </div>
                                <div class="col-6 sub-line mt-1 p-1">
                                    <div class="row">
                                        <form class="col-md-6" action="/alliance/{{$activePlanet}}/accept/{{$alliance->id}}/{{$application->user_id}}" method="post">
                                            @csrf
                                            <button type="submit" class="btn btn-success">Annehmen</button>
                                        </form>
                                        <form class="col-md-6" action="/alliance/{{$activePlanet}}/decline/{{$alliance->id}}/{{$application->user_id}}" method="post">
                                            @csrf
                                            <button type="submit" class="btn btn-danger">Ablehnen</button>
                                        </form>
                                    </div>
                                </div>
                                @endforeach
                            </div>

                        </div>
                    @endif
                    <div class="col-12 sub-line">
                        <a href="/alliance/{{$activePlanet}}/delete/{{$alliance->id}}" class="text-danger p1">Allianz auflösen</a>
                    </div>
                @endif
            @else
                @if(!$userData->alliance_id && $userData->alliance_application == null)
                    <div class="col-12 sub-line">
                        <form action="/alliance/{{$activePlanet}}/apply/{{$alliance->id}}" method="post">
                            @csrf
                            <button type="submit" class="btn btn-success">Bewerben</button>
                        </form>
                    </div>
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
                    <button type="submit" class="btn btn-secondary">Weiter</button>
                </form>
            </div>
        @endif
    </div>
</div>
