<div class="container-fluid">
    <div class="row">
        <div class="col-12 col-md-4 offset-md-4">
            <form id="treeTarget">
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="target" id="targetRadios1" value="/techtree/buildings/{{$activePlanet}}">
                    <label class="form-check-label" for="targetRadios1">Gebäude</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="target" id="targetRadios2" value="/techtree/research/{{$activePlanet}}">
                    <label class="form-check-label" for="targetRadios2">Forschungen</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="target" id="targetRadios3" value="/techtree/ships/{{$activePlanet}}">
                    <label class="form-check-label" for="targetRadios3">Schiffe</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="target" id="targetRadios4" value="/techtree/turrets/{{$activePlanet}}">
                    <label class="form-check-label" for="targetRadios4">Verteidigung</label>
                </div>
                <input type="button" class="btn btn-secondary" value="Anzeigen" onclick="window.location.href=document.querySelector('#treeTarget input[type=radio]:checked').value"/>
            </form>
        </div>
    </div>
</div>
