<div class="container-fluid">
    <div class="row">
        <div class="col-12 title-line">
            <div class="row">
                <div class="col-12 col-md-4">
                    <a href="/messages/new/{{$activePlanet}}">Neue Nachrichten</a>
                </div>
                <div class="col-12 col-md-4">
                    <a href="/messages/inbox/{{$activePlanet}}">Posteingang</a>
                </div>
                <div class="col-12 col-md-4">
                    <a href="/messages/outbox/{{$activePlanet}}">Postausgang</a>
                </div>
            </div>
        </div>
        <form action="/messages/edit/inbox" method="post" style="width: 100%;">
        @csrf
        @foreach($messages as $message)
            <div class="single-message">
                @if($message->user_id != 0)
                    <div class="col-12">
                        <div class="row">
                            <div class="col-1 title-line">
                                <input type="checkbox" name="toBeDeleted[{{$message->id}}]"/>
                            </div>
                            <div class="col-6 title-line">
                                <a href="/profile/{{$message->user_id}}">{{$message->sender->username}}</a>&nbsp;<a href="/messages/send/{{$message->user_id}}">[Antworten]</a>
                            </div>
                            <div class="col-5 title-line">
                                {{date('Y-m-d H:i:s', $message->created_at->timestamp)}}
                            </div>
                            <div class="col-12 sub-line mb-0">
                                Betreff: {{$message->subject}}
                            </div>
                            <div class="col-12 sub-line">
                                {{$message->message}}
                            </div>
                        </div>
                    </div>
                @else
                    <div class="col-12">
                        <div class="row">
                            <div class="col-1 title-line">
                                <input type="checkbox" name="toBeDeleted[{{$message->id}}]"/>
                            </div>
                            <div class="col-6 title-line">
                                System
                            </div>
                            <div class="col-5 title-line">
                                {{date('Y-m-d H:i:s', $message->created_at->timestamp)}}
                            </div>
                            <div class="col-12 sub-line mb-0">
                                Betreff: {{$message->subject}}
                            </div>
                            <div class="col-12 sub-line">
                                {!! $message->message !!}
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        @endforeach
        @if(count($messages) == 0)
            <div class="col-12 sub-line">Keine Nachrichten vorhanden</div>
        @else
            <div class="col-12">
                <div class="row">
                    <div class="col-6 title-line mt-1 p-1">
                        <button class="btn btn-secondary" type="button" onclick="$('input[type=checkbox]').each(function(){$(this).prop('checked', true)});">Alle</button>
                    </div>
                    <div class="col-6 title-line mt-1 p-1">
                        <button class="btn btn-secondary" type="submit">Löschen</button>
                    </div>
                </div>
            </div>
        @endif
    </form>
    </div>
</div>
