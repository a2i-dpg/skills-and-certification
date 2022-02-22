@php
    $authUser = \App\Helpers\Classes\AuthHelper::getAuthUser();
@endphp

@extends('master::layouts.master')

@section('title')
    Trainer List
@endsection
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header text-primary custom-bg-gradient-info">
                        <h3 class="card-title font-weight-bold">Trainer List</h3>

                        <div class="card-tools">
                            @can('create', \App\Models\User::class)
                                <a href="javascript:;"
                                   class="btn btn-sm btn-outline-primary btn-rounded create-new-button">
                                    <i class="fas fa-plus-circle"></i> {{__('generic.add_new')}}
                                </a>
                            @endcan
                        </div>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <div class="datatable-container">
                            <table id="dataTable" class="table table-bordered table-striped dataTable">
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <x-modal id="edit-add-modal" type="success" xl></x-modal>

    @include('utils.delete-confirm-modal')
@endsection

@push('css')
    <link rel="stylesheet" href="{{asset('/css/datatable-bundle.css')}}">

    <style>
        .has-error {
            position: relative;
            padding: 0 0 12px 0;
        }

        #user_type_id-error {
            position: absolute;
            left: 6px;
            bottom: -9px;
        }
    </style>
@endpush

@push('js')
    <script type="text/javascript" src="{{asset('/js/datatable-bundle.js')}}"></script>

    <script>
        const INSTITUTE_USER = parseInt('{{ \App\Models\UserType::USER_TYPE_INSTITUTE_USER_CODE }}');
        const editAddModal = $("#edit-add-modal");
        const viewModal = $("#user-profile-view-modal");
        const isUserBelongsToInstitute = {!! \App\Helpers\Classes\AuthHelper::getAuthUser()->isUserBelongsToInstitute() !!}

        $(function () {
            let params = serverSideDatatableFactory({
                url: '{{ route('admin.trainers.datatable') }}',
                order: [[2, "asc"]],
                columns: [
                    {
                        title: "SL#",
                        data: null,
                        defaultContent: "SL#",
                        searchable: false,
                        orderable: false,
                        visible: true,
                    },
                    {
                        title: "Name",
                        data: "name",
                        name: "users.name"
                    },
                    {
                        title: "User Type",
                        data: "user_type_title",
                        name: "user_types.title"
                    },
                    {
                        title: "Institute",
                        data: "institute_title",
                        name: "institutes.title",
                        visible: false,
                    },
                    {
                        title: "District",
                        data: "loc_district_name",
                        name: "loc_districts.title",
                        visible: false,
                    },
                    {
                        title: "Action",
                        data: "action",
                        name: "action",
                        orderable: false,
                        searchable: false,
                        visible: true
                    },
                ]
            });
            const datatable = $('#dataTable').DataTable(params);
            bindDatatableSearchOnPresEnterOnly(datatable);

            $(document, 'td').on('click', '.delete', function (e) {
                $('#delete_form')[0].action = $(this).data('action');
                console.log($('#delete_form')[0].action)
                $('#delete_modal').modal('show');
            });

            $(document).on('click', ".dt-view", async function () {
                let url = $(this).data('url');
                let response = await $.get(url);
                viewModal.find('.modal-content').html(response);
                viewModal.modal('show');
            });

            $(document).on('click', ".dt-edit", async function () {
                let response = await $.get($(this).data('url'));
                editAddModal.find('.modal-content').html(response);
                initializeSelect2(".select2-ajax-wizard");
                editAddModal.modal('show');
                registerValidator(true);
                if ($(this).hasClass('button-from-view')) {
                    viewModal.modal('hide');
                }
            });

            if ($(".create-new-button").length) {
                $(document).on('click', ".create-new-button", async function () {
                    let url = '{{route('admin.users.create')}}';
                    let response = await $.get(url);
                    editAddModal.find('.modal-content').html(response);
                    initializeSelect2(".select2-ajax-wizard");
                    editAddModal.modal('show');
                    registerValidator(false);
                });
            }


            function disabledHideFormFields(...fields) {
                fields.forEach(function (field) {
                    field.prop('disabled', true);
                    field.parent().parent().hide();
                });
            }

            function enableShowFormFields(...fields) {
                fields.forEach(function (field) {
                    field.prop('disabled', false);
                    field.parent().parent().show();
                });
            }

            $(document).on('change', "#user_type_id", function () {
                let userType = parseInt($(this).val());

                switch (userType) {
                    case {!! \App\Models\UserType::USER_TYPE_TRAINING_CENTER_USER_CODE !!}:
                        enableShowFormFields($('#loc_division_id'));
                        disabledHideFormFields($('#institute_id'), $('#organization_id'), $('#loc_district_id'));
                        break;
                    case {!! \App\Models\UserType::USER_TYPE_BRANCH_USER_CODE !!}:
                        enableShowFormFields($('#loc_district_id'));
                        disabledHideFormFields($('#institute_id'), $('#organization_id'), $('#loc_division_id'));
                        break;
                    case {!! \App\Models\UserType::USER_TYPE_INSTITUTE_USER_CODE !!}:
                        enableShowFormFields($('#institute_id'));
                        disabledHideFormFields($('#organization_id'), $('#loc_district_id'), $('#loc_division_id'));
                        break;
                    case {!! \App\Models\UserType::USER_TYPE_TRAINER_USER_CODE !!}:
                        isUserBelongsToInstitute ? disabledHideFormFields($('#institute_id')) : enableShowFormFields($('#institute_id'));
                        disabledHideFormFields($('#organization_id'), $('#loc_district_id'), $('#loc_division_id'));
                        break;
                    default:
                        disabledHideFormFields($('#institute_id'), $('#loc_district_id'), $('#organization_id'), $('#loc_division_id'));
                }
            })

            editAddModal.on('hidden.bs.modal', function () {
                editAddModal.find('.modal-content').empty();
            });
            viewModal.on('hidden.bs.modal', function () {
                viewModal.find('.modal-content').empty();
            });

            function readURL(input) {
                if (input.files && input.files[0]) {
                    let reader = new FileReader();
                    reader.onload = function (e) {
                        $('.avatar-preview img').attr('src', e.target.result);
                    }
                    reader.readAsDataURL(input.files[0]); // convert to base64 string
                }
            }

            $(document).on('change', "#profile_pic", function () {
                readURL(this);
            });

            function registerValidator(edit) {
                $(".edit-add-form").validate({
                    rules: {
                        profile_pic: {
                            accept: "image/*",
                        },
                        name: {
                            required: true,
                        },
                        email: {
                            required: true,
                            pattern: /^([a-zA-Z0-9_\.\-\+])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/,
                        },
                        user_type_id: {
                            required: true
                        },
                        institute_id: {
                            required: function () {
                                return $('#user_type_id').val() == {!! \App\Models\UserType::USER_TYPE_INSTITUTE_USER_CODE !!};
                            }
                        },
                        loc_district_id: {
                            required: function () {
                                return $('#user_type_id').val() == {!! \App\Models\UserType::USER_TYPE_BRANCH_USER_CODE !!};
                            }
                        },
                        old_password: {
                            required: function () {
                                return !!$('#password').val().length;
                            },
                        },
                        password: {
                            required: !edit,
                        },
                        password_confirmation: {
                            equalTo: '#password',
                        },
                    },
                    messages: {
                        profile_pic: {
                            accept: "Please input valid image file",
                        },
                    },
                    submitHandler: function (htmlForm) {
                        $('.overlay').show();
                        let formData = new FormData(htmlForm);
                        let jForm = $(htmlForm);
                        $.ajax({
                            url: jForm.prop('action'),
                            method: jForm.prop('method'),
                            data: formData,
                            enctype: 'multipart/form-data',
                            cache: false,
                            contentType: false,
                            processData: false,
                        })
                            .done(function (responseData) {
                                console.log(responseData)
                                if (responseData.message == 'Something wrong. Please try again') {
                                    toastr.error(responseData.message);
                                } else {
                                    toastr.success(responseData.message);
                                }
                                editAddModal.modal('hide');
                            })
                            .fail(window.ajaxFailedResponseHandler)
                            .always(function () {
                                datatable.draw();
                                $('.overlay').hide();
                            });
                        return false;
                    }
                });
            }

        });

    </script>
@endpush
