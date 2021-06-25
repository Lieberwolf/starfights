<div class="container">
    <div class="row">
        <div class="col-12 title-line">Allianz Gründen</div>
        <div class="col-12 sub-line">
            <form action="/alliance/{{$activePlanet}}/found" method="post">
                @csrf
                <div class="form-group row">
                    <label class="col-6" for="alliance_name">Allianzname</label>
                    <input type="text" name="alliance_name" class="col-6 form-control" id="alliance_name"/>
                    @error('name')
                    <span class="ui-state-error" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
                <div class="form-group row">
                    <label class="col-6" for="alliance_tag">Allianz Tag</label>
                    <input type="text" name="alliance_tag" class="col-6 form-control" id="alliance_tag" minlength="1" maxlength="5"/>
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
