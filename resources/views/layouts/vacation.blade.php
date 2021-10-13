<div class="row">
    <div class="col-md-8 offset-md-2">
        <div class="card">
            <div class="card-header title-line">Urlaubsmodus aktiv</div>
            <div class="card-body sub-line">
                <div class="row">
                    <div class="col-12 mb-4">
                        Dieser Account befindet sich noch im Urlaubsmodus.<br/>
                        Dieser ist noch aktiv bis: {{$date}}
                    </div>
                    <div class="col-6">
                        <a class="btn btn-primary" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">{{ __('Logout') }}</a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                            @csrf
                        </form>
                    </div>
                    <div class="col-6">
                        <a class="btn btn-danger" href="/vacation/deactivate">Deaktivieren</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

