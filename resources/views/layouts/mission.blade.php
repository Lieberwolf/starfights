<div class="container-fluid">
    <div class="row">
        <div class="col-12 title-line mb-1"><span>Mission starten</span></div>
        <div class="col-12">
            <form action="/mission/start/{{$activePlanet}}" method="post">
                @csrf
                <div class="row">
                    <div class="col-12 col-md-6 sub-line">
                        <div class="row">
                            <div class="col-4">
                                <div class="form-group p-1">
                                    <label for="galaxy">Galaxie</label>
                                    <input value="{{$target ? $target[0] : $koords->galaxy}}" type="number" name="galaxy" id="galaxy" class="form-control" placeholder="Galaxie" step="1" min="1"/>
                                    @error('galaxy')
                                    <span class="ui-state-error" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="form-group p-1">
                                    <label for="system">System</label>
                                    <input value="{{$target ? $target[1] : $koords->system}}"  type="number" name="system" id="system" class="form-control" placeholder="System" step="1" min="1"/>
                                    @error('system')
                                    <span class="ui-state-error" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="form-group p-1">
                                    <label for="planet">Planet</label>
                                    <input value="{{$target ? $target[2] : $koords->planet}}"  type="number" name="planet" id="planet" class="form-control" placeholder="Planet" step="1" min="1"/>
                                    @error('planet')
                                    <span class="ui-state-error" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-md-6 sub-line p-1">
                        <div class="form-group">
                            <label for="speed">Fluggeschwindigkeit</label>
                            <select name="speed" id="speed" class="form-control">
                                <option value="100" selected>100%</option>
                                <option value="90">90%</option>
                                <option value="80">80%</option>
                                <option value="70">70%</option>
                                <option value="60">60%</option>
                                <option value="50">50%</option>
                                <option value="40">40%</option>
                                <option value="30">30%</option>
                                <option value="20">20%</option>
                                <option value="10">10%</option>
                            </select>
                        </div>
                    </div>
                    @if($shipsAtPlanet)
                        @foreach($shipsAtPlanet->ship_types as $key => $ship)
                            @if($ship->amount > 0)
                                <div class="col-12 col-md-6 offset-md-6 sub-line">
                                    <div class="form-group row p-1 mb-0">
                                        <label class="col-6 col-form-label" for="fleet-{{$ship->ship_id}}">{{$ship->ship_name}} <a class="js-add-to-field">(max.: <span>{{$ship->amount}}</span>)</a></label>
                                        <input data-ship-id="{{$ship->ship_id}}" data-ship-name="{{$ship->ship_name}}" id="fleet-{{$ship->ship_id}}" name="fleet[{{$ship->ship_id}}]" type="number" min="0" step="1" max="{{$ship->amount}}" class="col-6 form-control" placeholder="{{$ship->ship_name}}"/>
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    @endif
                    <div class="col-12 col-md-6 offset-md-6 title-line mt-1 p-1">
                        <input type="submit" value="Flotte zusammenstellen" class="btn btn-secondary"/>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
