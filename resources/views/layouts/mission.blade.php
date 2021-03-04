<div class="container">
    <div class="row">
        <div class="col-12">
            <form action="/mission/{{$activePlanet}}/start" method="post">
                @csrf
                <div class="row">
                    <div class="col-12 col-md-6">
                        <div class="row">
                            <div class="col-4">
                                <div class="form-group">
                                    <label for="galaxy">Galaxie</label>
                                    <input type="number" name="galaxy" id="galaxy" class="form-control" placeholder="Galaxie" step="1" min="1"/>
                                    @error('galaxy')
                                    <span class="ui-state-error" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="form-group">
                                    <label for="system">System</label>
                                    <input type="number" name="system" id="system" class="form-control" placeholder="System" step="1" min="1"/>
                                    @error('system')
                                    <span class="ui-state-error" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="form-group">
                                    <label for="planet">Planet</label>
                                    <input type="number" name="planet" id="planet" class="form-control" placeholder="Planet" step="1" min="1"/>
                                    @error('planet')
                                    <span class="ui-state-error" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-md-6 mb-4">
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
                    @if($shipsAtPlanet)
                        @foreach($shipsAtPlanet->ship_types as $ship)
                            @if($ship->amount > 0)
                                <label class="col-12 col-md-3 offset-md-6 mb-1" for="fleet-{{$ship->ship_id}}">{{$ship->ship_name}} (max.: {{$ship->amount}})</label>
                                <div class=" col-12 col-md-3 mb-1">
                                    <input id="fleet-{{$ship->ship_id}}" name="fleet[{{$ship->ship_id}}]" type="number" min="0" step="1" max="{{$ship->amount}}" class="form-control" placeholder="{{$ship->ship_name}}"/>
                                </div>
                            @endif
                        @endforeach
                    @endif
                    <div class="col-12 col-md-6 offset-md-6">
                        <input type="submit" value="Flotte zusammenstellen" class="btn btn-primary btn-block"/>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
