@extends('layouts.app')

@section('content')
    <head></head>

    <body>

    <div class="title-holder">
        <h1> Admin panel </h1>
    </div>
    <hr>

    <div class="admin-panel-cards-holder col-personal-dd-12 col-personal-d-12">

        <div class="col-personal-dd-3 col-personal-d-4 col-personal-s-6 col-personal-ms-12">
            <div class="admin-panel-card col-personal-d-3 col-personal-s-6 col-personal-ms-12">
                <h1> Users </h1>
                <div class="admin-panel-card-circle">
                    <a href="{{ route('adminUsers') }}">
                        <i class="fas fa-users"></i>
                    </a>
                </div>
            </div>
        </div>

        <div class="col-personal-dd-3 col-personal-d-4 col-personal-s-6 col-personal-ms-12">
            <div class="admin-panel-card col-personal-d-3 col-personal-s-6 col-personal-ms-12">
                <h1> Restaurants </h1>
                <div class="admin-panel-card-circle">
                    <a href="{{ route('adminRestaurants') }}">
                        <i class="fas fa-utensils"></i>
                    </a>
                </div>
            </div>
        </div>

        <div class="col-personal-dd-3 col-personal-d-4 col-personal-s-6 col-personal-ms-12">
            <div class="admin-panel-card col-personal-d-3 col-personal-s-6 col-personal-ms-12">
                <h1> Comments </h1>
                <div class="admin-panel-card-circle">
                    <a href="{{ url('/') }}">
                        <i class="fas fa-comments"></i>
                    </a>
                </div>
            </div>
        </div>

        <div class="col-personal-dd-3 col-personal-d-4 col-personal-s-6 col-personal-ms-12">
            <div class="admin-panel-card col-personal-d-3 col-personal-s-6 col-personal-ms-12">
                <h1> Types </h1>
                <div class="admin-panel-card-circle">
                    <a href="{{ route('adminTypes') }}">
                        <i class="fas fa-copy"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
    </body>

@endsection