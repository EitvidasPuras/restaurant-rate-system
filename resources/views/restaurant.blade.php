@extends('layouts.app')

@section('content')
    <head>

        @push('scripts')
            <script type="text/javascript">

                $(document).on('click', '#submitComment', function (e) {
                    e.preventDefault();
                    var commentText = $('#commentText').val();
                    var rating = $('#doRateRestaurant').val();
                    if (commentText && rating) {
                        var url = "{{ route('comments.store') }}";

                        $.ajax({
                            type: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            url: url,
                            data: {
                                restaurant_id: "{{ $restaurant->id }}",
                                text: commentText,
                                rating: rating
                            },
                            success: function (data) {
                                window.location.reload(true);
                            },
                            error: function (error) {
                                $('#errors').text(error.responseJSON).fadeIn('slow');
                                setTimeout(function () {
                                    $("#errors").fadeOut('slow');
                                }, 2500);
                            }

                        });
                    } else {
                        $('#errors').text('Comment field and rating is required').fadeIn('slow');
                        setTimeout(function () {
                            $("#errors").fadeOut('slow');
                        }, 2500);
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
        <div id="holdImage" class="col-personal-dx-6 col-personal-dd-6 col-personal-d-6">
            <img src="{{asset("storage/restaurant_images/$restaurant->image")}}">
        </div>

        <div id="holdText" class="col-personal-d-6 col-personal-s-6 col-personal-ms-12">
            <p id="restaurantDescription"> {{$restaurant->description}}</p>

            <p id="restaurantTypeName"> Type: {{$restaurant->type->name}}</p>
            <p id="restaurantSeats"> Number of seats: {{ $restaurant->seats }}</p>
            <p id="restaurantAverage"> Average rating: <input id="input-3" name="input-3" step="0.1"
                                                              value="{{ number_format($restaurant->average_rating, 1) }}"
                                                              class="rating rating-loading col-personal-12 col-personal-ms-12 col-personal-ss-12"
                                                              readonly> | Reviews: {{$restaurant->total_count}}</p>
        </div>
    </div>

    <hr>

    @if(auth()->check())
        <div class="comment-form-holder">
            <form>
                @csrf

                <textarea id="commentText" tabindex="-1"
                          class="form-control" name="commentText"
                          cols="10" rows="5" placeholder="Type in a comment"
                          maxlength="500"
                ></textarea>

                <div id="starsAndButton">

                    <input id="doRateRestaurant" name="input-9"
                           required class="rating rating-loading"
                           data-min="0" data-max="5" data-step="1">

                    <div id="errors"></div>

                    <button id="submitComment" class="btn btn-primary col-personal-ms-12">
                        {{ __('Submit') }}
                    </button>

                </div>

            </form>
        </div>
    @endif

    @if($restaurant->comments->count() > 0)
        <div class="col-personal-12 col-personal-ms-12 col-personal-ss-12">
            @foreach($restaurant->comments as $comment )
                <div class="comment-holder">
                    <div id="commentHeader">
                        <p id="commentUsername"
                           class="col-personal-d-3 col-personal-s-3 col-personal-ms-12 col-personal-ss-12">{{ $comment->user->name }}</p>
                        @foreach($restaurant->ratings as $rating)
                            @if($comment->user_id == $rating->user_id)
                                <p id="commentRating"
                                   class="col-personal-d-5 col-personal-s-5 col-personal-ms-12 col-personal-ss-12">
                                    <input id="showCommentRating" name="input-3" step="1"
                                           value="{{ $rating->rating }}"
                                           class="rating rating-loading"
                                           readonly>
                                </p>
                            @endif
                        @endforeach
                        <p id="commentUpdatedAt"
                           class="col-personal-d-4 col-personal-s-4 col-personal-ms-12 col-personal-ss-12"> {{ $comment->updated_at }}</p>
                    </div>
                    <div id="commentText">
                        <p>{{ $comment->text }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="no-content">
            <h1> No reviews found </h1>
        </div>
    @endif

    </body>

@endsection