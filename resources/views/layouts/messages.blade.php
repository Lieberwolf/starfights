<div class="container">
    <div class="row">
        <div class="col-12 title-line">
            <div class="row">
                <div class="col-12 col-md-4">
                    <a href="/messages/new">Neue Nachrichten</a>
                </div>
                <div class="col-12 col-md-4">
                    <a href="/messages/inbox">Posteingang</a>
                </div>
                <div class="col-12 col-md-4">
                    <a href="/messages/outbox">Postausgang</a>
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
                            <div class="col-1">
                                <input type="checkbox" name="toBeDeleted[{{$message->id}}]"/>
                            </div>
                            <div class="col-6">
                                <a href="/profile/{{$message->user_id}}">{{$message->sender->username}}</a>&nbsp;<a href="/messages/send/{{$message->user_id}}">[Antworten]</a>
                            </div>
                            <div class="col-5">
                                {{date('Y-m-d H:i:s', $message->created_at->timestamp)}}
                            </div>
                            <div class="col-12">
                                Betreff: {{$message->subject}}
                            </div>
                            <div class="col-12">
                                {{$message->message}}
                            </div>
                        </div>
                    </div>
                @else
                    <div class="col-12">
                        <div class="row">
                            <div class="col-1">
                                <input type="checkbox" name="toBeDeleted[{{$message->id}}]"/>
                            </div>
                            <div class="col-6">
                                System
                            </div>
                            <div class="col-5">
                                {{date('Y-m-d H:i:s', $message->created_at->timestamp)}}
                            </div>
                            <div class="col-12">
                                Betreff: {{$message->subject}}
                            </div>
                            <div class="col-12">
                                {!! $message->message !!}
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        @endforeach
        <button class="btn btn-default" type="submit">Löschen</button>
    </form>
    </div>
</div>
