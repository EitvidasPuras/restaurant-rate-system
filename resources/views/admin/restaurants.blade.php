@extends('layouts.app')

@section('content')

    <head>

        @push('scripts')
            <script>

                // New restaurant request
                $(document).on('click', '#submitRestaurant', function (e) {
                    e.preventDefault();
                    var rname = $('#restaurantNameInput').val();
                    var rdescription = $('#restaurantDescInput').val();
                    var rseats = $('#restaurantSeatsInput').val();
                    var rtype = $('#restaurantTypeInput').val();
                    var rimage = $('#restaurantImageInput')[0].files[0];

                    var maxFileSize = $('#restaurantImageInput').data('max-size');

                    if (rname && rdescription
                        && rseats && rtype && rimage
                        && (rseats > 0 && rseats < 101)
                        && (rimage.size < maxFileSize)) {
                        var formData = new FormData();
                        formData.append('name', rname);
                        formData.append('description', rdescription);
                        formData.append('seats', rseats);
                        formData.append('type_id', rtype);
                        formData.append('image', rimage);

                        var url = "{{ route('restaurants.store') }}";
                        $.ajax({
                            type: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            url: url,
                            processData: false,
                            contentType: false,
                            data: formData,
                            success: function (data) {
                                window.location.reload(true);
                            },
                            error: function (error) {
                                $('#errorsRestaurant').text(error.responseJSON).fadeIn('slow');
                                setTimeout(function () {
                                    $("#errorsRestaurant").fadeOut('slow');
                                }, 3000);
                            }

                        });
                    } else {
                        $('#errorsRestaurant').text('Please check if all data is correct').fadeIn('slow');
                        setTimeout(function () {
                            $("#errorsRestaurant").fadeOut('slow');
                        }, 3000);
                    }
                });

                // Open edit restaurant form
                $(document).on('click', '#openEditRestaurantForm', function (e) {
                    e.preventDefault();
                    e.stopPropagation();
                    $('#editRestaurantWindow').fadeIn('slow');
                    $(this).toggleClass('popup-active');

                    var rid = $(this).data('r-id');
                    var url = "{{ route('restaurants.show', '?id') }}";
                    url = url.replace('?id', rid);
                    $.ajax({
                        type: 'GET',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        url: url,
                        success: function (data) {
                            $('div#editRestaurantWindow div:nth-child(1)').children('h1').text('Edit ' + data['name']);
                            $('#restaurantNameInputEdit').val(data['name']);
                            $('#restaurantDescInputEdit').text(data['description']);
                            $('#restaurantSeatsInputEdit').val(data['seats']);
                            $('#restaurantTypeInputEdit').val(data['type_id']);
                            $('#nameOfExistingFile').text(data['image']);
                            $('#fri').val(rid);
                        },
                        error: function (error) {
                            $('#errorsEditRestaurant').text(error.responseJSON).fadeIn('slow');
                            setTimeout(function () {
                                $("#errorsEditRestaurant").fadeOut('slow');
                            }, 3000);
                        }
                    });
                });

                // Edit restaurant request
                $(document).on('click', '#submitEditedRestaurant', function (e) {
                    e.preventDefault();
                    var rname = $('#restaurantNameInputEdit').val();
                    var rdescription = $('#restaurantDescInputEdit').val();
                    var rseats = $('#restaurantSeatsInputEdit').val();
                    var rtype = $('#restaurantTypeInputEdit').val();
                    var rimage = $('#restaurantImageInputEdit')[0].files[0];

                    var maxFileSize = $('#restaurantImageInputEdit').data('max-size');

                    if (typeof rimage !== 'undefined') {
                        if (rname && rdescription
                            && rseats && rtype
                            && (rseats > 0 && rseats < 101)
                            && (rimage.size < maxFileSize)) {
                            var formData = new FormData();
                            formData.append('name', rname);
                            formData.append('description', rdescription);
                            formData.append('seats', rseats);
                            formData.append('type_id', rtype);
                            formData.append('image', rimage);
                            formData.append('_method', 'PUT');

                            var rid = $('#fri').val();
                            var url = "{{ route('restaurants.update', '?id') }}";
                            url = url.replace('?id', rid);
                            $.ajax({
                                type: 'POST',
                                headers: {
                                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                },
                                url: url,
                                processData: false,
                                contentType: false,
                                data: formData,
                                success: function (data) {
                                    window.location.reload(true);
                                },
                                error: function (error) {
                                    $('#errorsEditRestaurant').text(error.responseJSON).fadeIn('slow');
                                    setTimeout(function () {
                                        $("#errorsEditRestaurant").fadeOut('slow');
                                    }, 3000);
                                }
                            });
                        } else {
                            $('#errorsEditRestaurant').text('Please check if all data is correct').fadeIn('slow');
                            setTimeout(function () {
                                $("#errorsEditRestaurant").fadeOut('slow');
                            }, 3000);
                        }
                    } else {
                        if (rname && rdescription
                            && rseats && rtype
                            && (rseats > 0 && rseats < 101)) {
                            var formData = new FormData();
                            formData.append('name', rname);
                            formData.append('description', rdescription);
                            formData.append('seats', rseats);
                            formData.append('type_id', rtype);
                            formData.append('_method', 'PUT');

                            var rid = $('#fri').val();
                            var url = "{{ route('restaurants.update', '?id') }}";
                            url = url.replace('?id', rid);
                            $.ajax({
                                type: 'POST',
                                headers: {
                                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                },
                                url: url,
                                processData: false,
                                contentType: false,
                                data: formData,
                                success: function (data) {
                                    window.location.reload(true);
                                },
                                error: function (error) {
                                    $('#errorsEditRestaurant').text(error.responseJSON).fadeIn('slow');
                                    setTimeout(function () {
                                        $("#errorsEditRestaurant").fadeOut('slow');
                                    }, 3000);
                                }
                            });
                        } else {
                            $('#errorsEditRestaurant').text('Please check if all data is correct').fadeIn('slow');
                            setTimeout(function () {
                                $("#errorsEditRestaurant").fadeOut('slow');
                            }, 3000);
                        }
                    }
                });

                // Close edit restaurant form
                $(document).on('click', function (e) {
                    if ($(e.target).closest('#editRestaurantWindow').length > 0) {
                    } else {
                        $('#editRestaurantWindow').fadeOut('slow');
                        $(this).removeClass('popup-active');
                    }
                });

                // Open new restaurant form
                $(document).on('click', '#openNewRestaurantForm', function (e) {
                    e.preventDefault();
                    e.stopPropagation();
                    $('#newRestaurantCreation').fadeIn('slow');
                    $('#openNewRestaurantForm').toggleClass('popup-active');
                });

                // Close new restaurant form
                $(document).on('click', function (e) {
                    if ($(e.target).closest('#newRestaurantCreation').length > 0) {
                    } else {
                        $('#newRestaurantCreation').fadeOut('slow');
                        $('#openNewRestaurantForm').removeClass('popup-active');
                    }
                });

                // Open delete restaurant form
                $(document).on('click', '#openDeleteRestaurantConfirmation', function (e) {
                    e.preventDefault();
                    e.stopPropagation();
                    $('#deleteRestaurantWindow').fadeIn('slow');
                    $(this).toggleClass('popup-active');

                    var rid = $(this).data('r-id');
                    var url = "{{ route('restaurants.show', '?id') }}";
                    url = url.replace('?id', rid);

                    $.ajax({
                        type: 'GET',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        url: url,
                        success: function (data) {
                            $('div#deleteRestaurantWindow div:nth-child(1)').children('h1').text('Delete ' + data['name'] + ' ?');
                            $('#frid').val(rid);
                        }
                    });
                });

                // Close delete restaurant form
                $(document).on('click', function (e) {
                    if ($(e.target).closest('#deleteRestaurantWindow').length > 0) {
                    } else {
                        $('#deleteRestaurantWindow').fadeOut('slow');
                        $(this).removeClass('popup-active');
                    }
                });

                // Delete restaurant request
                $(document).on('click', '#submitDeleteRestaurant', function (e) {
                    e.preventDefault();

                    var formData = new FormData();
                    formData.append('_method', 'DELETE');

                    var rid = $('#frid').val();
                    var url = "{{ route('restaurants.destroy', '?id') }}";
                    url = url.replace('?id', rid);

                    $.ajax({
                        type: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        url: url,
                        processData: false,
                        contentType: false,
                        data: formData,
                        success: function (data) {
                            window.location.reload(true);
                        },
                        error: function (error) {
                            console.log(error);
                        }
                    });
                })

            </script>
        @endpush

    </head>

    <body>

    <div class="title-holder">
        <h1> Edit restaurants </h1>
        <button id="openNewRestaurantForm" class="btn btn-primary">
            <i class="fas fa-plus"></i>
            {{ __('New restaurant') }}
        </button>
    </div>
    <hr>

    @if($restaurants->count() > 0)
        <div class="admin-objects-holder col-personal-12">
            @foreach($restaurants as $restaurant)
                <div class="admin-object-holder">
                    <div class="col-personal-dx-3 col-personal-dd-4 col-personal-d-5 col-personal-s-7">
                        <img id="restaurantImag" src="{{asset("storage/restaurant_images/$restaurant->image")}}">
                    </div>
                    <div id="restaurantMultiLineInfo"
                         class="col-personal-dx-2 col-personal-dd-3 col-personal-d-4 col-personal-s-5">
                        <p id="restaurantName">
                            {{ $restaurant->name }}
                        </p>
                        <p id="restaurantType">
                            {{ $restaurant->type->name }}
                        </p>
                        <p id="restaurantSeats">
                            {{ $restaurant->seats }}
                        </p>
                    </div>
                    <p id="restaurantDescription"
                       class="col-personal-dx-2 col-personal-dd-3 col-personal-d-4 col-personal-s-7">
                        {{str_limit($restaurant->description,130,"...")}}
                    </p>
                    <div id="restaurantMultiLineInfo"
                         class="col-personal-dx-1 col-personal-dd-2 col-personal-d-2 col-personal-s-5">
                        <p id="restaurantRating">
                            Rating: {{ $restaurant->average_rating }}
                        </p>
                        <p id="restaurantTotal">
                            {{ $restaurant->total_count }} reviews
                        </p>
                    </div>
                    <p id="restaurantCreatedAt"
                       class="col-personal-dx-2 col-personal-dd-3 col-personal-d-3 col-personal-s-6">
                        {{ $restaurant->created_at }}
                    </p>
                    <p id="objectEdit"
                       class="col-personal-dx-1 col-personal-dd-1 col-personal-d-1 col-personal-s-2">
                        <a id="openEditRestaurantForm"
                           data-r-id="{{ $restaurant->id }}">
                            <i class="fas fa-edit"></i>
                        </a>
                    </p>
                    <p id="objectDelete"
                       class="col-personal-dx-1 col-personal-dd-1 col-personal-d-1 col-personal-s-2">
                        <a id="openDeleteRestaurantConfirmation" data-r-id="{{ $restaurant->id }}">
                            <i class="fas fa-trash-alt"></i>
                        </a>
                    </p>
                </div>
            @endforeach
            {!! $restaurants->render() !!}
        </div>
    @else
        <div class="no-content">
            <h1> No restaurants found </h1>
        </div>
    @endif

    <div id="newRestaurantCreation" class="admin-new-edit-restaurant col-personal-12">

        <div class="title-holder">
            <h1> Create a restaurant </h1>
        </div>
        <hr>

        <div id="adminObjectForm">

            <form>
                @csrf
                <div class="form-personal-row">
                    <label class="col-personal-2"> Name </label>
                    <input id="restaurantNameInput" name="restaurantNameInput" placeholder="Type in a name"
                           maxlength="15"
                           class="form-control">
                </div>

                <div class="form-personal-row">
                    <label class="col-personal-2"> Description </label>
                    <textarea id="restaurantDescInput"
                              class="form-control" name="restaurantDescInput"
                              cols="10" rows="7" placeholder="Type in a description"
                              maxlength="600"
                    ></textarea>
                </div>

                <div class="form-personal-row">
                    <label class="col-personal-2"> Seats </label>
                    <input id="restaurantSeatsInput" min="1" max="100" placeholder="Select number of seats"
                           type="number"
                           class="form-control" name="restaurantSeatsInput">
                </div>

                <div class="form-personal-row">
                    <label class="col-personal-2"> Type </label>
                    <select class="form-control" name="restaurantTypeInput" id="restaurantTypeInput">
                        @foreach($types as $type)
                            <option value="{{ $type->id }}"> {{ $type->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-personal-row">
                    <label class="col-personal-2"> Image (1MB) </label>
                    <input placeholder="Upload a file" id="restaurantImageInput" maxlength="100" type="file"
                           class="form-control" data-max-size="1048576" accept="image/*"
                           name="restaurantImageInput">
                </div>

                <div class="admin-form-button-holder">
                    <div class="admin-object-errors" id="errorsRestaurant"></div>
                    <button id="submitRestaurant" class="btn btn-primary">
                        {{ __('Submit') }}
                    </button>
                </div>

            </form>
        </div>
    </div>

    <div id="editRestaurantWindow" class="admin-new-edit-restaurant col-personal-12">
        <div class="title-holder">
            <h1></h1>
        </div>
        <hr>

        <div id="adminObjectForm">

            <form>
                @csrf
                <div class="form-personal-row">
                    <label class="col-personal-2"> Name </label>
                    <input id="restaurantNameInputEdit" name="restaurantNameInputEdit" placeholder="Type in a name"
                           maxlength="15"
                           class="form-control">
                </div>

                <div class="form-personal-row">
                    <label class="col-personal-2"> Description </label>
                    <textarea id="restaurantDescInputEdit"
                              class="form-control" name="restaurantDescInputEdit"
                              cols="10" rows="7" placeholder="Type in a description"
                              maxlength="600"
                    ></textarea>
                </div>

                <div class="form-personal-row">
                    <label class="col-personal-2"> Seats </label>
                    <input id="restaurantSeatsInputEdit" min="1" max="100" placeholder="Select number of seats"
                           type="number"
                           class="form-control" name="restaurantSeatsInputEdit">
                </div>

                <div class="form-personal-row">
                    <label class="col-personal-2"> Type </label>
                    <select class="form-control" name="restaurantTypeInputEdit" id="restaurantTypeInputEdit">
                        @foreach($types as $type)
                            <option value="{{ $type->id }}"> {{ $type->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-personal-row">
                    <label class="col-personal-2"> Image (1MB) </label>
                    <input placeholder="Upload a file" id="restaurantImageInputEdit" maxlength="100" type="file"
                           class="form-control" data-max-size="1048576" accept="image/*"
                           name="restaurantImageInputEdit">
                </div>

                <div class="admin-form-button-holder" id="editRestaurantButton">
                    <label id="nameOfExistingFile"></label>
                    <div class="admin-object-errors" id="errorsEditRestaurant"></div>
                    <button id="submitEditedRestaurant" class="btn btn-primary">
                        {{ __('Submit') }}
                    </button>
                    <input type="hidden" id="fri" name="fri">
                </div>
            </form>
        </div>

    </div>

    <div id="deleteRestaurantWindow" class="admin-deletion-restaurant col-personal-12">
        <div class="title-holder">
            <h1></h1>
        </div>
        <hr>

        <div class="admin-form-button-holder" id="deleteRestaurantButton">
            <button id="submitDeleteRestaurant" class="btn btn-danger">
                {{ __('Delete') }}
            </button>
            <input type="hidden" id="frid" name="frid">
        </div>
    </div>

    </body>

@endsection