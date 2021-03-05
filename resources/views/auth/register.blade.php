@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Register') }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('register') }}">
                        @csrf

                        <div class="form-group row">
                            <label for="username" class="col-md-4 col-form-label text-md-right">{{ __('Name') }}</label>

                            <div class="col-md-6">
                                <input id="username" type="text" class="form-control @error('username') is-invalid @enderror" name="username" value="{{ old('username') }}" autocomplete="username" autofocus>

                                @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="email" class="col-md-4 col-form-label text-md-right">{{ __('E-Mail Address') }}</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ uniqid() }}@starfights.de" autocomplete="email" readonly>

                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="password" class="col-md-4 col-form-label text-md-right">{{ __('Password') }}</label>

                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" autocomplete="new-password">

                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="password-confirm" class="col-md-4 col-form-label text-md-right">{{ __('Confirm Password') }}</label>

                            <div class="col-md-6">
                                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" autocomplete="new-password">
                            </div>
                        </div>

                        <hr>

                        <div class="form-group row">
                            <label for="race-select" class="col-md-4 col-form-label text-md-right">{{ __('Select Civilization') }}</label>

                            <div class="col-md-6">
                                <div class="form-check">
                                    <input type="radio" class="form-check-input" name="race_id" id="raceRadio1" value="1"/>
                                    <label for="raceRadio1" class="form-check-label">Tirashkar (Offensiv-Bonus +10%)</label>
                                </div>
                                <div class="form-check">
                                    <input type="radio" class="form-check-input" name="race_id" id="raceRadio2" value="2"/>
                                    <label for="raceRadio2" class="form-check-label">Lidatier (Defensiv-Bonus +10%)</label>
                                </div>
                                <div class="form-check">
                                    <input type="radio" class="form-check-input" name="race_id" id="raceRadio3" value="3"/>
                                    <label for="raceRadio3" class="form-check-label">Nalmenen (Forschungszeit-Bonus +5%)</label>
                                </div>
                                <div class="form-check">
                                    <input type="radio" class="form-check-input" name="race_id" id="raceRadio4" value="4"/>
                                    <label for="raceRadio4" class="form-check-label">Kolinar (Bau-/Produktionszeit-Bonus +5%)</label>
                                </div>
                                <div class="form-check">
                                    <input type="radio" class="form-check-input" name="race_id" id="raceRadio5" value="5"/>
                                    <label for="raceRadio5" class="form-check-label">Padurein (Resourcen-Bonus +10%)</label>
                                </div>
                            </div>
                            @error('race_id')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>

                        <div class="form-group row">
                            <label for="startControlSelect" class="col-md-4 col-form-label text-md-right">{{ __('Starting Galaxy') }}</label>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <select class="form-control" id="startControlSelect" name="galaxy">
                                        <option value="1">1</option>
                                    </select>
                                </div>
                            </div>
                            @error('galaxy')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Register') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
