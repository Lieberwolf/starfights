<div class="">
    <a href="/messages/new">Neue Nachrichten</a>
    <a href="/messages/inbox">Posteingang</a>
    <a href="/messages/outbox">Postausgang</a>
    <form action="/messages/edit/inbox" method="post">
        @csrf
        @foreach($messages as $message)
            <div class="single-message">
                @if($message->user_id != 0)
                    <table>
                        <thead>
                        <tr>
                            <th><input type="checkbox" name="toBeDeleted[{{$message->id}}]"/></th>
                            <th colspan="2"><a href="/profile/{{$message->user_id}}">{{$message->sender->username}}</a></th>
                            <th>{{date('Y-m-d H:i:s', $message->created_at->timestamp)}}</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td colspan="3">
                                {{$message->subject}}
                            </td>
                        </tr>
                        <tr>
                            <td colspan="3">
                                {{$message->message}}
                            </td>
                        </tr>
                        </tbody>
                    </table>
                @else
                    <table>
                        <thead>
                        <tr>
                            <th><input type="checkbox" name="toBeDeleted[{{$message->id}}]"/></th>
                            <th colspan="2">System</th>
                            <th>{{date('Y-m-d H:i:s', $message->created_at->timestamp)}}</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td colspan="3">
                                {{$message->subject}}
                            </td>
                        </tr>
                        <tr>
                            <td colspan="3">
                                {!! $message->message !!}
                            </td>
                        </tr>
                        </tbody>
                    </table>
                @endif
            </div>
        @endforeach
        <button class="btn btn-default" type="submit">Löschen</button>
    </form>
</div>
