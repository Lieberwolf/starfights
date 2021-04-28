<div class="container">
    <div class="row">
        <div class="col-12 title-line">Allianz Gründen</div>
        <div class="col-12 sub-line">
            <form action="/alliance/found/{{$activePlanet}}" method="post">
                @csrf
                <div class="form-group row">
                    <label class="col-6" for="name">Allianzname</label>
                    <input type="text" name="name" class="col-6 form-control" id="name"/>
                    @error('name')
                    <span class="ui-state-error" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
                <div class="form-group row">
                    <label class="col-6" for="name">Allianz Tag</label>
                    <input type="text" name="tag" class="col-6 form-control" id="name" minlength="1" maxlength="5"/>
                    @error('tag')
                    <span class="ui-state-error" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
                <button type="submit" class="btn btn-primary">Gründen!</button>
            </form>
        </div>
    </div>
</div>
