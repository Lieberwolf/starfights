<div class="">
    <h4>Nachricht an "{{$receiver->username}}"</h4>
    <form action="/messages/sending" method="post">
        @csrf
        <input type="text" name="subject" placeholder="subject"/><br/>
        <textarea type="text" name="message" placeholder="message"></textarea>
        @error('message')
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
        @enderror
        <br/>
        <input type="hidden" name="receiver_id" value="{{$receiver->id}}"/>
        <button type="submit">Abschicken</button>
    </form>
</div>
