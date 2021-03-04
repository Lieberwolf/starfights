@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-2">
                @if (session('status'))
                    <div class="alert alert-success" role="alert">
                        {{ session('status') }}
                    </div>
                @endif
                @include('layouts.menu')
            </div>
            <div class="col-md-10 text-center">
                <div class="row">
                    <div class="col-12">
                        @include('layouts.resources-menu')
                    </div>
                    <div class="col-12">
                        @include('layouts.simulation')
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
