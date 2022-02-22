@php
    $edit = !empty($institute->id);
@endphp

@extends('master::layouts.master')

@section('title')
    {{ 'Trainer Information' }}
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card card-outline">
                    <div class="card-header text-primary custom-bg-gradient-info">
                        <h3 class="card-title font-weight-bold text-primary">{{ 'Trainer Information'}}</h3>
                        <div class="card-tools">
                            @can('viewAny', App\Models\User::class)
                                <a href="{{route('admin.users.index')}}"
                                   class="btn btn-sm btn-outline-primary btn-rounded">
                                    <i class="fas fa-backward"></i> {{ __('generic.back_to_list') }}
                                </a>
                            @endcan
                        </div>
                    </div>

                    <div class="card-body">
                        <form class="row edit-add-form" method="post"
                              enctype="multipart/form-data"
                              action="{{route('admin.trainer-info.store')}}">
                            @csrf
                            @if($edit)
                                @method('put')
                            @endif

                            <div class="col-md-12">
                                <div class="card card-outline">
                                    <div class="card-header text-primary custom-bg-gradient-info">
                                        <h3 class="card-title font-weight-bold text-primary">{{ 'Personal Information'}}</h3>
                                    </div>
                                    <div class="card-body">
                                        <div class="form-row">
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label for="name">{{ __('generic.name') }}<span
                                                            style="color: red"> * </span></label>
                                                    <input type="text" class="form-control" id="name"
                                                           name="name"
                                                           value="{{ $trainer ? $trainer->name : old('title') }}"
                                                           placeholder="{{ __('generic.name') }}">
                                                </div>
                                            </div>

                                            <input type="hidden" name="institute_id" id="institute_id"
                                                   value="{{ $trainer->institute_id }}">
                                            <input type="hidden" name="trainer_id" id="trainer_id"
                                                   value="{{ $trainer->id }}">

                                            <div
                                                class="form-group col-md-6 {{ !empty($authYouth)? ($authYouth->email?'read-only-input-field':''):'' }}">
                                                <label for="email">{{ __('generic.email') }} <span
                                                        class="required">*</span>
                                                    :</label>
                                                <input type="text" class="form-control" name="email" id="email"
                                                       value="{{ $trainer ? $trainer->email :old('email') }}"
                                                       placeholder="{{ __('generic.email') }}">
                                            </div>

                                            <div
                                                class="form-group col-md-6 {{ !empty($authYouth)? ($authYouth->mobile?'read-only-input-field':''):'' }}">
                                                <label for="mobile">{{__('generic.mobile') }} <span
                                                        class="required">*</span>
                                                    :</label>
                                                <input type="text" class="form-control" name="mobile" id="mobile"
                                                       value="{{ !empty($trainer->trainerPersonalInformation) ? $trainer->trainerPersonalInformation->mobile :old('mobile') }}"
                                                       placeholder="{{__('generic.mobile') }}">
                                            </div>

                                            <div class="form-group col-md-6">
                                                <label for="gender">{{__('generic.gender') }}:</label>
                                                <div
                                                    class="d-md-flex form-control {{ !empty($traineeSelfInfo)? ($traineeSelfInfo->gender?'read-only-input-field':''):'' }}"
                                                    style="display: inline-table;">
                                                    <div class="custom-control custom-radio mr-3">
                                                        <input class="custom-control-input" type="radio"
                                                               id="gender_male"
                                                               {{ !empty($trainer->trainerPersonalInformation) && $trainer->trainerPersonalInformation->gender == 1?' checked' : '' }}
                                                               name="gender"
                                                               value="{{ \App\Models\TraineeFamilyMemberInfo::GENDER_MALE }}"
                                                            {{old('gender') == \App\Models\TraineeFamilyMemberInfo::GENDER_MALE ? 'checked' : ''}}>
                                                        <label for="gender_male"
                                                               class="custom-control-label">{{__('generic.gender.male') }}</label>
                                                    </div>
                                                    <div class="custom-control custom-radio mr-3">
                                                        <input class="custom-control-input" type="radio"
                                                               id="gender_female"
                                                               {{ !empty($trainer->trainerPersonalInformation) && $trainer->trainerPersonalInformation->gender == 2?' checked' : '' }}
                                                               name="gender"
                                                               value="{{ \App\Models\TraineeFamilyMemberInfo::GENDER_FEMALE }}"
                                                            {{ old('gender') == \App\Models\TraineeFamilyMemberInfo::GENDER_FEMALE ? 'checked' : ''}}>
                                                        <label for="gender_female"
                                                               class="custom-control-label">{{__('generic.gender.female') }}</label>
                                                    </div>
                                                    <div class="custom-control custom-radio mr-3">
                                                        <input class="custom-control-input" type="radio"
                                                               id="gender_transgender"
                                                               {{ !empty($trainer->trainerPersonalInformation) && $trainer->trainerPersonalInformation->gender == 4?' checked' : '' }}
                                                               name="gender"
                                                               value="{{ \App\Models\TraineeFamilyMemberInfo::GENDER_TRANSGENDER }}"
                                                            {{old('gender') == \App\Models\TraineeFamilyMemberInfo::GENDER_TRANSGENDER ? 'checked' : ''}}>
                                                        <label for="gender_transgender"
                                                               class="custom-control-label">{{__('generic.gender.hermaphrodite') }}</label>
                                                    </div>
                                                </div>
                                            </div>

                                            <div
                                                class="form-group col-md-6 {{ !empty($trainer->trainerPersonalInformation)? ($trainer->trainerPersonalInformation->date_of_birth?'read-only-input-field':''):'' }}">
                                                <label for="date_of_birth">{{__('generic.date_of_birth') }}:</label>
                                                <input type="text" class="form-control flat-date" name="date_of_birth"
                                                       id="date_of_birth"
                                                       value="{{ !empty($trainer->trainerPersonalInformation)? $trainer->trainerPersonalInformation->date_of_birth :old('date_of_birth') }}"
                                                       placeholder="{{__('generic.date_of_birth') }}">
                                            </div>

                                            <div class="form-group col-md-6">
                                                <label for="marital_status">{{__('generic.marital_status') }} :</label>
                                                <div class="form-control">
                                                    <div class="custom-control  custom-radio d-inline-block mr-3">
                                                        <input class="custom-control-input" type="radio"
                                                               id="marital_status_married"
                                                               name="marital_status"
                                                               value="{{ \App\Models\TraineeFamilyMemberInfo::MARITAL_STATUS_MARRIED }}"
                                                            {{ old('marital_status') == \App\Models\TraineeFamilyMemberInfo::MARITAL_STATUS_MARRIED ? 'checked' : '' }}
                                                            {{ !empty($trainer->trainerPersonalInformation) && $trainer->trainerPersonalInformation->marital_status == \App\Models\TraineeFamilyMemberInfo::MARITAL_STATUS_MARRIED?' checked' : '' }}
                                                        >
                                                        <label for="marital_status_married"
                                                               class="custom-control-label">{{__('generic.marital_status.married') }}</label>
                                                    </div>
                                                    <div class="custom-control custom-radio d-inline-block mr-3">
                                                        <input class="custom-control-input" type="radio"
                                                               id="marital_status_single"
                                                               name="marital_status"
                                                               value="{{ \App\Models\TraineeFamilyMemberInfo:: MARITAL_STATUS_SINGLE}}"
                                                            {{ old('marital_status') == \App\Models\TraineeFamilyMemberInfo::MARITAL_STATUS_SINGLE ? 'checked' : '' }}
                                                            {{ !empty($trainer->trainerPersonalInformation) && $trainer->trainerPersonalInformation->marital_status == \App\Models\TraineeFamilyMemberInfo::MARITAL_STATUS_SINGLE?' checked' : '' }}
                                                        >
                                                        <label for="marital_status_single"
                                                               class="custom-control-label">{{__('generic.marital_status.unmarried') }}</label>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="form-group col-md-6">
                                                <label for="religion">{{ __('generic.religion') }}:</label>
                                                <div class="d-md-flex form-control" style="display: inline-table;">
                                                    <div class="custom-control custom-radio mr-3">
                                                        <input class="custom-control-input" type="radio"
                                                               id="religion_islam"
                                                               name="religion"
                                                               value="{{ \App\Models\TraineeFamilyMemberInfo::RELIGION_ISLAM }}"
                                                            {{ old('religion') == \App\Models\TraineeFamilyMemberInfo::RELIGION_ISLAM ? 'checked' : '' }}
                                                            {{ !empty($trainer->trainerPersonalInformation) && $trainer->trainerPersonalInformation->religion == \App\Models\TraineeFamilyMemberInfo::RELIGION_ISLAM ?' checked' : '' }}
                                                        >
                                                        <label for="religion_islam"
                                                               class="custom-control-label">{{ __('generic.religion.islam') }}</label>
                                                    </div>
                                                    <div class="custom-control custom-radio mr-3">
                                                        <input class="custom-control-input" type="radio"
                                                               id="religion_hindu"
                                                               name="religion"
                                                               value="{{ \App\Models\TraineeFamilyMemberInfo::RELIGION_HINDU }}"
                                                            {{ old('religion') == \App\Models\TraineeFamilyMemberInfo::RELIGION_HINDU ? 'checked' : '' }}
                                                            {{ !empty($trainer->trainerPersonalInformation) && $trainer->trainerPersonalInformation->religion == \App\Models\TraineeFamilyMemberInfo::RELIGION_HINDU ?' checked' : '' }}
                                                        >

                                                        <label for="religion_hindu"
                                                               class="custom-control-label">{{ __('generic.religion.hindu') }}</label>
                                                    </div>
                                                    <div class="custom-control custom-radio mr-3">
                                                        <input class="custom-control-input" type="radio"
                                                               id="religion_christian"
                                                               name="religion"
                                                               value="{{ \App\Models\TraineeFamilyMemberInfo::RELIGION_CHRISTIAN }}"
                                                            {{ old('religion') == \App\Models\TraineeFamilyMemberInfo::RELIGION_CHRISTIAN ? 'checked' : '' }}
                                                            {{ !empty($trainer->trainerPersonalInformation) && $trainer->trainerPersonalInformation->religion == \App\Models\TraineeFamilyMemberInfo::RELIGION_CHRISTIAN ?' checked' : '' }}
                                                        >
                                                        <label for="religion_christian"
                                                               class="custom-control-label">{{ __('generic.religion.christian') }}</label>
                                                    </div>

                                                    <div class="custom-control custom-radio mr-3">
                                                        <input class="custom-control-input" type="radio"
                                                               id="religion_buddhist"
                                                               name="religion"
                                                               value="{{ \App\Models\TraineeFamilyMemberInfo::RELIGION_BUDDHIST }}"
                                                            {{ old('religion') == \App\Models\TraineeFamilyMemberInfo::RELIGION_BUDDHIST ? 'checked' : '' }}
                                                            {{ !empty($trainer->trainerPersonalInformation) && $trainer->trainerPersonalInformation->religion == \App\Models\TraineeFamilyMemberInfo::RELIGION_BUDDHIST ?' checked' : '' }}
                                                        >
                                                        <label for="religion_buddhist"
                                                               class="custom-control-label">{{ __('generic.religion.buddhist') }}</label>
                                                    </div>

                                                    <div class="custom-control custom-radio mr-3">
                                                        <input class="custom-control-input" type="radio"
                                                               id="religion_jain"
                                                               name="religion"
                                                               value="{{ \App\Models\TraineeFamilyMemberInfo::RELIGION_JAIN }}"
                                                            {{ old('religion') == \App\Models\TraineeFamilyMemberInfo::RELIGION_JAIN ? 'checked' : '' }}
                                                            {{ !empty($trainer->trainerPersonalInformation) && $trainer->trainerPersonalInformation->religion == \App\Models\TraineeFamilyMemberInfo::RELIGION_JAIN ?' checked' : '' }}
                                                        >
                                                        <label for="religion_jain"
                                                               class="custom-control-label">{{ __('generic.religion.jain') }}</label>
                                                    </div>

                                                    <div class="custom-control custom-radio mr-3">
                                                        <input class="custom-control-input" type="radio"
                                                               id="religion_other"
                                                               name="religion"
                                                               value="{{ \App\Models\TraineeFamilyMemberInfo::RELIGION_OTHERS }}"
                                                            {{ old('religion') == \App\Models\TraineeFamilyMemberInfo::RELIGION_OTHERS ? 'checked' : '' }}
                                                            {{ !empty($trainer->trainerPersonalInformation) && $trainer->trainerPersonalInformation->religion == \App\Models\TraineeFamilyMemberInfo::RELIGION_OTHERS ?' checked' : '' }}
                                                        >
                                                        <label for="religion_other"
                                                               class="custom-control-label">{{ __('generic.religion.other') }}</label>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="form-group col-md-6">
                                                <label for="nationality">{{ __('generic.nationality')}}</label>
                                                <select class="select2" name="nationality" id="nationality">
                                                    <option value=""></option>
                                                    <option
                                                        value="bd" {{ !empty($trainer->trainerPersonalInformation) && $trainer->trainerPersonalInformation->nationality == 'bd' ? ' checked' : '' }}
                                                    >{{__('generic.bangladeshi')}}
                                                    </option>
                                                    <option
                                                        value="others" {{ !empty($trainer->trainerPersonalInformation) && $trainer->trainerPersonalInformation->nationality == 'bd' ? ' checked' : '' }}>
                                                        {{__('generic.others')}}
                                                    </option>
                                                </select>
                                            </div>

                                            <div class="form-group col-md-6">
                                                <label for="nid_no">{{ __('generic.nid_no') }}</label>
                                                <input type="text" class="form-control mb-2" name="nid_no"
                                                       id="nid_no"
                                                       value="{{ !empty($trainer->trainerPersonalInformation) ? $trainer->trainerPersonalInformation->nid_no : old('nid_no') }}"
                                                       placeholder="{{__('generic.nid_no')}}">
                                            </div>

                                            <div class="form-group col-md-6">
                                                <label for="nid_no">{{ __('generic.birth_certificate') }}</label>
                                                <input type="text" class="form-control mb-2"
                                                       name="birth_registration_no"
                                                       id="birth_registration_no"
                                                       value="{{ !empty($trainer->trainerPersonalInformation) ? $trainer->trainerPersonalInformation->birth_registration_no : old('birth_registration_no') }}"
                                                       placeholder="{{ __('generic.birth_certificate')}}">
                                            </div>

                                            <div class="form-group col-md-6">
                                                <label for="nid_no">{{ __('generic.passport_no') }}</label>
                                                <input type="text" class="form-control mb-2"
                                                       name="passport_no"
                                                       id="passport_no"
                                                       value="{{ !empty($trainer->trainerPersonalInformation) ? $trainer->trainerPersonalInformation->passport_no : old('passport_no') }}"
                                                       placeholder="{{ __('generic.passport_no')}}">
                                            </div>

                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label
                                                        for="present_address">{{ __('generic.present_address')}}</label>
                                                    <textarea id="present_address" name="present_address"
                                                              class="form-control" rows="3"
                                                              placeholder="{{ __('generic.Ex')  }}: {{__('generic.holding_no')}}/{{__('generic.village')}}/{{__('generic.upazila')}}/{{__('generic.district')}}">{{ isset($trainer->trainerPersonalInformation) ? $trainer->trainerPersonalInformation->present_address : '' }}</textarea>
                                                </div>
                                            </div>

                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label
                                                        for="permanent_address">{{ __('generic.permanent_address')}}</label>
                                                    <textarea id="permanent_address" name="permanent_address"
                                                              class="form-control" rows="3"
                                                              placeholder="{{ __('generic.Ex')  }}: {{__('generic.holding_no')}}/{{__('generic.village')}}/{{__('generic.upazila')}}/{{__('generic.district')}}">{{ isset($trainer->trainerPersonalInformation) ? $trainer->trainerPersonalInformation->permanent_address : '' }}</textarea>
                                                </div>
                                            </div>
                                        </div>


                                        <div class="form-row justify-content-between">
                                            <div class="form-group col-md-6">
                                                <label for="profile_pic"> {{ __('generic.profile_pic')}}</label>
                                                <div class="input-group">
                                                    <div class="profile-upload-section">
                                                        <div class="avatar-preview profile_pic text-center">
                                                            <label for="profile_pic">
                                                                <img class="figure-img"
                                                                     src={{ !empty($trainer->profile_pic) ? asset('storage/'. $trainer->profile_pic) : ($trainer->trainerPersonalInformation && $trainer->trainerPersonalInformation->profile_pic ? asset('storage/'. $trainer->trainerPersonalInformation->profile_pic) :  "https://via.placeholder.com/350x350?text=Trainer+Profile_pic")}}
                                                                         width="300" height="300"
                                                                     alt="Profile pic"/>
                                                                <span class="p-1 bg-gray"
                                                                      style="position: relative; right: 0; bottom: 50%; border: 2px solid #afafaf; border-radius: 50%;margin-left: -31px; overflow: hidden">
                                                        <i class="fa fa-pencil-alt text-white"></i>
                                                    </span>
                                                            </label>
                                                        </div>
                                                        <input type="file" name="profile_pic" style="display: none"
                                                               id="profile_pic">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label for="signature_pic">{{ __('generic.signature')}}</label>
                                                <div class="input-group">
                                                    <div class="profile-upload-section">
                                                        <div class="avatar-preview signature_pic text-center">
                                                            <label for="signature_pic">
                                                                <img class="loading-img"
                                                                     src={{ $trainer->trainerPersonalInformation && $trainer->trainerPersonalInformation->signature_pic ? asset('storage/'. $trainer->trainerPersonalInformation->signature_pic) :  "https://via.placeholder.com/350x350?text=Trainer+signature"}}
                                                                         width="250" height="100"
                                                                     alt="Signature pic"/>
                                                                <span class="p-1 bg-gray"
                                                                      style="position: relative; right: 0; bottom: 50%; border: 2px solid #afafaf; border-radius: 50%;margin-left: -31px; overflow: hidden">
                                                        <i class="fa fa-pencil-alt text-white"></i> </span>
                                                            </label>
                                                        </div>
                                                        <input type="file" name="signature_pic"
                                                               style="display: none"
                                                               id="signature_pic">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="card">
                                    <div class="card-header custom-bg-gradient-info academic-qualifications">
                                        <h3 class="card-title font-weight-bold text-primary"><i
                                                class="fa fa-address-book"> </i> {{ __('generic.academic_qualification')}}
                                        </h3>
                                    </div>
                                    <div class="card-body row">
                                        <div class="col-md-6 academic-qualification-jsc mb-2">
                                            <div class="card col-md-12 custom-bg-gradient-info" style="height: 100%;">
                                                <div class="card-header">
                                                    <h3 class="card-title text-primary d-inline-flex">{{ __('admin.examination_name.jsc')}}
                                                        /{{ __('generic.equivalent')}}
                                                        ({{ __('generic.pass')}})</h3>
                                                </div>
                                                <div class="card-body jsc_collapse hide">

                                                    <input type="hidden" name="academicQualification[jsc][examination]"
                                                           value="{{ \App\Models\TraineeAcademicQualification::EXAMINATION_JSC }}">

                                                    <div class="form-row form-group">
                                                        <label for="jsc_examination_name"
                                                               class="col-md-4 col-form-label">{{ __('generic.examination')}}
                                                        </label>
                                                        <div class="col-md-8">
                                                            <select name="academicQualification[jsc][examination_name]"
                                                                    id="jsc_examination_name"
                                                                    class="select2 form-control">
                                                                <option value=""></option>
                                                                @foreach(\App\Models\TraineeAcademicQualification::getJSCExaminationOptions() as $key => $value)
                                                                    <option
                                                                        value="{{ $key }}" {{ isset($academicQualifications[\App\Models\TraineeAcademicQualification::EXAMINATION_JSC]) && $academicQualifications[\App\Models\TraineeAcademicQualification::EXAMINATION_JSC]->examination_name == $key ? 'selected' : ''}} {{ old('academicQualification.jsc.examination_name') == $key ? 'selected' : '' }}>
                                                                        {{ $value }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <div class="col-md-4"></div>
                                                    </div>

                                                    <div class="form-row form-group mt-2">
                                                        <label for="jsc_board"
                                                               class="col-md-4 col-form-label">{{ __('generic.board')}}
                                                        </label>
                                                        <div class="col-md-8">
                                                            <select name="academicQualification[jsc][board]"
                                                                    id="jsc_board"
                                                                    class="select2">
                                                                @foreach(\App\Models\TraineeAcademicQualification::getExaminationBoardOptions() as $key => $value)
                                                                    <option value=""></option>
                                                                    <option
                                                                        value="{{ $key }}" {{ isset($academicQualifications[\App\Models\TraineeAcademicQualification::EXAMINATION_JSC]) && $academicQualifications[\App\Models\TraineeAcademicQualification::EXAMINATION_JSC]->board == $key ? 'selected' : ''}} {{ old('academicQualification.jsc.board') == $key ? 'selected' : '' }}>
                                                                        {{ $value }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <div class="col-md-4"></div>
                                                    </div>

                                                    <div class="form-row form-group mt-2">
                                                        <label for="jsc_roll"
                                                               class="col-md-4 col-form-label">{{ __('generic.roll_no')}}</label>
                                                        <div class="col-md-8">
                                                            <input type="text"
                                                                   name="academicQualification[jsc][roll_no]"
                                                                   id="jsc_roll" class="form-control"
                                                                   value="{{ isset($academicQualifications[\App\Models\TraineeAcademicQualification::EXAMINATION_JSC]) ? $academicQualifications[\App\Models\TraineeAcademicQualification::EXAMINATION_JSC]->roll_no :  old('academicQualification.jsc.roll_no') }}">
                                                        </div>
                                                        <div class="col-md-4"></div>
                                                    </div>

                                                    <div class="form-row form-group mt-2">
                                                        <label for="jsc_reg_no"
                                                               class="col-md-4 col-form-label">{{ __('generic.reg_no')}}</label>
                                                        <div class="col-md-8">
                                                            <input type="text" id="jsc_reg_no"
                                                                   name="academicQualification[jsc][reg_no]"
                                                                   class="form-control"
                                                                   value="{{ isset($academicQualifications[\App\Models\TraineeAcademicQualification::EXAMINATION_JSC]) ? $academicQualifications[\App\Models\TraineeAcademicQualification::EXAMINATION_JSC]->reg_no :  old('academicQualification.jsc.reg_no') }}">
                                                        </div>
                                                        <div class="col-md-4"></div>
                                                    </div>

                                                    <input type="hidden" name="academicQualification[jsc][result]"
                                                           value="5">
                                                    <div class="form-row form-group mt-2">
                                                        <label for="jsc_result"
                                                               class="col-md-4 col-form-label">{{ __('generic.result')}}
                                                        </label>
                                                        <div class="col-md-8">
                                                            <input type="number"
                                                                   name="academicQualification[jsc][grade]"
                                                                   id="jsc_gpa" class="form-control"
                                                                   width="10" placeholder="{{ __('generic.result')}}"
                                                                   value="{{ isset($academicQualifications[\App\Models\TraineeAcademicQualification::EXAMINATION_JSC]) ? $academicQualifications[\App\Models\TraineeAcademicQualification::EXAMINATION_JSC]->grade :  old('academicQualification.jsc.grade') }}">
                                                        </div>
                                                        <div class="col-md-4"></div>
                                                    </div>

                                                    <div class="form-row form-group mt-2">
                                                        <label for="jsc_passing_year"
                                                               class="col-md-4 col-form-label">{{ __('generic.passing_year')}}</label>
                                                        <div class="col-md-8">
                                                            <select name="academicQualification[jsc][passing_year]"
                                                                    id="jsc_passing_year" class="select2">
                                                                <option value=""></option>
                                                                @for($i = now()->format('Y') - 50; $i <= now()->format('Y'); $i++)
                                                                    <option
                                                                        value="{{ $i }}" {{ isset($academicQualifications[\App\Models\TraineeAcademicQualification::EXAMINATION_JSC]) && $academicQualifications[\App\Models\TraineeAcademicQualification::EXAMINATION_JSC]->passing_year == $i ? 'selected' : ''}} >{{ $i }}</option>
                                                                @endfor
                                                            </select>
                                                        </div>
                                                        <div class="col-md-4"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6 academic-qualification-ssc mb-2">
                                            <div class="card col-md-12 custom-bg-gradient-info" style="height: 100%;">
                                                <div class="card-header">
                                                    <h3 class="card-title text-primary d-inline-flex">
                                                        {{ __('admin.examination_name.ssc')}}
                                                        /{{ __('generic.equivalent')}}/{{ __('generic.o-level')}}
                                                        ({{ __('generic.pass')}}) </h3>
                                                </div>
                                                <div class="card-body ssc_collapse {{--collapse--}} hide">

                                                    <input type="hidden" name="academicQualification[ssc][examination]"
                                                           value="{{ \App\Models\TraineeAcademicQualification::EXAMINATION_SSC }}">

                                                    <div class="form-row form-group">
                                                        <label for="ssc_examination_name"
                                                               class="col-md-4 col-form-label">{{ __('generic.examination')}}
                                                        </label>
                                                        <div class="col-md-8">
                                                            <select name="academicQualification[ssc][examination_name]"
                                                                    id="ssc_examination_name"
                                                                    class="select2 form-control">
                                                                <option value=""></option>
                                                                @foreach(\App\Models\TraineeAcademicQualification::getSSCExaminationOptions() as $key => $value)
                                                                    <option
                                                                        value="{{ $key }}" {{ isset($academicQualifications[\App\Models\TraineeAcademicQualification::EXAMINATION_SSC]) && $academicQualifications[\App\Models\TraineeAcademicQualification::EXAMINATION_SSC]->examination_name == $key ? 'selected' : ''}} {{ old('academicQualification.ssc.examination_name') == $key ? 'selected' : '' }}>
                                                                        {{ $value }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <div class="col-md-4"></div>
                                                    </div>

                                                    <div class="form-row form-group mt-2">
                                                        <label for="ssc_board"
                                                               class="col-md-4 col-form-label">{{ __('generic.board')}}</label>
                                                        <div class="col-md-8">
                                                            <select name="academicQualification[ssc][board]"
                                                                    id="ssc_board"
                                                                    class="select2">
                                                                <option value=""></option>

                                                                @foreach(\App\Models\TraineeAcademicQualification::getExaminationBoardOptions() as $key => $value)
                                                                    <option
                                                                        value="{{ $key }}" {{ isset($academicQualifications[\App\Models\TraineeAcademicQualification::EXAMINATION_SSC]) && $academicQualifications[\App\Models\TraineeAcademicQualification::EXAMINATION_SSC]->board == $key ? 'selected' : ''}} {{ old('academicQualification.jsc.board') == $key ? 'selected' : '' }}>
                                                                        {{ $value }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <div class="col-md-4"></div>
                                                    </div>

                                                    <div class="form-row form-group mt-2">
                                                        <label for="ssc_roll"
                                                               class="col-md-4 col-form-label">{{ __('generic.roll_no')}}</label>
                                                        <div class="col-md-8">
                                                            <input type="text"
                                                                   name="academicQualification[ssc][roll_no]"
                                                                   id="ssc_roll" class="form-control"
                                                                   value="{{ isset($academicQualifications[\App\Models\TraineeAcademicQualification::EXAMINATION_SSC]) ? $academicQualifications[\App\Models\TraineeAcademicQualification::EXAMINATION_SSC]->roll_no :  old('academicQualification.ssc.roll_no') }}">
                                                        </div>
                                                        <div class="col-md-4"></div>
                                                    </div>

                                                    <div class="form-row form-group mt-2">
                                                        <label for="ssc_reg_no"
                                                               class="col-md-4 col-form-label">{{ __('generic.reg_no')}}</label>
                                                        <div class="col-md-8">
                                                            <input type="text" id="ssc_reg_no"
                                                                   name="academicQualification[ssc][reg_no]"
                                                                   class="form-control"
                                                                   value="{{ isset($academicQualifications[\App\Models\TraineeAcademicQualification::EXAMINATION_SSC]) ? $academicQualifications[\App\Models\TraineeAcademicQualification::EXAMINATION_SSC]->reg_no :  old('academicQualification.ssc.reg_no') }}">
                                                        </div>
                                                        <div class="col-md-4"></div>
                                                    </div>

                                                    <div class="form-row form-group mt-2">
                                                        <label for="ssc_result"
                                                               class="col-md-4 col-form-label">{{ __('generic.result')}}</label>
                                                        <div class="col-md-8" id="ssc_result_div">
                                                            <select name="academicQualification[ssc][result]"
                                                                    id="ssc_result"
                                                                    class="select2">
                                                                <option value=""></option>
                                                                @foreach(\App\Models\TraineeAcademicQualification::getExaminationResultOptions() as $key => $value)
                                                                    @if($key == \App\Models\TraineeAcademicQualification::EXAMINATION_RESULT_PASSED_MBBS_BDS)
                                                                        @continue;
                                                                    @endif
                                                                    <option
                                                                        value="{{ $key }}" {{ isset($academicQualifications[\App\Models\TraineeAcademicQualification::EXAMINATION_SSC]) && $academicQualifications[\App\Models\TraineeAcademicQualification::EXAMINATION_SSC]->result == $key ? 'selected' : ''}} {{ old('academicQualification.ssc.result') == $key ? 'selected' : '' }}>
                                                                        {{ $value }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <div class="" id="ssc_gpa_div">
                                                            <input type="number"
                                                                   name="academicQualification[ssc][grade]"
                                                                   id="ssc_gpa" class="form-control"
                                                                   width="10" placeholder="{{ __('generic.gpa')}}"
                                                                   value="{{ isset($academicQualifications[\App\Models\TraineeAcademicQualification::EXAMINATION_SSC]) ? $academicQualifications[\App\Models\TraineeAcademicQualification::EXAMINATION_SSC]->grade :  old('academicQualification.ssc.grade') }}"
                                                                   hidden>
                                                        </div>
                                                        <div class="col-md-4"></div>
                                                    </div>

                                                    <div class="form-row form-group mt-2">
                                                        <label for="ssc_group"
                                                               class="col-md-4 col-form-label">{{ __('generic.division')}}</label>
                                                        <div class="col-md-8">
                                                            <select name="academicQualification[ssc][group]"
                                                                    class="select2"
                                                                    id="ssc_group">
                                                                <option value=""></option>
                                                                @foreach(\App\Models\TraineeAcademicQualification::getExaminationGroupOptions() as $key => $value)
                                                                    <option
                                                                        value="{{ $key }}" {{ isset($academicQualifications[\App\Models\TraineeAcademicQualification::EXAMINATION_SSC]) && $academicQualifications[\App\Models\TraineeAcademicQualification::EXAMINATION_SSC]->group == $key ? 'selected' : ''}} {{ old('academicQualification.ssc.group') == $key ? 'selected' : '' }}>
                                                                        {{ $value }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <div class="col-md-4"></div>
                                                    </div>

                                                    <div class="form-row form-group mt-2">
                                                        <label for="ssc_passing_year"
                                                               class="col-md-4 col-form-label">{{ __('generic.passing_year')}}</label>
                                                        <div class="col-md-8">
                                                            <select name="academicQualification[ssc][passing_year]"
                                                                    id="ssc_passing_year" class="select2">
                                                                <option value=""></option>
                                                                @for($i = now()->format('Y') - 50; $i <= now()->format('Y'); $i++)
                                                                    <option
                                                                        value="{{ $i }}" {{ isset($academicQualifications[\App\Models\TraineeAcademicQualification::EXAMINATION_SSC]) && $academicQualifications[\App\Models\TraineeAcademicQualification::EXAMINATION_SSC]->passing_year == $i ? 'selected' : ''}} {{ old('academicQualification.ssc.passing_year') == $i ? 'selected' : '' }}>
                                                                        {{ $i }}
                                                                    </option>
                                                                @endfor
                                                            </select>
                                                        </div>
                                                        <div class="col-md-4"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-6 academic-qualification-hsc mb-2">
                                            <div class="card custom-bg-gradient-info col-md-12" style="height: 100%;">
                                                <div class="card-header">
                                                    <h3 class="card-title text-primary d-inline-flex">
                                                        {{ __('admin.examination_name.hsc')}}
                                                        /{{ __('generic.equivalent')}}
                                                        ({{ __('generic.pass')}})
                                                    </h3>
                                                </div>
                                                <div class="card-body hsc_collapse hide">
                                                    <input type="hidden" name="academicQualification[hsc][examination]"
                                                           value="{{ \App\Models\TraineeAcademicQualification::EXAMINATION_HSC }}">
                                                    <div class="form-row form-group">
                                                        <label for="hsc_examination_name"
                                                               class="col-md-4 col-form-label">{{ __('generic.examination')}}
                                                        </label>

                                                        <div class="col-md-8">
                                                            <select name="academicQualification[hsc][examination_name]"
                                                                    id="hsc_examination_name" class="select2">
                                                                <option></option>
                                                                @foreach(\App\Models\TraineeAcademicQualification::getHSCExaminationOptions() as $key => $value)
                                                                    <option
                                                                        value="{{ $key }}" {{ isset($academicQualifications[\App\Models\TraineeAcademicQualification::EXAMINATION_HSC]) && $academicQualifications[\App\Models\TraineeAcademicQualification::EXAMINATION_HSC]->examination_name == $key ? 'selected' : ''}} {{ old('academicQualification.hsc.examination_name') == $key ? 'selected' : '' }}>
                                                                        {{ $value }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <div class="col-md-4"></div>
                                                    </div>

                                                    <div class="form-row form-group mt-2">
                                                        <label for="hsc_board"
                                                               class="col-md-4 col-form-label">{{ __('generic.board')}}
                                                        </label>
                                                        <div class="col-md-8">
                                                            <select name="academicQualification[hsc][board]"
                                                                    id="hsc_board"
                                                                    class="select2">
                                                                <option></option>
                                                                @foreach(\App\Models\TraineeAcademicQualification::getExaminationBoardOptions() as $key => $value)
                                                                    <option
                                                                        value="{{ $key }}" {{ isset($academicQualifications[\App\Models\TraineeAcademicQualification::EXAMINATION_HSC]) && $academicQualifications[\App\Models\TraineeAcademicQualification::EXAMINATION_HSC]->board == $key ? 'selected' : ''}} {{ old('academicQualification.hsc.board') == $key ? 'selected' : '' }}>
                                                                        {{ $value }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <div class="col-md-4"></div>
                                                    </div>

                                                    <div class="form-row form-group mt-2">
                                                        <label for="hsc_roll"
                                                               class="col-md-4 col-form-label">{{ __('generic.roll_no')}}</label>
                                                        <div class="col-md-8">
                                                            <input type="text"
                                                                   name="academicQualification[hsc][roll_no]"
                                                                   id="hsc_roll" class="form-control"
                                                                   value="{{ isset($academicQualifications[\App\Models\TraineeAcademicQualification::EXAMINATION_HSC]) ? $academicQualifications[\App\Models\TraineeAcademicQualification::EXAMINATION_HSC]->roll_no :  old('academicQualification.hsc.roll_no') }}">
                                                        </div>
                                                        <div class="col-md-4"></div>
                                                    </div>

                                                    <div class="form-row form-group mt-2">
                                                        <label for="hsc_reg_no"
                                                               class="col-md-4 col-form-label">{{ __('generic.reg_no')}}</label>
                                                        <div class="col-md-8">
                                                            <input type="text" name="academicQualification[hsc][reg_no]"
                                                                   id="hsc_reg_no" class="form-control"
                                                                   value="{{ isset($academicQualifications[\App\Models\TraineeAcademicQualification::EXAMINATION_HSC]) ? $academicQualifications[\App\Models\TraineeAcademicQualification::EXAMINATION_HSC]->reg_no :  old('academicQualification.hsc.reg_no') }}"

                                                            >
                                                        </div>
                                                        <div class="col-md-4"></div>
                                                    </div>

                                                    <div class="form-row form-group mt-2">
                                                        <label for="hsc_result"
                                                               class="col-md-4 col-form-label">{{ __('generic.result')}}
                                                        </label>
                                                        <div class="col-md-8" id="hsc_result_div">
                                                            <select name="academicQualification[hsc][result]"
                                                                    id="hsc_result"
                                                                    class="select2">
                                                                <option></option>
                                                                @foreach(\App\Models\TraineeAcademicQualification::getExaminationResultOptions() as $key => $value)
                                                                    @if($key == \App\Models\TraineeAcademicQualification::EXAMINATION_RESULT_PASSED_MBBS_BDS)
                                                                        @continue;
                                                                    @endif
                                                                    <option
                                                                        value="{{ $key }}" {{ isset($academicQualifications[\App\Models\TraineeAcademicQualification::EXAMINATION_HSC]) && $academicQualifications[\App\Models\TraineeAcademicQualification::EXAMINATION_HSC]->result == $key ? 'selected' : ''}} {{ old('academicQualification.hsc.result') == $key ? 'selected' : '' }}>
                                                                        {{ $value }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <div class="" id="hsc_gpa_div">
                                                            <input type="number"
                                                                   name="academicQualification[hsc][grade]"
                                                                   id="hsc_gpa" class="form-control"
                                                                   width="10" placeholder="{{ __('generic.gpa')}}"
                                                                   value="{{ isset($academicQualifications[\App\Models\TraineeAcademicQualification::EXAMINATION_HSC]) ? $academicQualifications[\App\Models\TraineeAcademicQualification::EXAMINATION_HSC]->grade :  old('academicQualification.hsc.grade') }}"
                                                                   hidden>
                                                        </div>
                                                        <div class="col-md-4"></div>
                                                    </div>

                                                    <div class="form-row form-group mt-2">
                                                        <label for="hsc_group"
                                                               class="col-md-4 col-form-label">{{ __('generic.division')}}
                                                        </label>
                                                        <div class="col-md-8">
                                                            <select name="academicQualification[hsc][group]"
                                                                    id="hsc_group"
                                                                    class="select2">
                                                                <option></option>
                                                                @foreach(\App\Models\TraineeAcademicQualification::getExaminationGroupOptions() as $key => $value)
                                                                    <option
                                                                        value="{{ $key }}" {{ isset($academicQualifications[\App\Models\TraineeAcademicQualification::EXAMINATION_HSC]) && $academicQualifications[\App\Models\TraineeAcademicQualification::EXAMINATION_HSC]->group == $key ? 'selected' : ''}} {{ old('academicQualification.hsc.group') == $key ? 'selected' : '' }}>
                                                                        {{ $value }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <div class="col-md-4"></div>
                                                    </div>

                                                    <div class="form-row form-group mt-2">
                                                        <label for="hsc_passing_year"
                                                               class="col-md-4 col-form-label">{{ __('generic.passing_year')}}</label>
                                                        <div class="col-md-8">
                                                            <select name="academicQualification[hsc][passing_year]"
                                                                    id="hsc_passing_year" class="select2">
                                                                <option value=""></option>
                                                                @for($i = now()->format('Y') - 50; $i <= now()->format('Y'); $i++)
                                                                    <option
                                                                        value="{{ $i }}" {{ isset($academicQualifications[\App\Models\TraineeAcademicQualification::EXAMINATION_HSC]) && $academicQualifications[\App\Models\TraineeAcademicQualification::EXAMINATION_HSC]->passing_year == $i ? 'selected' : ''}} {{ old('academicQualification.hsc.passing_year') == $i ? 'selected' : '' }}>
                                                                        {{ $i }}
                                                                    </option>
                                                                @endfor
                                                            </select>
                                                        </div>
                                                        <div class="col-md-4"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-6 academic-qualification-graduation mb-2">
                                            <div class="card col-md-12 custom-bg-gradient-info" style="height: 100%;">
                                                <div class="card-header">
                                                    <h3 class="card-title text-primary d-inline-flex">{{ __('admin.examination_name.honors')}}
                                                        ({{ __('generic.pass')}})</h3>
                                                </div>
                                                <div class="card-body graduation_collapse hide">
                                                    <input type="hidden"
                                                           name="academicQualification[graduation][examination]"
                                                           value="{{ \App\Models\TraineeAcademicQualification::EXAMINATION_GRADUATION }}">

                                                    <div class="form-row form-group">
                                                        <label for="graduation_examination_name"
                                                               class="col-md-4 col-form-label">{{ __('generic.examination')}}
                                                        </label>
                                                        <div class="col-md-8">
                                                            <select
                                                                name="academicQualification[graduation][examination_name]"
                                                                id="graduation_examination_name" class="select2">
                                                                <option value=""></option>
                                                                @foreach(\App\Models\TraineeAcademicQualification::getGraduationExaminationOptions() as $key => $value)
                                                                    <option
                                                                        value="{{ $key }}" {{ isset($academicQualifications[\App\Models\TraineeAcademicQualification::EXAMINATION_GRADUATION]) && $academicQualifications[\App\Models\TraineeAcademicQualification::EXAMINATION_GRADUATION]->examination_name == $key ? 'selected' : ''}} {{ old('academicQualification.graduation.examination_name') == $key ? 'selected' : '' }}>
                                                                        {{ $value }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <div class="col-md-4"></div>
                                                    </div>

                                                    <div class="form-row form-group mt-2">
                                                        <label for="graduation_subject"
                                                               class="col-md-4 col-form-label">{{ __('generic.subject')}}
                                                            /{{ __('generic.degree')}}</label>
                                                        <div class="col-md-8">
                                                            <input type="text"
                                                                   name="academicQualification[graduation][subject]"
                                                                   id="graduation_subject" class="form-control"
                                                                   value="{{ isset($academicQualifications[\App\Models\TraineeAcademicQualification::EXAMINATION_GRADUATION]) ? $academicQualifications[\App\Models\TraineeAcademicQualification::EXAMINATION_GRADUATION]->subject :  old('academicQualification.graduation.subject') }}">
                                                        </div>
                                                        <div class="col-md-4"></div>
                                                    </div>

                                                    <div class="form-row form-group mt-2">
                                                        <label for="graduation_institute"
                                                               class="col-md-4 col-form-label">{{ __('generic.institute')}}
                                                            /{{ __('generic.university')}}</label>
                                                        <div class="col-md-8">
                                                            <select name="academicQualification[graduation][institute]"
                                                                    id="graduation_institute"
                                                                    class="select2">
                                                                <option value=""></option>
                                                                @foreach(\App\Models\TraineeAcademicQualification::getUniversities() as $key => $value)
                                                                    <option
                                                                        value="{{ $key }}" {{ isset($academicQualifications[\App\Models\TraineeAcademicQualification::EXAMINATION_GRADUATION]) && $academicQualifications[\App\Models\TraineeAcademicQualification::EXAMINATION_GRADUATION]->institute == $key ? 'selected' : ''}} {{ old('academicQualification.graduation.institute') == $key ? 'selected' : '' }}>
                                                                        {{ $value }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <div class="col-md-4"></div>
                                                    </div>


                                                    <div class="form-row form-group mt-2">
                                                        <label for="graduation_result"
                                                               class="col-md-4 col-form-label">{{ __('generic.result')}}
                                                        </label>
                                                        <div class="col-md-8" id="graduation_result_div">
                                                            <select name="academicQualification[graduation][result]"
                                                                    id="graduation_result"
                                                                    class="select2">
                                                                <option value=""></option>
                                                                @foreach(\App\Models\TraineeAcademicQualification::getExaminationResultOptions() as $key => $value)
                                                                    <option
                                                                        value="{{ $key }}" {{ isset($academicQualifications[\App\Models\TraineeAcademicQualification::EXAMINATION_GRADUATION]) && $academicQualifications[\App\Models\TraineeAcademicQualification::EXAMINATION_GRADUATION]->result == $key ? 'selected' : ''}} {{ old('academicQualification.graduation.result') == $key ? 'selected' : '' }}>
                                                                        {{ $value }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <div class="" id="graduation_cgpa_div">
                                                            <input type="number"
                                                                   name="academicQualification[graduation][grade]"
                                                                   id="graduation_cgpa"
                                                                   class="form-control" width="10"
                                                                   placeholder="{{ __('generic.cgpa')}}"
                                                                   value="{{ isset($academicQualifications[\App\Models\TraineeAcademicQualification::EXAMINATION_GRADUATION]) ? $academicQualifications[\App\Models\TraineeAcademicQualification::EXAMINATION_GRADUATION]->grade :  old('academicQualification.graduation.grade') }}"
                                                                   hidden>
                                                        </div>
                                                        <div class="col-md-4"></div>
                                                    </div>

                                                    <div class="form-row form-group mt-2">
                                                        <label for="graduation_passing_year"
                                                               class="col-md-4 col-form-label">
                                                            {{ __('generic.passing_year')}}</label>
                                                        <div class="col-md-8">
                                                            <select
                                                                name="academicQualification[graduation][passing_year]"
                                                                id="graduation_passing_year" class="select2">
                                                                <option></option>
                                                                @for($i = now()->format('Y') - 50; $i <= now()->format('Y'); $i++)
                                                                    <option
                                                                        value="{{ $i }}" {{ isset($academicQualifications[\App\Models\TraineeAcademicQualification::EXAMINATION_GRADUATION]) && $academicQualifications[\App\Models\TraineeAcademicQualification::EXAMINATION_GRADUATION]->passing_year == $i ? 'selected' : ''}} {{ old('academicQualification.graduation.passing_year') == $i ? 'selected' : '' }}>
                                                                        {{ $i }}
                                                                    </option>
                                                                @endfor
                                                            </select>
                                                        </div>
                                                        <div class="col-md-4"></div>
                                                    </div>

                                                    <div class="form-row form-group mt-2">
                                                        <label for="graduation_course_duration"
                                                               class="col-md-4 col-form-label">
                                                            {{ __('generic.course_duration')}}</label>
                                                        <div class="col-md-8">
                                                            <select
                                                                name="academicQualification[graduation][course_duration]"
                                                                id="graduation_course_duration" class="select2">
                                                                <option></option>
                                                                @foreach(\App\Models\TraineeAcademicQualification::getExaminationCourseDurationOptions() as $key => $value)
                                                                    <option
                                                                        value="{{ $key }}" {{ isset($academicQualifications[\App\Models\TraineeAcademicQualification::EXAMINATION_GRADUATION]) && $academicQualifications[\App\Models\TraineeAcademicQualification::EXAMINATION_GRADUATION]->course_duration == $key ? 'selected' : ''}} {{ old('academicQualification.graduation.course_duration') == $key ? 'selected' : '' }}>
                                                                        {{ $value }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <div class="col-md-4"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6 academic-qualification-masters mb-2">
                                            <div class="card col-md-12 custom-bg-gradient-info" style="height: 100%;">
                                                <div class="card-header">
                                                    <h3 class="card-title text-primary d-inline-flex">{{ __('admin.examination_name.masters')}}
                                                        ({{ __('generic.pass')}}) </h3>
                                                </div>
                                                <div class="card-body masters_collapse {{--collapse--}} hide">
                                                    <input type="hidden"
                                                           name="academicQualification[masters][examination]"
                                                           value="{{ \App\Models\TraineeAcademicQualification::EXAMINATION_MASTERS }}">
                                                    <div class="form-row form-group">
                                                        <label for="masters_examination_name"
                                                               class="col-md-4 col-form-label">{{ __('generic.examination')}}</label>
                                                        <div class="col-md-8">
                                                            <select
                                                                name="academicQualification[masters][examination_name]"
                                                                id="masters_examination_name" class="select2">
                                                                <option></option>
                                                                @foreach(\App\Models\TraineeAcademicQualification::getMastersExaminationOptions() as $key => $value)
                                                                    <option
                                                                        value="{{ $key }}" {{ isset($academicQualifications[\App\Models\TraineeAcademicQualification::EXAMINATION_MASTERS]) && $academicQualifications[\App\Models\TraineeAcademicQualification::EXAMINATION_MASTERS]->examination_name == $key ? 'selected' : ''}} {{ old('academicQualification.masters.examination_name') == $key ? 'selected' : '' }}>
                                                                        {{ $value }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <div class="col-md-4"></div>
                                                    </div>

                                                    <div class="form-row form-group mt-2">
                                                        <label for="masters_subject"
                                                               class="col-md-4 col-form-label">{{ __('generic.subject')}}
                                                            /{{ __('generic.degree')}}</label>
                                                        <div class="col-md-8">
                                                            <input type="text"
                                                                   name="academicQualification[masters][subject]"
                                                                   id="masters_subject"
                                                                   class="form-control"
                                                                   value="{{ isset($academicQualifications[\App\Models\TraineeAcademicQualification::EXAMINATION_MASTERS]) ? $academicQualifications[\App\Models\TraineeAcademicQualification::EXAMINATION_MASTERS]->subject :  old('academicQualification.graduation.subject') }}"
                                                            >
                                                        </div>
                                                        <div class="col-md-4"></div>
                                                    </div>

                                                    <div class="form-row form-group mt-2">
                                                        <label for="masters_institute"
                                                               class="col-md-4 col-form-label">{{ __('generic.institute')}}
                                                            /{{ __('generic.university')}}</label>
                                                        <div class="col-md-8">
                                                            <select name="academicQualification[masters][institute]"
                                                                    id="masters_institute" class="select2">
                                                                <option value=""></option>
                                                                @foreach(\App\Models\TraineeAcademicQualification::getUniversities() as $key => $value)
                                                                    <option
                                                                        value="{{ $key }}" {{ isset($academicQualifications[\App\Models\TraineeAcademicQualification::EXAMINATION_MASTERS]) && $academicQualifications[\App\Models\TraineeAcademicQualification::EXAMINATION_MASTERS]->institute == $key ? 'selected' : ''}} {{ old('academicQualification.masters.institute') == $key ? 'selected' : '' }}>
                                                                        {{ $value }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <div class="col-md-4"></div>
                                                    </div>


                                                    <div class="form-row form-group mt-2">
                                                        <label for="masters_result"
                                                               class="col-md-4 col-form-label">{{ __('generic.result')}}</label>
                                                        <div class="col-md-8" id="masters_result_div">
                                                            <select name="academicQualification[masters][result]"
                                                                    id="masters_result"
                                                                    class="select2">
                                                                <option></option>
                                                                @foreach(\App\Models\TraineeAcademicQualification::getExaminationResultOptions() as $key => $value)
                                                                    <option
                                                                        value="{{ $key }}" {{ isset($academicQualifications[\App\Models\TraineeAcademicQualification::EXAMINATION_MASTERS]) && $academicQualifications[\App\Models\TraineeAcademicQualification::EXAMINATION_MASTERS]->result == $key ? 'selected' : ''}} {{ old('academicQualification.masters.result') == $key ? 'selected' : '' }}>
                                                                        {{ $value }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <div class="" id="masters_cgpa_div">
                                                            <input type="number"
                                                                   name="academicQualification[masters][grade]"
                                                                   id="masters_cgpa"
                                                                   class="form-control" width="10"
                                                                   placeholder="{{__('generic.cgpa')}}"
                                                                   value="{{ old('academicQualification.masters.grade') }}"
                                                                   hidden>
                                                        </div>
                                                        <div class="col-md-4"></div>
                                                    </div>

                                                    <div class="form-row form-group mt-2">
                                                        <label for="masters_passing_year"
                                                               class="col-md-4 col-form-label">
                                                            {{ __('generic.passing_year')}}</label>
                                                        <div class="col-md-8">
                                                            <select name="academicQualification[masters][passing_year]"
                                                                    class="select2" id="masters_passing_year">
                                                                <option></option>
                                                                @for($i = now()->format('Y') - 50; $i <= now()->format('Y'); $i++)
                                                                    <option
                                                                        value="{{ $i }}" {{ isset($academicQualifications[\App\Models\TraineeAcademicQualification::EXAMINATION_MASTERS]) && $academicQualifications[\App\Models\TraineeAcademicQualification::EXAMINATION_MASTERS]->passing_year == $i ? 'selected' : ''}} {{ old('academicQualification.masters.passing_year') == $i ? 'selected' : '' }}>
                                                                        {{ $i }}
                                                                    </option>
                                                                @endfor
                                                            </select>
                                                        </div>
                                                        <div class="col-md-4"></div>

                                                    </div>

                                                    <div class="form-row form-group mt-2">
                                                        <label for="masters_course_duration"
                                                               class="col-md-4 col-form-label">
                                                            {{ __('generic.course_duration')}}</label>
                                                        <div class="col-md-8">
                                                            <select
                                                                name="academicQualification[masters][course_duration]"
                                                                id="masters_course_duration" class="select2">
                                                                <option></option>
                                                                @foreach(\App\Models\TraineeAcademicQualification::getExaminationCourseDurationOptions() as $key => $value)
                                                                    <option
                                                                        value="{{ $key }}" {{ isset($academicQualifications[\App\Models\TraineeAcademicQualification::EXAMINATION_MASTERS]) && $academicQualifications[\App\Models\TraineeAcademicQualification::EXAMINATION_MASTERS]->course_duration == $key ? 'selected' : ''}} {{ old('academicQualification.masters.course_duration') == $key ? 'selected' : '' }}>
                                                                        {{ $value }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <div class="col-md-4"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="card">
                                    <div class="card-header custom-bg-gradient-info experiences">
                                        <h3 class="card-title font-weight-bold text-primary"><i
                                                class="fa fa-address-book"> </i> {{ __('generic.experience')}} </h3>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-12 trainer-experience-contents"></div>
                                            <div class="col-md-12">
                                                <button type="button"
                                                        class="btn btn-primary add-more-experience-btn float-right">
                                                    <i class="fa fa-plus-circle fa-fw"></i></button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>


                            <div class="col-sm-12 text-right">
                                <button type="submit"
                                        class="btn btn-success">{{ $edit ? __('generic.update') : __('generic.add') }}</button>
                            </div>
                        </form>
                    </div><!-- /.card-body -->
                    <div class="overlay" style="display: none">
                        <i class="fas fa-2x fa-sync-alt fa-spin"></i>
                    </div>
                </div>
            </div>
        </div>

    </div>

@endsection

@push('js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/lodash.js/4.17.21/lodash.js"></script>

    <x-generic-validation-error-toastr></x-generic-validation-error-toastr>

    <script>

        const EDIT = !!'{{$edit}}';
        let SL = 0;

        $(document).ready(function () {
            $("#profile_pic").change(async function () {
                await readURL(this); //preview image
                traineeRegistrationForm.validate().element("#profile_pic");
            });

            $("#signature_pic").change(async function () {
                await readURL(this);
                traineeRegistrationForm.validate().element("#signature_pic");
            });
        })

        function readURL(input) {
            return new Promise(function (resolve, reject) {
                let reader = new FileReader();
                reader.onload = (e) => {
                    $("." + input.id + ' img').attr('src', e.target.result);
                    resolve(e.target.result);
                };
                reader.onerror = reject;
                reader.readAsDataURL(input.files[0]); // convert to base64 string
            });
        }

        function hideJobEndDateField(sl) {
            const jobEndDate = "#job_end_date" + sl;
            $(jobEndDate).parent().hide();
        }

        function addRow(data = {}) {
            const EDIT = !!data.id;
            let trainerExperience = _.template($('#trainer-experiences').html());
            let experienceContentElm = $(".trainer-experience-contents");
            experienceContentElm.append(trainerExperience({sl: SL, data: data, edit: EDIT}))
            experienceContentElm.find('.flat-date').each(function () {
                $(this).flatpickr({
                    altInput: false,
                    altFormat: "j F, Y",
                    dateFormat: "Y-m-d",
                });
            });

            $.validator.addMethod("cGreaterThan", $.validator.methods.greaterThan,
                "Application start date must be greater than today");
            $.validator.addMethod("cApplicationEndDate", $.validator.methods.greaterThan,
                "Application end date must be greater than Application start date");
            $.validator.addMethod("cCourseStartDate", $.validator.methods.greaterThan,
                "Course start date must be greater than Application end date");
            $.validator.addClassRules("number_of_batches", {required: true});
            $.validator.addClassRules("application_start_date", {
                required: true,
                //cGreaterThan: '#today',
            });

            for (let i = 0; i <= SL; i++) {

                $.validator.addClassRules("job_start_date" + i, {
                    required: true,
                    // cApplicationEndDate: '.application_start_date' + i,
                });
            }


            $.validator.addClassRules("organization_name", {required: true});
            $.validator.addClassRules("position", {required: true});

            if (data.current_working_status == 1) {
                hideJobEndDateField(SL);
            }

            SL++;
        }

        function deleteRow(slNo) {
            let sessionELm = $("#session-no-" + slNo);
            if (sessionELm.find('.delete_status').length) {
                sessionELm.find('.delete_status').val(1);
                sessionELm.hide();
            } else {
                sessionELm.remove();
            }
            SL--;
        }

        $(document).ready(function () {
            @if($trainer->trainerExperiences->count())
            @foreach($trainer->trainerExperiences as $experience)
            addRow(@json($experience));
            @endforeach
            @else
            addRow();
            @endif

            $(document).on('click', '.add-more-experience-btn', function () {
                addRow();
            });
        });

        const editAddForm = $('.edit-add-form');
        editAddForm.validate({
            rules: {
                name: {
                    required: true
                },
                email: {
                    required: true
                },
                mobile: {
                    required: true
                },
            },


            submitHandler: function (htmlForm) {
                $('.overlay').show();

                // Get some values from elements on the page:
                const form = $(htmlForm);
                const formData = new FormData(htmlForm);
                const url = form.attr("action");

                // Send the data using post
                $.ajax({
                    url: url,
                    data: formData,
                    type: 'POST',
                    processData: false,
                    contentType: false,
                    success: function (response) {
                        $('.overlay').hide();
                        let alertType = response.alertType;
                        let alertMessage = response.message;
                        let alerter = toastr[alertType];
                        alerter ? alerter(alertMessage) : toastr.error("toastr alert-type " + alertType + " is unknown");

                        if (response.accessKey) {
                            window.location.href = response.redirectTo;
                        }
                    },
                });

                return false;
            }

        });

        $("#ssc_result").on('change', function () {
            if ($(this).val() == {!! \App\Models\TraineeAcademicQualification::EXAMINATION_RESULT_GPA_OUT_OF_FOUR !!}
                || $(this).val() == {!! \App\Models\TraineeAcademicQualification::EXAMINATION_RESULT_GPA_OUT_OF_FIVE !!}) {
                $('#ssc_gpa').removeAttr('hidden');
                $('#ssc_result_div').removeAttr('class');
                $('#ssc_result_div').addClass('col-md-6');
                $('#ssc_gpa_div').addClass('col-md-2');

            } else {
                $('#ssc_gpa').attr('hidden', true);
                $('#ssc_result_div').removeAttr('class');
                $('#ssc_result_div').addClass('col-md-8');
                $('#ssc_gpa_div').removeAttr('class');
            }
        });

        $("#hsc_result").on('change', function () {
            if ($(this).val() == {!! \App\Models\TraineeAcademicQualification::EXAMINATION_RESULT_GPA_OUT_OF_FOUR !!}
                || $(this).val() == {!! \App\Models\TraineeAcademicQualification::EXAMINATION_RESULT_GPA_OUT_OF_FIVE !!}) {
                $('#hsc_gpa').removeAttr('hidden');
                $('#hsc_result_div').removeAttr('class');
                $('#hsc_result_div').addClass('col-md-6');
                $('#hsc_gpa_div').addClass('col-md-2');
            } else {
                $('#hsc_gpa').attr('hidden', true);
                $('#hsc_result_div').removeAttr('class');
                $('#hsc_result_div').addClass('col-md-8');
                $('#hsc_gpa_div').removeAttr('class');
            }
        });

        $("#graduation_result").on('change', function () {
            if ($(this).val() == {!! \App\Models\TraineeAcademicQualification::EXAMINATION_RESULT_GPA_OUT_OF_FOUR !!}
                || $(this).val() == {!! \App\Models\TraineeAcademicQualification::EXAMINATION_RESULT_GPA_OUT_OF_FIVE !!}) {
                $('#graduation_cgpa').removeAttr('hidden');

                $('#graduation_result_div').removeAttr('class');
                $('#graduation_result_div').addClass('col-md-6');
                $('#graduation_cgpa_div').addClass('col-md-2');
            } else {
                $('#graduation_cgpa').attr('hidden', true);

                $('#graduation_result_div').removeAttr('class');
                $('#graduation_result_div').addClass('col-md-8');
                $('#graduation_cgpa_div').removeAttr('class');
            }
        });

        $("#masters_result").on('change', function () {
            if ($(this).val() == {!! \App\Models\TraineeAcademicQualification::EXAMINATION_RESULT_GPA_OUT_OF_FOUR !!}
                || $(this).val() == {!! \App\Models\TraineeAcademicQualification::EXAMINATION_RESULT_GPA_OUT_OF_FIVE !!}) {
                $('#masters_cgpa').removeAttr('hidden');

                $('#masters_result_div').removeAttr('class');
                $('#masters_result_div').addClass('col-md-6');
                $('#masters_cgpa_div').addClass('col-md-2');
            } else {
                $('#masters_cgpa').attr('hidden', true);

                $('#masters_result_div').removeAttr('class');
                $('#masters_result_div').addClass('col-md-8');
                $('#masters_cgpa_div').removeAttr('class');
            }
        });


        const handleCurrentWorkingStatus = function (currentlyWorkingInputEle, sl) {
            const jobEndDate = "#job_end_date" + sl;
            console.log('this', $(jobEndDate));

            if (currentlyWorkingInputEle.checked) {
                $(jobEndDate).parent().hide();
            } else {
                $(jobEndDate).parent().show();
            }
        }

    </script>

    <script type="text/template" id="trainer-experiences">
        <div class="card" id="session-no-<%=sl%>">
            <div class="card-header d-flex justify-content-between">
                <h5 class="session-name-english<%=sl%>"><%= "{{ __('generic.experience')}} " + (sl+1)%></h5>
                <div class="card-tools">
                    <button type="button"
                            onclick="deleteRow(<%=sl%>)"
                            class="btn btn-warning less-experience-btn float-right mr-2"><i
                            class="fa fa-minus-circle fa-fw"></i></button>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <% if(data.id) { %>
                    <input type="hidden" id="trainer_experience_<%=data.id%>" name="trainer_experiences[<%=sl%>][id]"
                           value="<%=data.id%>">
                    <input type="hidden" name="trainer_experiences[<%=sl%>][delete]" class="delete_status" value="0"/>
                    <% } %>

                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="organization_name">{{ __('generic.organization')}} <span
                                    style="color: red"> * </span></label>
                            <input type="text"
                                   class="form-control organization_name"
                                   name="trainer_experiences[<%=sl%>][organization_name]"
                                   placeholder="{{ __('Organization name') }}"
                                   value="<%=edit ? data.organization_name : ''%>"
                            >
                        </div>
                    </div>

                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="job_position">{{ __('generic.position')}} <span
                                    style="color: red"> * </span></label>
                            <input type="text"
                                   class="form-control position"
                                   name="trainer_experiences[<%=sl%>][position]"
                                   placeholder="{{ __('Position') }}"
                                   value="<%=edit ? data.position : ''%>"
                            >
                        </div>
                    </div>

                    <div class="col-sm-12">
                        <div class="form-check">
                            <input class="form-check-input trainer_experience_current_work_status<%=sl%>"
                                   type="checkbox"
                                   name="trainer_experiences[<%=sl%>][current_working_status]"
                                   id="currently_working_status<%=sl%>"
                                   value="1"
                                   onclick="handleCurrentWorkingStatus(this, <%=sl%>)"
                            <%=data.current_working_status ? "checked" : ''%>
                            >
                            <label class="form-check-label" for="currently_working_status<%=sl%>">
                                {{ __('generic.currently_working')}}?
                            </label>
                        </div>
                    </div>


                    <div class="col-sm-6">
                        <div class="form-group">
                            <label
                                for="job_start_date">{{ __('generic.job_start_date')}} <span
                                    style="color: red"> * </span></label>
                            <input type="text"
                                   class="flat-date flat-date-custom-bg form-control job_start_date job_start_date<%=sl%> "
                                   name="trainer_experiences[<%=sl%>][job_start_date]"
                                   id="trainer_experiences[<%=sl%>][job_start_date]"
                                   value="<%=edit ? data.job_start_date : ''%>">
                        </div>
                    </div>

                    <div class="col-sm-6">
                        <div class="form-group">
                            <label
                                for="job_end_date">{{ __('generic.job_end_date')}} <span
                                    style="color: red"> * </span></label>
                            <input type="text"
                                   class="flat-date flat-date-custom-bg form-control job_end_date job_end_date<%=sl%>"
                                   name="trainer_experiences[<%=sl%>][job_end_date]"
                                   id="job_end_date<%=sl%>"
                                   value="<%=edit ? data.job_end_date : ''%>"
                            >
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </script>
@endpush


