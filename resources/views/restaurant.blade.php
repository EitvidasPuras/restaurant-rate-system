@extends('layouts.app')

@section('content')
    <head>

        @push('scripts')
            <script type="text/javascript">

                $(document).on('click', '#submitComment', function (e) {
                    e.preventDefault();
                    var commentText = $('#commentText').val();
                    if (commentText) {
                        var data = [];
                        data['restaurant_id'] = "{{ $restaurant->id }}";
                        data['text'] = commentText;
                        var url = "{{ route('comments.store') }}";

                        $.ajax({
                            type: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            url: url,
                            data: {
                                restaurant_id: "{{ $restaurant->id }}",
                                text: commentText
                            },
                            success: function (data) {
                                console.log(data);
                                window.location.reload(true);
                            },
                            error: function (error) {
                                for (var obj in error.responseJSON) {
                                    $('#errors').append('<span>' + error.responseJSON[obj] + '</span>');
                                }
                                $('#errors').fadeIn();
                                console.log(error.responseJSON);
                            }

                        });
                    } else {
                        $('#errors').text('Comment field is required').show();
                    }
                });

            </script>
        @endpush

    </head>
    <body>

    <div class="title-holder">
        <h1> {{$restaurant->name}}</h1>
    </div>
    <hr>

    <div class="restaurant-info-holder col-personal-12">
        <div class="col-personal-d-6 col-personal-s-6 col-personal-ms-12">
            <img src="{{asset("storage/restaurant_images/$restaurant->image")}}">
        </div>

        <div class="col-personal-d-6 col-personal-s-6 col-personal-ms-12">
            <p id="restaurantDescription"> {{$restaurant->description}}</p>

            <p id="restaurantTypeName"> Type: {{$restaurant->type->name}}</p>
            <p id="restaurantSeats"> Number of seats: {{ $restaurant->seats }}</p>
            <p id="restaurantTotalRated"> Number of users who rated this restaurant: {{$restaurant->total_count}}</p>
            <p id="restaurantAverage"> Average rating: {{$restaurant->average_rating}}</p>
        </div>
    </div>

    <hr>

    <div class="comment-form-holder">
        <form>
            @csrf

            <textarea id="commentText" tabindex="-1"
                      class="form-control" name="commentText"
                      cols="10" rows="5" placeholder="Type in a comment"
            ></textarea>

            <a id="submitComment" class="btn btn-primary">
                {{ __('Submit') }}
            </a>

        </form>
    </div>

    <div id="errors"></div>

    <p> {{ $restaurant->comments }}</p>
    <p> {{ $restaurant->ratings  }}</p>

    </body>

@endsection