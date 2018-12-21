@extends('layouts.app')

@section('content')

    <head>

        @push('scripts')
            <script>

                // Open new type creation
                $(document).on('click', '#openNewTypeForm', function (e) {
                    e.preventDefault();
                    e.stopPropagation();
                    $('#newTypeCreation').fadeIn('slow');
                    $('#openNewTypeForm').toggleClass('popup-active');
                });

                // Close new type creation
                $(document).on('click', function (e) {
                    if ($(e.target).closest('#newTypeCreation').length > 0) {
                    } else {
                        $('#newTypeCreation').fadeOut('slow');
                        $('#openNewTypeForm').removeClass('popup-active');
                    }
                });

                // New type creation request
                $(document).on('click', '#submitType', function (e) {
                    e.preventDefault();
                    var tname = $('#typeNameInput').val();

                    if (tname) {
                        var formData = new FormData();
                        formData.append('name', tname);

                        var url = "{{ route('types.store') }}";
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
                                $('#errorsType').text(error.responseJSON).fadeIn('slow');
                                setTimeout(function () {
                                    $("#errorsType").fadeOut('slow');
                                }, 3000);
                            }

                        });
                    } else {
                        $('#errorsType').text('Please check if the name is correct').fadeIn('slow');
                        setTimeout(function () {
                            $("#errorsType").fadeOut('slow');
                        }, 3000);
                    }
                });

                // Open type edit
                $(document).on('click', '#openEditTypeForm', function (e) {
                    e.preventDefault();
                    e.stopPropagation();
                    $('#editTypeWindow').fadeIn('slow');
                    $(this).toggleClass('popup-active');

                    var tid = $(this).data('r-id');
                    var url = "{{ route('types.show', '?id') }}";
                    url = url.replace('?id', tid);
                    $.ajax({
                        type: 'GET',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        url: url,
                        success: function (data) {
                            $('div#editTypeWindow div:nth-child(1)').children('h1').text('Edit ' + data['name']);
                            $('#typeNameInputEdit').val(data['name']);
                            $('#fti').val(tid);
                        },
                        error: function (error) {
                            $('#errorsEditType').text(error.responseJSON).fadeIn('slow');
                            setTimeout(function () {
                                $("#errorsEditType").fadeOut('slow');
                            }, 3000);
                        }
                    });
                });

                // Close type edit
                $(document).on('click', function (e) {
                    if ($(e.target).closest('#editTypeWindow').length > 0) {
                    } else {
                        $('#editTypeWindow').fadeOut('slow');
                        $(this).removeClass('popup-active');
                    }
                });

                // Edit type request
                $(document).on('click', '#submitEditedType', function (e) {
                    e.preventDefault();
                    var tname = $('#typeNameInputEdit').val();

                    if (tname) {
                        var formData = new FormData();
                        formData.append('name', tname);
                        formData.append('_method', 'PUT');

                        var tid = $('#fti').val();
                        var url = "{{ route('types.update', '?id') }}";
                        url = url.replace('?id', tid);
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
                                $('#errorsEditType').text(error.responseJSON).fadeIn('slow');
                                setTimeout(function () {
                                    $("#errorsEditType").fadeOut('slow');
                                }, 3000);
                            }

                        });
                    } else {
                        $('#errorsEditType').text('Please check if the name is correct').fadeIn('slow');
                        setTimeout(function () {
                            $("#errorsEditType").fadeOut('slow');
                        }, 3000);
                    }
                })

                // Open delete type
                $(document).on('click', '#openDeleteTypeConfirmation', function (e) {
                    e.preventDefault();
                    e.stopPropagation();
                    $('#deleteTypeWindow').fadeIn('slow');
                    $(this).toggleClass('popup-active');

                    var tid = $(this).data('r-id');
                    var url = "{{ route('types.show', '?id') }}";
                    url = url.replace('?id', tid);

                    $.ajax({
                        type: 'GET',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        url: url,
                        success: function (data) {
                            $('div#deleteTypeWindow div:nth-child(1)').children('h1').text('Delete ' + data['name'] + ' ?');
                            $('#ftid').val(tid);
                        }
                    });
                });

                // Close delete type
                $(document).on('click', function (e) {
                    if ($(e.target).closest('#deleteTypeWindow').length > 0) {
                    } else {
                        $('#deleteTypeWindow').fadeOut('slow');
                        $(this).removeClass('popup-active');
                    }
                });

                // Delete type request
                $(document).on('click', '#submitDeleteType', function (e) {
                    e.preventDefault();

                    var formData = new FormData();
                    formData.append('_method', 'DELETE');

                    var tid = $('#ftid').val();
                    var url = "{{ route('types.destroy', '?id') }}";
                    url = url.replace('?id', tid);

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
        <h1> Edit types </h1>
        <button id="openNewTypeForm" class="btn btn-primary">
            <i class="fas fa-plus"></i>
            {{ __('New type') }}
        </button>
    </div>
    <hr>

    @if($types->count() > 0)
        <div class="admin-objects-holder col-personal-12">
            @foreach($types as $type)
                <div class="admin-object-holder">
                    <p id="typeName"
                       class="col-personal-dx-5 col-personal-dd-5 col-personal-d-5 col-personal-s-5 col-personal-ms-6">
                        {{ $type->name }}
                    </p>
                    <p id="typeCreatedAt"
                       class="col-personal-dx-5 col-personal-dd-5 col-personal-d-5 col-personal-s-5 col-personal-ms-6">
                        {{ $type->created_at }}
                    </p>
                    <p id="objectEdit"
                       class="col-personal-dx-1 col-personal-dd-1 col-personal-d-1 col-personal-s-1 col-personal-ms-1">
                        <a id="openEditTypeForm"
                           data-r-id="{{ $type->id }}">
                            <i class="fas fa-edit"></i>
                        </a>
                    </p>
                    <p id="objectDelete"
                       class="col-personal-dx-1 col-personal-dd-1 col-personal-d-1 col-personal-s-1 col-personal-ms-1">
                        <a id="openDeleteTypeConfirmation" data-r-id="{{ $type->id }}">
                            <i class="fas fa-trash-alt"></i>
                        </a>
                    </p>
                </div>
            @endforeach
            {!! $types->render() !!}
        </div>
    @else
        <div class="no-content">
            <h1> No types found </h1>
        </div>
    @endif

    <div id="newTypeCreation" class="admin-new-edit-type col-personal-12">

        <div class="title-holder">
            <h1> Create a type </h1>
        </div>
        <hr>

        <div id="adminObjectForm">

            <form>
                @csrf
                <div class="form-personal-row">
                    <label class="col-personal-2"> Name </label>
                    <input id="typeNameInput" name="typeNameInput" placeholder="Type in a name"
                           maxlength="15"
                           class="form-control">
                </div>

                <div class="admin-form-button-holder" id="createTypeButton">
                    <div class="admin-object-errors" id="errorsType"></div>
                    <button id="submitType" class="btn btn-primary">
                        {{ __('Submit') }}
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div id="editTypeWindow" class="admin-new-edit-type col-personal-12">
        <div class="title-holder">
            <h1></h1>
        </div>
        <hr>

        <div id="adminObjectForm">

            <form>
                @csrf
                <div class="form-personal-row">
                    <label class="col-personal-2"> Name </label>
                    <input id="typeNameInputEdit" name="typeNameInputEdit" placeholder="Type in a name"
                           maxlength="15"
                           class="form-control">
                </div>

                <div class="admin-form-button-holder" id="editTypeButton">
                    <div class="admin-object-errors" id="errorsEditType"></div>
                    <button id="submitEditedType" class="btn btn-primary">
                        {{ __('Submit') }}
                    </button>
                    <input type="hidden" id="fti" name="fti">
                </div>
            </form>
        </div>

    </div>

    <div id="deleteTypeWindow" class="admin-deletion-type col-personal-12">
        <div class="title-holder">
            <h1></h1>
        </div>
        <hr>

        <label id="infoAboutDeletion"> Also deletes all the restaurants that use this type </label>

        <div class="admin-form-button-holder" id="deleteRestaurantButton">
            <button id="submitDeleteType" class="btn btn-danger">
                {{ __('Delete') }}
            </button>
            <input type="hidden" id="ftid" name="ftid">
        </div>
    </div>

    </body>

@endsection