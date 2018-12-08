@extends('layouts.app')

@section('content')
    <head>

    </head>
    <body>

    <div class="restaurant-holder col-personal-12">
        @foreach( $restaurants as $restaurant)
            <div class="col-personal-d-4 col-personal-s-6 col-personal-ms-12">
                <div class="restaurant-header col-personal-d-4 col-personal-s-6 col-personal-ms-12">{{ $restaurant->name }}</div>
                <img id="restaurantImage" src="{{asset("storage/restaurant_images/$restaurant->image")}}">
                <div class="text-container">
                    <p>{{ $restaurant->description }}</p>
                </div>
            </div>
        @endforeach
    </div>

    </body>
@endsection