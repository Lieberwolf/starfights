<div class="container-fluid">
    <div class="row">
        <div class="col-12 title-line">
            <span>{{__('settings.settings')}}</span>
        </div>

        <div class="col-12 title-line"><span>{{__('settings.emailSettings')}}</span></div>
        <div class="col-12 sub-line">
            <form action="/settings/{{$activePlanet}}/updateE" method="post">
                @csrf
                <div class="form-group row p-1 mb-0">
                    <label class="col-6 col-form-label" for="email">{{__('settings.emailLabel')}}</label>
                    <input value="{{$user->email}}" tabindex="1" id="email" name="email" type="email" class="col-6 form-control" placeholder="{{__('settings.emailLabel')}}">
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
                    <input tabindex="2" id="email-confirm" name="email_confirmation" type="email" class="col-6 form-control" placeholder="{{__('settings.emailPLabel')}}">
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
                        <button tabindex="3" type="submit" class="btn btn-secondary">{{__('settings.save')}}</button>
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
                    <input tabindex="4" id="password" name="password" type="password" class="col-6 form-control" placeholder="{{__('settings.passwordLabel')}}">
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
                    <input tabindex="5" id="password-confirm" name="password_confirmation" type="password" class="col-6 form-control" placeholder="{{__('settings.passwordPLabel')}}">
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
                    <div class="col-6 col-form-label" for="password">Account Löschen</div>
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
