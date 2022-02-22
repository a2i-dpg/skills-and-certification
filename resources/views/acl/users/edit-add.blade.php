@php
    $edit = !empty($user->id);
    /** @var \App\Models\User $authUser */
    $authUser = \App\Helpers\Classes\AuthHelper::getAuthUser();
@endphp

@extends('master::layouts.master')

@section('title')
    {{ ! $edit ? __('admin.course.edit') : __('admin.course.update') }}
@endsection

@section('content')
    <div class="container-fluid">
        <div class="card card-outline">

            <div class="card-header d-flex justify-content-between custom-bg-gradient-info">
                <h3 class="card-title font-weight-bold text-primary">{{__('admin.users.index')}}</h3>
                <div class="card-tools">
                        <a href="{{route('admin.users.index')}}"
                           class="btn btn-sm btn-outline-primary btn-rounded">
                            <i class="fas fa-backward"></i> {{__('admin.common.back')}}
                        </a>
                    </div>
                </div>

            <div class="card-body">
                <form class="row edit-add-form" method="post"
                      action="{{$edit ? route('admin.users.update', $user->id) : route('admin.users.store')}}"
                      enctype="multipart/form-data">
                    @csrf
                    @if($edit)
                        @method('put')
                    @endif
                    <div class="col-md-12">
                        <div class="row justify-content-center align-content-center">
                            <div class="form-group" style="width: 200px; height: 200px">
                                <div class="input-group">
                                    <div class="profile-upload-section">
                                        <div class="avatar-preview text-center">
                                            <label for="profile_pic">
                                                <img class="img-thumbnail rounded-circle"
                                                     src="{{$edit && $user->profile_pic ? asset('storage/'.$user->profile_pic) : 'https://via.placeholder.com/350x350?text=Profile+Picture'}}"
                                                     style="width: 200px; height: 200px"
                                                     alt="Profile pic"/>
                                                <span class="p-1 bg-gray"
                                                      style="position: absolute; right: 0; bottom: 50%; border: 2px solid #afafaf; border-radius: 50%; overflow: hidden">
                                                        <i class="fa fa-pencil-alt text-white"></i>
                                                    </span>
                                            </label>
                                        </div>
                                        <input type="file" name="profile_pic" style="display: none"
                                               id="profile_pic">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="name">Name <span style="color: red"> * </span></label>
                            <input type="text" class="form-control" id="name"
                                   name="name"
                                   value="{{$edit ? $user->name : old('name')}}"
                                   placeholder="Name">
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="email">{{ __('Email') }} <span style="color: red"> * </span></label>
                            <input type="email" class="form-control" id="email"
                                   name="email"
                                   data-unique-user-email="{{ $edit ? $user->email : '' }}"
                                   value="{{$edit ? $user->email : old('email')}}"
                                   placeholder="{{ __('Email') }}">
                        </div>
                    </div>

                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="user_type_id">{{ __('User Type') }} <span
                                    style="color: red"> * </span></label>
                            <select class="form-control select2"
                                    name="user_type_id"
                                    id="user_type_id"
                                    data-placeholder="{{ __('generic.select_placeholder') }}"
                            >
                                <option value="" selected disabled>{{ __('generic.select_placeholder') }}</option>
                                @foreach($userTypes as $userType)
                                    <option value="{{$userType->code}}"
                                            @if($edit && $user->userType->code == $userType->code) selected @endif>
                                        {{$userType->title}}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    @if($authUser->isUserBelongsToInstitute())
                        <input type="hidden" name="institute_id" id="institute_id" value="{{$authUser->institute_id}}">
                    @else
                        <div class="col-sm-6 institute-id-block"
                             style="display: @if($edit && $user->institute) block @else none @endif;">
                            <div class="form-group">
                                <label for="institute_id">{{ __('Institute') }} <span
                                        style="color: red"> * </span></label>
                                <select class="form-control select2-ajax-wizard"
                                        name="institute_id"
                                        id="institute_id"
                                        data-model="{{base64_encode(\App\Models\Institute::class)}}"
                                        data-label-fields="{title}"
                                        @if($edit && $user->institute)
                                        data-preselected-option="{{json_encode(['text' =>  $user->institute->title, 'id' =>  $user->institute->id])}}"
                                        @endif
                                        data-placeholder="{{ __('generic.select_placeholder') }}"
                                >
                                </select>
                            </div>
                        </div>
                    @endif

                    <div class="col-sm-6 branch-id-block"
                         style="display: @if($edit && ($user->isBranchLevelUser() || $user->isTrainingCenterLevelUser())) block @else none @endif;">
                        <div class="form-group">
                            <label for="branch_id">{{ __('Branch') }} <span
                                    style="color: red"> * </span></label>
                            <select class="form-control select2-ajax-wizard"
                                    name="branch_id"
                                    id="branch_id"
                                    data-model="{{base64_encode(\App\Models\Branch::class)}}"
                                    data-depend-on="institute_id"
                                    data-label-fields="{title}"
                                    @if($edit && $user->branch)
                                    data-preselected-option="{{json_encode(['text' =>  $user->branch->title, 'id' =>  $user->branch->id])}}"
                                    @endif
                                    data-placeholder="{{ __('generic.select_placeholder') }}"
                            >
                            </select>
                        </div>
                    </div>

                    <div class="col-sm-6 training-center-id-block"
                         style="display: @if($edit && ($user->isTrainingCenterLevelUser())) block @else none @endif;">
                        <div class="form-group">
                            <label for="training_center_id">{{ __('TrainingCenter') }} <span
                                    style="color: red"> * </span></label>
                            <select class="form-control select2-ajax-wizard"
                                    name="training_center_id"
                                    id="training_center_id"
                                    data-model="{{base64_encode(\App\Models\TrainingCenter::class)}}"
                                    data-label-fields="{title}"
                                    data-depend-on="institute_id"
                                    data-depend-on-optional="branch_id"
                                    @if($edit && $user->trainingCenter)
                                    data-preselected-option="{{json_encode(['text' =>  $user->trainingCenter->title, 'id' =>  $user->trainingCenter->id])}}"
                                    @endif
                                    data-placeholder="{{ __('generic.select_placeholder') }}"
                            >
                            </select>
                        </div>
                    </div>

                    @if($edit && $authUser->id == $user->id && $authUser->can('changePassword', $user))
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="old_password">{{ __('Old Password') }}<span
                                        style="color: red"> * </span></label>
                                <input type="password" class="form-control" id="old_password"
                                       name="old_password"
                                       value=""
                                       placeholder="{{ __('Old Password') }}">
                            </div>
                        </div>
                    @endif

                    @if(!$edit || $authUser->can('changePassword', $user))
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="password">{{ __($edit ? 'New Password' : 'Password') }} <span
                                        style="color: red"> * </span></label>
                                <input type="password" class="form-control" id="password"
                                       name="password"
                                       value=""
                                       placeholder="{{ __($edit ? 'New Password' : 'Password') }}">
                            </div>
                        </div>

                        <div class="col-sm-6">
                            <div class="form-group">
                                <label
                                    for="password_confirmation">{{ __($edit ? 'Retype New Password' : 'Retype Password') }}
                                    <span style="color: red"> * </span></label>
                                <input type="password" class="form-control" id="password_confirmation"
                                       name="password_confirmation"
                                       value=""
                                       placeholder="{{ __($edit ? 'Retype New Password' : 'Retype Password') }}">
                            </div>
                        </div>
                    @endif

                    @if($edit)
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="row_status">{{ __('generic.row_status') }}</label>
                                <div class="custom-control custom-radio">
                                    <input class="custom-control-input" type="radio" id="row_status_active"
                                           name="row_status"
                                           value="{{ \App\Models\BaseModel::ROW_STATUS_ACTIVE }}"
                                        {{ ($edit && $user->row_status == \App\Models\BaseModel::ROW_STATUS_ACTIVE) || old('row_status') == \App\Models\BaseModel::ROW_STATUS_ACTIVE ? 'checked' : '' }}>
                                    <label for="row_status_active"
                                           class="custom-control-label">{{ __('admin.status.active') }}</label>
                                </div>

                                <div class="custom-control custom-radio">
                                    <input class="custom-control-input" type="radio" id="row_status_inactive"
                                           name="row_status"
                                           value="{{ \App\Models\BaseModel::ROW_STATUS_INACTIVE }}"
                                        {{ ($edit && $user->row_status == \App\Models\BaseModel::ROW_STATUS_INACTIVE) || old('row_status') == \App\Models\BaseModel::ROW_STATUS_INACTIVE ? 'checked' : '' }}>
                                    <label for="row_status_inactive"
                                           class="custom-control-label">{{ __('admin.status.inactive') }}</label>
                                </div>
                            </div>
                        </div>
                    @endif

                    <div class="col-sm-12 text-right">
                        <button type="submit" class="btn btn-success j8">{{$edit ? 'Update' : 'Create'}}</button>
                    </div>
                </form>
            </div><!-- /.card-body -->
            <div class="overlay" style="display: none">
                <i class="fas fa-2x fa-sync-alt fa-spin"></i>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <x-generic-validation-error-toastr></x-generic-validation-error-toastr>

    <script>
        const EDIT = !!'{{$edit}}';
        const INSTITUTE_USER = parseInt('{{ \App\Models\UserType::USER_TYPE_INSTITUTE_USER_CODE }}');
        const isUserBelongsToInstitute = {!! $authUser->isUserBelongsToInstitute() !!}

        $(function () {
            $(document).on('change', "#user_type_id, #institute_id, #branch_id", function () {
                let userType = parseInt($('#user_type_id').val());

                if (userType == {!! \App\Models\UserType::USER_TYPE_INSTITUTE_USER_CODE !!}) {
                    $('.institute-id-block').show();

                    $('.branch-id-block').hide();
                    $('.training-center-id-block').hide();

                    $('#training_center_id').val('');
                    $('#branch_id').val('');
                } else if (userType == {!! \App\Models\UserType::USER_TYPE_BRANCH_USER_CODE !!}) {
                    $('.institute-id-block').show();
                    $('.branch-id-block').show();

                    $('.training-center-id-block').hide();

                    $('#training_center_id').val('');
                } else if (userType == {!! \App\Models\UserType::USER_TYPE_TRAINING_CENTER_USER_CODE !!}) {
                    $('.institute-id-block').show();
                    $('.branch-id-block').show();
                    $('.training-center-id-block').show();
                } else if (userType == {!! \App\Models\UserType::USER_TYPE_TRAINER_USER_CODE !!}) {
                    $('.institute-id-block').show();
                    $('#training_center_id').val('');
                    $('#branch_id').val('');
                } else {
                    $('.institute-id-block').hide();
                    $('.branch-id-block').hide();
                    $('.training-center-id-block').hide();

                    $('#institute_id').val('');
                    $('#training_center_id').val('');
                    $('#branch_id').val('');
                }
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
                            return $('#user_type_id').val() == {!! \App\Models\UserType::USER_TYPE_INSTITUTE_USER_CODE !!} ||
                                $('#user_type_id').val() == {!! \App\Models\UserType::USER_TYPE_BRANCH_USER_CODE !!} ||
                                $('#user_type_id').val() == {!! \App\Models\UserType::USER_TYPE_TRAINER_USER_CODE !!} ||
                                $('#user_type_id').val() == {!! \App\Models\UserType::USER_TYPE_TRAINING_CENTER_USER_CODE !!};
                        }
                    },
                    branch_id: {
                        required: function () {
                            return $('#user_type_id').val() == {!! \App\Models\UserType::USER_TYPE_BRANCH_USER_CODE !!};
                        }
                    },
                    training_center_id: {
                        required: function () {
                            return $('#user_type_id').val() == {!! \App\Models\UserType::USER_TYPE_TRAINING_CENTER_USER_CODE !!};
                        }
                    },
                    old_password: {
                        required: function () {
                            return !!$('#password').val().length;
                        },
                    },
                    password: {
                        required: !EDIT,
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
                    htmlForm.submit();
                }
            });
        });

    </script>
@endpush
