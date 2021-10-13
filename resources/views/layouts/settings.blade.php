<div class="container-fluid">
    <div class="row">
        <div class="col-12 title-line">
            <span>{{__('settings.settings')}}</span>
        </div>

        <div class="col-12 title-line">
            Urlaubsmodus
        </div>
        <div class="col-12 sub-line">
            @if(strtotime($vacation->vacation_blocked_until) <= now()->timestamp)
            <form action="/vacation/enable" method="post">
                @csrf
                <div class="form-group row p-1 mb-0">
                    <div class="col-6 col-form-label">
                        <div class="row">
                            <div class="col-12">
                                Urlaubsmodus
                            </div>
                            <div class="col-12">
                                Gilt für 2 Wochen und ist danach für 4 Wochen gesperrt.
                            </div>
                        </div>
                    </div>
                    <div class="col-6 form-check p-1">
                        <input class="form-check-input" type="checkbox" name="vacation" id="vacation">
                        <label class="form-check-label" for="vacation">
                            <span>aktivieren</span>
                        </label>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12 col-md-6 offset-md-6 p-2">
                        <button type="submit" class="btn btn-secondary">{{__('settings.save')}}</button>
                    </div>
                </div>
            </form>
            @else
                <div class="row">
                    <div class="col-6">Urlaubsmodus</div>
                    <div class="col-6">Gesperrt bis: {{$vacation->vacation_blocked_until}}</div>
                </div>
            @endif
        </div>

        <div class="col-12 mt-3 title-line">
            <span>Benachrichtigungen</span>
        </div>
        <div class="col-12 sub-line">
            <form action="/settings/{{$activePlanet}}/updateN" method="post">
                @csrf
                <div class="form-group row p-1 mb-0">
                    <div class="col-6 col-form-label">Konstruktionen</div>
                    <div class="col-6 form-check p-1">
                        <input class="form-check-input" type="checkbox" name="construction" id="construction" {{ property_exists($notifications, 'construction') && $notifications->construction == "on" ? 'checked' : '' }}>
                        <label class="form-check-label" for="construction">
                            <span>Konstruktionen</span>
                        </label>
                    </div>
                </div>
                <div class="form-group row p-1 mb-0">
                    <div class="col-6 col-form-label">Forschungen</div>
                    <div class="col-6 form-check p-1">
                        <input class="form-check-input" type="checkbox" name="research" id="research" {{ property_exists($notifications, 'research') && $notifications->research == "on" ? 'checked' : '' }}>
                        <label class="form-check-label" for="construction">
                            <span>Forschungen</span>
                        </label>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12 col-md-6 offset-md-6 p-2">
                        <button type="submit" class="btn btn-secondary">{{__('settings.save')}}</button>
                    </div>
                </div>
            </form>
        </div>

        <div class="col-12 title-line mt-3"><span>{{__('settings.emailSettings')}}</span></div>
        <div class="col-12 sub-line">
            <form action="/settings/{{$activePlanet}}/updateE" method="post">
                @csrf
                <div class="form-group row p-1 mb-0">
                    <label class="col-6 col-form-label" for="email">{{__('settings.emailLabel')}}</label>
                    <input value="{{$user->email}}" id="email" name="email" type="email" class="col-6 form-control" placeholder="{{__('settings.emailLabel')}}">
                    @error('email')
                    <div class="col-12 col-md-6 offset-md-6">
                        <span class="ui ui-state-error text-danger" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    </div>
                    @enderror
                </div>
                <div class="form-group row p-1 mb-0">
                    <label class="col-6 col-form-label" for="email-confirm">{{__('settings.emailPLabel')}}</label>
                    <input id="email-confirm" name="email_confirmation" type="email" class="col-6 form-control" placeholder="{{__('settings.emailPLabel')}}">
                    @error('email_confirmation')
                    <div class="col-12 col-md-6 offset-md-6">
                        <span class="ui ui-state-error text-danger" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    </div>
                    @enderror
                </div>
                <div class="row">
                    <div class="col-12 col-md-6 offset-md-6 p-2">
                        <button type="submit" class="btn btn-secondary">{{__('settings.save')}}</button>
                    </div>
                </div>
            </form>
        </div>

        <div class="col-12 title-line mt-3"><span>{{__('settings.passwordSettings')}}</span></div>
        <div class="col-12 sub-line">
            <form action="/settings/{{$activePlanet}}/updateP" method="post">
                @csrf
                <div class="form-group row p-1 mb-0">
                    <label class="col-6 col-form-label" for="password">{{__('settings.passwordLabel')}}</label>
                    <input id="password" name="password" type="password" class="col-6 form-control" placeholder="{{__('settings.passwordLabel')}}">
                    @error('password')
                    <div class="col-12 col-md-6 offset-md-6">
                        <span class="ui ui-state-error text-danger" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    </div>
                    @enderror
                </div>
                <div class="form-group row p-1 mb-0">
                    <label class="col-6 col-form-label" for="password-confirm">{{__('settings.passwordPLabel')}}</label>
                    <input id="password-confirm" name="password_confirmation" type="password" class="col-6 form-control" placeholder="{{__('settings.passwordPLabel')}}">
                    @error('password_confirmation')
                    <div class="col-12 col-md-6 offset-md-6">
                        <span class="ui ui-state-error text-danger" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    </div>
                    @enderror
                </div>
                <div class="col-12 col-md-6 offset-md-6 p-1 text-left">
                    <a href="{{ route('password.request') }}" target="_blank">{{__('settings.forgottenPassword')}}</a>
                </div>
                <div class="row">
                    <div class="col-12 col-md-6 offset-md-6 p-2">
                        <button tabindex="6" type="submit" class="btn btn-secondary">{{__('settings.save')}}</button>
                    </div>
                </div>
            </form>
        </div>
        <div class="col-12 sub-line">
            <form action="/settings/{{$activePlanet}}/delete" method="post">
                @csrf
                @error('delete')
                <div class="col-12 col-md-6 offset-md-6">
                    <span class="ui ui-state-error text-danger" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                </div>
                @enderror
                <div class="form-group row p-1 mb-0">
                    <div class="col-6 col-form-label">Account Löschen</div>
                    <div class="col-6 form-check p-1">
                        <input class="form-check-input" type="checkbox" name="delete" id="delete" {{ old('delete') ? 'checked' : '' }}>
                        <label class="form-check-label" for="delete">
                            <span class="text-danger">Ja, wirklich löschen</span>
                        </label>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12 col-md-6 offset-md-6 p-2">
                        <button tabindex="6" type="submit" class="btn btn-danger">Löschen</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
