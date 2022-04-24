@php
    $currentInstitute = app('currentInstitute');
    $layout = 'master::layouts.front-end';
@endphp
@extends($layout)

@section('title')
{{$siteSettingInfo->site_title}} :: {{__('generic.youth_profile')}}
@endsection

@push('css')
    <style>

    </style>
@endpush

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="card mt-4">
                    <div class="card-header">
                        <div class="card-title text-bold">{{__('frontend.trainee.edit_personal_information')}}</div>
                        <div class="card-tools">
                            <a href="{{route('frontend.trainee')}}"
                               class="btn btn-sm btn-outline-primary btn-rounded">
                                <i class="fas fa-backward"></i> {{ __('generic.back_to_profile') }}
                            </a>
                        </div>
                    </div>

                    <div class="card-body">
                        <form action="{{ route('frontend.update-personal-info', ['id' => $trainee->id]) }}"
                              method="post"
                              enctype="multipart/form-data" class="edit-form">
                            @csrf
                            @method('put')

                            <div class="col-md-12">
                                <div class="row justify-content-center align-content-center">
                                    <div class="form-group" style="width: 200px; height: 200px">
                                        <div class="input-group">
                                            <div class="profile-upload-section">
                                                <div class="avatar-preview text-center">
                                                    <label for="profile_pic">
                                                        <img class="img-thumbnail rounded-circle"
                                                             src="{{$trainee->profile_pic ? asset('storage/'.$trainee->profile_pic) : 'https://via.placeholder.com/350x350?text=Profile+Picture'}}"
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

                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label for="name">{{ __('generic.name') }}<span
                                            class="required">*</span> :</label>
                                    <input type="text" class="form-control" name="name" id="name"
                                           placeholder="{{ __('generic.name') }}" value="{{ $trainee->name }}">
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="email">{{ __('generic.email') }}<span
                                            class="required">*</span> :</label>
                                    <input type="text" class="form-control" name="email" id="email"
                                           placeholder="{{ __('generic.email') }}" value="{{ $trainee->email }}">
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="mobile">{{ __('generic.mobile') }}<span
                                            class="required">*</span> :</label>
                                    <input type="text" class="form-control" name="mobile" id="mobile"
                                           placeholder="{{ __('generic.mobile') }}" value="{{ $trainee->mobile  }}">
                                </div>

                                <div class="form-group col-md-6">
                                    <label for="date_of_birth">{{ __('generic.date_of_birth') }}<span
                                            class="required">*</span> :</label>
                                    <input type="text" class="flat-date form-control" name="date_of_birth"
                                           id="date_of_birth"
                                           placeholder="{{ __('generic.date_of_birth') }}"
                                           value="{{ $trainee->date_of_birth }}">
                                </div>

                                <div class="form-group col-md-6">
                                    <label for="gender">{{ __('generic.gender') }}<span
                                            class="required">*</span> :</label>
                                    <div
                                        class="d-md-flex form-control"
                                        style="display: inline-table;">
                                        <div class="custom-control custom-radio mr-3">
                                            <input class="custom-control-input" type="radio" id="gender_male"
                                                   name="gender"
                                                   value="{{ \App\Models\TraineeFamilyMemberInfo::GENDER_MALE }}"
                                                {{ $trainee->gender == \App\Models\TraineeFamilyMemberInfo::GENDER_MALE ? 'checked' : ''}}>
                                            <label for="gender_male"
                                                   class="custom-control-label">{{ __('generic.gender.male') }}</label>
                                        </div>
                                        <div class="custom-control custom-radio mr-3">
                                            <input class="custom-control-input" type="radio" id="gender_female"
                                                   name="gender"
                                                   value="{{ \App\Models\TraineeFamilyMemberInfo::GENDER_FEMALE }}"
                                                {{ $trainee->gender == \App\Models\TraineeFamilyMemberInfo::GENDER_FEMALE ? 'checked' : ''}}>
                                            <label for="gender_female"
                                                   class="custom-control-label">{{__('generic.gender.female')}}</label>
                                        </div>
                                        <div class="custom-control custom-radio mr-3">
                                            <input class="custom-control-input" type="radio"
                                                   id="gender_transgender"
                                                   name="gender"
                                                   value="{{ \App\Models\TraineeFamilyMemberInfo::GENDER_OTHER }}"
                                                {{$trainee->gender == \App\Models\TraineeFamilyMemberInfo::GENDER_OTHER ? 'checked' : ''}}>
                                            <label for="gender_transgender"
                                                   class="custom-control-label">{{ __('generic.other') }}</label>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group col-md-6">
                                    <label for="disable_status">{{ __('generic.disability') }}<span
                                            class="required">*</span> :</label>
                                    <div
                                        class="d-md-flex form-control"
                                        style="display: inline-table;">
                                        <div class="custom-control custom-radio mr-3">
                                            <input class="custom-control-input" type="radio"
                                                   id="physically_disable_yes"
                                                   name="disable_status"
                                                   value="{{ \App\Models\TraineeFamilyMemberInfo::PHYSICALLY_DISABLE_YES }}"
                                                {{$trainee->disable_status == \App\Models\TraineeFamilyMemberInfo::PHYSICALLY_DISABLE_YES ? 'checked' : ''}}>
                                            <label for="physically_disable_yes"
                                                   class="custom-control-label">{{ __('generic.yes') }}</label>
                                        </div>
                                        <div class="custom-control custom-radio mr-3">
                                            <input class="custom-control-input" type="radio"
                                                   id="physically_disable_no"
                                                   name="disable_status"
                                                   value="{{ \App\Models\TraineeFamilyMemberInfo::PHYSICALLY_DISABLE_NOT }}"
                                                {{ $trainee->disable_status == \App\Models\TraineeFamilyMemberInfo::PHYSICALLY_DISABLE_NOT ? 'checked' : ''}}>
                                            <label for="physically_disable_no"
                                                   class="custom-control-label">{{__('generic.no')}}</label>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group col-md-12">
                                    <label for="address">{{ __('generic.address') }}</label>
                                    <textarea class="form-control" name="address" placeholder="Address"
                                              id="address">{{ old('address', $trainee->address) }}</textarea>
                                </div>

                                <div class="form-group col-md-6">
                                    <label
                                        for="password">{{ __('generic.old_password') }}</label>
                                    <input type="password" class="form-control" name="old_password"
                                           id="old_password"
                                           placeholder="{{ __('generic.old_password') }}"
                                    >
                                </div>

                                <div class="form-group col-md-6">
                                    <label
                                        for="password">{{ __('generic.password') }}</label>
                                    <input type="password" class="form-control" name="password"
                                           id="password"
                                           placeholder="{{ __('generic.password') }}"
                                           value="{{old('password')}}">
                                </div>
                                <div class="form-group col-md-6">
                                    <label
                                        for="password_confirmation">{{ __('generic.retype_password') }}</label>
                                    <input type="password" class="form-control"
                                           name="password_confirmation"
                                           id="password_confirmation"
                                           placeholder="{{ __('generic.retype_password') }}"
                                           value="{{ old('password_confirmation') }}">
                                </div>


                                <div class="col-md-12">
                                    <input type="submit" class="btn btn-primary float-right ml-2"
                                           value="{{ __('admin.common.edit') }}">
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <x-generic-validation-error-toastr></x-generic-validation-error-toastr>

    <script>
        const editForm = $('.edit-form');

        editForm.validate({
            errorPlacement: function(error, element) {
                if (element.attr('type') == 'radio') {
                    error.appendTo( element.parent().parent().parent());
                } else {
                    error.appendTo(element.closest('.form-group'));
                }
            },
            rules: {
                name: {
                    required: true,
                },
                email: {
                    required: true,
                    pattern: /^([a-zA-Z0-9_\.\-\+])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/,
                },
                mobile: {
                    required: true,
                },
                date_of_birth: {
                    required: true,
                },
                gender: {
                    required: true,
                },
                disable_status: {
                    required: true,
                },
                old_password: {
                    required: false,
                },
                password: {
                    required: false,
                },
                password_confirmation: {
                    equalTo: '#password',
                },
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
    </script>
@endpush
