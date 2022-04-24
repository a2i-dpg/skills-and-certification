@php
    $layout = 'master::layouts.front-end';
@endphp

@extends($layout)

@section('title')
{{$siteSettingInfo->site_title}} :: {{__('frontend.trainee.trainee-registration')}}
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <form action="{{ route('frontend.trainee-registrations.store') }}" method="post"
                      class="trainee-registration-form">
                    @csrf
                    <div class="row justify-content-center py-4">
                        <div class="col-md-8">
                            <div class="card">
                                <div class="card-body">
                                    <div class="row">
                                        <h3 class="ml-2 font-weight-bold">{{__('frontend.trainee.registration')}}</h3>
                                    </div>
                                    <div class="form-row">
                                        <div class="form-group col-md-6">
                                            <label for="name">{{ __('generic.name') }}<span
                                                    class="required">*</span></label>
                                            <input type="text" class="form-control" name="name" id="name"
                                                   placeholder="{{ __('generic.name') }}" value="{{old('name')}}">
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="email">{{ __('generic.email') }}<span
                                                    class="required">*</span></label>
                                            <input type="text" class="form-control" name="email" id="email"
                                                   placeholder="{{ __('generic.email') }}" l value="{{ old('email') }}">
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="mobile">{{ __('generic.mobile') }}<span
                                                    class="required">*</span></label>
                                            <input type="text" class="form-control" name="mobile" id="mobile"
                                                   placeholder="{{ __('generic.mobile') }}" value="{{ old('mobile') }}">
                                        </div>

                                        <div class="form-group col-md-6">
                                            <label for="date_of_birth">{{ __('generic.date_of_birth') }}<span
                                                    class="required">*</span></label>
                                            <input type="text" class="flat-date form-control" name="date_of_birth"
                                                   id="date_of_birth"
                                                   placeholder="{{ __('generic.date_of_birth') }}"
                                                   value="{{ old('date_of_birth') }}">
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
                                                        {{old('gender') == \App\Models\TraineeFamilyMemberInfo::GENDER_MALE ? 'checked' : ''}}>
                                                    <label for="gender_male"
                                                           class="custom-control-label">{{ __('generic.gender.male') }}</label>
                                                </div>
                                                <div class="custom-control custom-radio mr-3">
                                                    <input class="custom-control-input" type="radio" id="gender_female"
                                                           name="gender"
                                                           value="{{ \App\Models\TraineeFamilyMemberInfo::GENDER_FEMALE }}"
                                                        {{ old('gender') == \App\Models\TraineeFamilyMemberInfo::GENDER_FEMALE ? 'checked' : ''}}>
                                                    <label for="gender_female"
                                                           class="custom-control-label">{{__('generic.gender.female')}}</label>
                                                </div>
                                                <div class="custom-control custom-radio mr-3">
                                                    <input class="custom-control-input" type="radio"
                                                           id="gender_transgender"
                                                           name="gender"
                                                           value="{{ \App\Models\TraineeFamilyMemberInfo::GENDER_OTHER }}"
                                                        {{old('gender') == \App\Models\TraineeFamilyMemberInfo::GENDER_OTHER ? 'checked' : ''}}>
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
                                                        {{old('disable_status') == \App\Models\TraineeFamilyMemberInfo::PHYSICALLY_DISABLE_YES ? 'checked' : ''}}>
                                                    <label for="physically_disable_yes"
                                                           class="custom-control-label">{{ __('generic.yes') }}</label>
                                                </div>
                                                <div class="custom-control custom-radio mr-3">
                                                    <input class="custom-control-input" type="radio"
                                                           id="physically_disable_no"
                                                           name="disable_status"
                                                           value="{{ \App\Models\TraineeFamilyMemberInfo::PHYSICALLY_DISABLE_NOT }}"
                                                        {{ old('disable_status') == \App\Models\TraineeFamilyMemberInfo::PHYSICALLY_DISABLE_NOT ? 'checked' : ''}}>
                                                    <label for="physically_disable_no"
                                                           class="custom-control-label">{{__('generic.no')}}</label>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-6 form-group">
                                            <label for="physical_disabilities">{{__('generic.physical_disabilities')}}
                                                <span style="color: red"> * </span>
                                            </label>
                                            <select name="physical_disabilities[]" id="physical_disabilities"
                                                    class="form-control select2" multiple>
                                                @foreach(\App\Models\TraineeFamilyMemberInfo::getPhysicalDisabilityOptions() as $key => $value)
                                                    <option
                                                        value="{{ $key }}" {{ $key == old('physical_disabilities[]') ? 'selected': '' }}>{{ $value }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="form-group col-md-12">
                                            <label for="address">{{ __('generic.address') }}<span
                                                    class="required">*</span></label>
                                            <textarea class="form-control" name="address" id="address">{{ old('address') }}</textarea>
                                        </div>

                                        <div class="form-group col-md-6">
                                            <label
                                                for="password">{{ __('generic.password') }}<span
                                                    class="required">*</span></label>
                                            <input type="password" class="form-control" name="password"
                                                   id="password"
                                                   placeholder="{{ __('generic.password') }}"
                                                   value="{{old('password')}}">
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label
                                                for="password_confirmation">{{ __('generic.retype_password') }}<span
                                                    class="required">*</span></label>
                                            <input type="password" class="form-control"
                                                   name="password_confirmation"
                                                   id="password_confirmation"
                                                   placeholder="{{ __('generic.retype_password') }}"
                                                   value="{{ old('password_confirmation') }}">
                                        </div>
                                        <div class="col-md-12">
                                            <input type="submit" class="btn btn-primary float-right ml-2"
                                                   value="{{ __('generic.registration') }}">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('css')

@endpush
@push('js')
    <x-generic-validation-error-toastr></x-generic-validation-error-toastr>

    <script>
        const SSPRegistrationForm = $('.trainee-registration-form');

        SSPRegistrationForm.validate({
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
                address: {
                    required: true,
                },
                password: {
                    required: true,
                },
                password_confirmation: {
                    equalTo: '#password',
                },
            }
        })

        $(document).ready(function () {
            $('#physical_disabilities').parent().hide();
        });

        $('[name = "physically_disable"]').on('change', function () {
            if (this.value == {!! \App\Models\TraineeFamilyMemberInfo::PHYSICALLY_DISABLE_YES !!}) {
                $('#physical_disabilities').parent().show();
            } else {
                $('#physical_disabilities').parent().hide();
            }
        })

    </script>
@endpush

