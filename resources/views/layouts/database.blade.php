<div class="container-fluid">
    <div class="row">
        <div class="col-12 title-line">
            <span>{{ __('database.database') }}</span>
        </div>
        <div class="col-12 sub-line">
            <form id="treeTarget">
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="target" id="targetRadios1" value="/database/buildings/{{$activePlanet}}">
                    <label class="form-check-label" for="targetRadios1">{{ __('database.buildings') }}</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="target" id="targetRadios2" value="/database/research/{{$activePlanet}}">
                    <label class="form-check-label" for="targetRadios2">{{ __('database.research') }}</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="target" id="targetRadios3" value="/database/ships/{{$activePlanet}}">
                    <label class="form-check-label" for="targetRadios3">{{ __('database.ships') }}</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="target" id="targetRadios4" value="/database/turrets/{{$activePlanet}}">
                    <label class="form-check-label" for="targetRadios4">{{ __('database.defense') }}</label>
                </div>
                <div class="col-12 sub-line p-1">
                    <input type="button" class="btn btn-secondary" value="{{ __('database.show') }}" onclick="window.location.href=document.querySelector('#treeTarget input[type=radio]:checked').value"/>
                </div>
            </form>
        </div>
    </div>
</div>
