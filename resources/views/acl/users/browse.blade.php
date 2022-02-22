@php
    $authUser = \App\Helpers\Classes\AuthHelper::getAuthUser();
@endphp

@extends('master::layouts.master')

@section('title')
    User List
@endsection
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header text-primary custom-bg-gradient-info">
                        <h3 class="card-title font-weight-bold">User List</h3>

                        <div class="card-tools">
                            @can('create', \App\Models\User::class)
                                <a href="{{route('admin.users.create')}}"
                                   class="btn btn-sm btn-outline-primary btn-rounded">
                                    <i class="fas fa-plus-circle"></i> {{__('generic.add_new')}}
                                </a>
                            @endcan
                        </div>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12">
                                <form action="#">
                                    <div class="row">
                                        <div class="col-md-1">
                                            <label class="filter-label text-primary">
                                                <i class="fas fa-sort-amount-down-alt"></i> {{ __('admin.trainee.filter')  }}
                                            </label>
                                        </div>
                                        <div class="col-sm-3">
                                            <div class="form-group">
                                                <select class="form-control select2"
                                                        name="user_type_id"
                                                        id="user_type_id"
                                                        data-placeholder="{{ __('menu.user_type') }}"
                                                >
                                                    <option value="" selected
                                                            disabled>{{ __('menu.user_type') }}</option>
                                                    @foreach($userTypes as $userType)
                                                        <option value="{{$userType->code}}"{{--
                                                                @if($user->userType->code == $userType->code) selected @endif--}}>
                                                            {{$userType->title}}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-3 institute-id-block" style="display: none">
                                            @if($authUser->isUserBelongsToInstitute())
                                                <input type="hidden" id="institute_id" name="institute_id"
                                                       value="{{$authUser->institute_id}}">
                                            @else
                                                <select class="form-control select2-ajax-wizard"
                                                        name="institute_id"
                                                        id="institute_id"
                                                        data-model="{{base64_encode(App\Models\Institute::class)}}"
                                                        data-label-fields="{title}"
                                                        data-dependent-fields="#branch_id|#course_id"
                                                        data-placeholder="Institute"
                                                >
                                                </select>
                                            @endif
                                        </div>
                                        <div class="col-md-2">
                                            <button type="button"
                                                    class="btn btn-warning reset-filter">{{__('admin.trainee.reset')}}</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
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
@endpush

@push('js')
    <script type="text/javascript" src="{{asset('/js/datatable-bundle.js')}}"></script>
    <script>
        $(function () {
            let params = serverSideDatatableFactory({
                url: '{{ route('admin.users.datatable') }}',
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
                        title: "Name (En)",
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
            params.ajax.data = d => {
                let userType = parseInt($('#user_type_id').val());
                let instituteId = parseInt($('#institute_id').val());
                if (userType) {
                    d.user_type_id = userType;
                }
                if (instituteId) {
                    d.institute_id = instituteId;
                }
            };

            const datatable = $('#dataTable').DataTable(params);
            bindDatatableSearchOnPresEnterOnly(datatable);

            $(document).on('change', "#user_type_id, #institute_id", function () {
                let userType = parseInt($('#user_type_id').val());
                let instituteId = parseInt($('#institute_id').val());

                if ([
                    {!! \App\Models\UserType::USER_TYPE_INSTITUTE_USER_CODE !!},
                    {!! \App\Models\UserType::USER_TYPE_BRANCH_USER_CODE !!},
                    {!! \App\Models\UserType::USER_TYPE_TRAINING_CENTER_USER_CODE !!},
                    {!! \App\Models\UserType::USER_TYPE_TRAINER_USER_CODE !!}
                ].includes(userType)) {
                    $('.institute-id-block').show();
                } else {
                    $('.institute-id-block').hide();
                    $('#institute_id').val('');
                }
                datatable.draw();
            });

            $(document).on('click', '.reset-filter', function (e) {
                $('#user_type_id').val('');
                $('#institute_id').val('');
                datatable.draw();
            });

            $(document, 'td').on('click', '.delete', function (e) {
                $('#delete_form')[0].action = $(this).data('action');
                console.log($('#delete_form')[0].action)
                $('#delete_modal').modal('show');
            });
        });
    </script>
@endpush
