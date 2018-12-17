@extends('layouts.app')

@section('content')
    <head>

    </head>
    <body>

    @if($restaurants->count() > 0)
        <div class="restaurants-holder col-personal-12">
            @foreach( $restaurants as $restaurant)
                <div class="col-personal-dx-4 col-personal-dd-4 col-personal-d-6">
                    <div class="name-header col-personal-dx-4 col-personal-dd-4 col-personal-ms-12">{{ $restaurant->name }}</div>
                    <a href="{{ route('showRestaurant', $restaurant->id) }}">
                        <img id="restaurantImage" src="{{asset("storage/restaurant_images/$restaurant->image")}}">
                    </a>
                    <p class="restaurant-text">{{str_limit($restaurant->description,130,"...")}}</p>
                    <p> Rating: {{ $restaurant->average_rating }} | {{ $restaurant->total_count }} reviews</p>
                </div>
            @endforeach
            {!! $restaurants->render() !!}
        </div>
    @else
        <div class="no-content">
            <h1> No restaurants found </h1>
        </div>
    @endif

    </body>

@endsection