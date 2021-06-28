<div class="container">
    <div class="row">
        <div class="col-12 title-line">Notizblock</div>
        <form action="/notice/{{$activePlanet}}/edit" method="post" class="col-12">
            @csrf
            <div class="row">
                <div class="col-12 sub-line">
                    <div class="form-group">
                        <textarea class="form-control" name="notice" rows="4">{{$notice != false ? $notice->content : ''}}</textarea>
                    </div>
                </div>
                <div class="col-12 title-line">
                    <input type="submit" value="Speichern" class="btn btn-secondary"/>
                </div>
            </div>
        </form>
    </div>
</div>
