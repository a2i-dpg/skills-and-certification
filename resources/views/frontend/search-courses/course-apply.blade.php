@php
    $currentInstitute = app('currentInstitute');
    $layout = 'master::layouts.front-end';

@endphp

@extends($layout)

@section('title')
{{$siteSettingInfo->site_title}} :: {{ __('course_enroll') }}
@endsection

@section('content')
    <div class="container-fluid" id="fixed-scrollbar">
        <div class="row  justify-content-center">
            <div class="col-md-11 col-sm-12">
                <div class="card mt-3">
                    <div class="card-header d-flex justify-content-between custom-bg-gradient-info"
                         style="background: url('{{asset('storage/'. optional($course)->cover_image)}}') no-repeat center center;
                             background-size: cover; min-height: 40vh;"
                    >
                    </div>
                    <div class="card-header text-primary custom-bg-gradient-info text-center">
                        <h3>{{$course->title}}</h3>
                    </div>
                    @if(!count($runningBatches ?? []))
                        <div class="card-body text-center">
                            <h1 class="display-5">{{__('frontend.course.opps')}}</h1>
                            <p class="lead">
                                {{__('frontend.course.no_batch')}}
                            </p>
                            <a class="btn btn-primary btn-sm" href="{{route('frontend.course_search')}}"
                               role="button">{{__('frontend.course.find_other_batch')}}</a>
                        </div>
                    @else
                        <form action="{{ route('frontend.course-enroll') }}" method="POST" class="courseEnrollmentForm">
                            @csrf
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-12 custom-view-box">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <p class="label-text">{{__('admin.course.batch_list')}}<span
                                                        class="text-italic">({{__('frontend.course.set_preference')}})</span>
                                                </p>

                                                <input type="hidden" name="course_id" value="{{ $course->id }}">

                                                <ul class="list-group">
                                                    @foreach($runningBatches as $batch)
                                                        <div class="card">
                                                            <label
                                                                for="batch-title-{{$batch->id}}">
                                                                <input class="batch-list"
                                                                       id="batch-title-{{$batch->id}}"
                                                                       data-id="{{ $batch->id }}"
                                                                       type="checkbox"
                                                                       value="{{ $batch->title }}"/>
                                                                <div class="card-body">
                                                                    <div>
                                                                        {{__('admin.course.batch')}}: <strong>{{ $batch->title }}</strong>
                                                                    </div>
                                                                    <div>
                                                                        {{__('admin.course.training_center')}}:
                                                                        <strong>{{ optional($batch->trainingCenter)->title }}</strong>
                                                                    </div>
                                                                    <div>
                                                                        {{__('admin.course.application_end_date')}}:
                                                                        <strong>{{ optional($batch->application_end_date)->format('d-m-Y') }}</strong>
                                                                    </div>
                                                                    <div>
                                                                        {{__('admin.course.batch_start_date')}}:
                                                                        <strong>{{ optional($batch->batch_start_date)->format('d-m-Y') }}</strong>
                                                                    </div>
                                                                </div>
                                                            </label>
                                                        </div>
                                                    @endforeach
                                                </ul>
                                            </div>

                                            <div class="col-md-6">
                                                <p class="label-text">{{'Selected '}}{{__('admin.course.batch_list')}}</p>
                                                <ul class="list-group selected-batch-list"></ul>
                                            </div>
                                        </div>

                                    </div>

                                    <div class="col-md-12">
                                        <p class="font-italic">({{__('admin.course.complete_application')}})</p>

                                        <div class="form-row">
                                            <div class="form-group col-md-6" id="ethnic-group-section">
                                                <label for="ethnic_group">{{ __('generic.ethnic_group') }}></label>
                                                <div
                                                    class="d-md-flex form-control"
                                                    style="display: inline-table;">
                                                    <div class="custom-control custom-radio mr-3">
                                                        <input class="custom-control-input" type="radio"
                                                               id="ethnic_group_yes"
                                                               name="ethnic_group"
                                                               value="{{ \App\Models\Trainee::ETHNIC_GROUP_YES }}"
                                                            {{$trainee->ethnic_group == \App\Models\Trainee::ETHNIC_GROUP_YES ? 'checked' : ''}}>
                                                        <label for="ethnic_group_yes"
                                                               class="custom-control-label">{{ __('generic.yes') }}</label>
                                                    </div>
                                                    <div class="custom-control custom-radio mr-3">
                                                        <input class="custom-control-input" type="radio"
                                                               id="ethnic_group_no"
                                                               name="ethnic_group"
                                                               value="{{ \App\Models\Trainee::ETHNIC_GROUP_NO }}"
                                                            {{ $trainee->ethnic_group == \App\Models\Trainee::ETHNIC_GROUP_NO ? 'checked' : ''}}>
                                                        <label for="ethnic_group_no"
                                                               class="custom-control-label">{{__('generic.no')}}</label>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-6 address-section">
                                                <div class="form-group">
                                                    <label for="address">{{__('admin.course.address')}}</label>
                                                    <input type="text" name="address" id="address" class="form-control">
                                                </div>
                                            </div>

                                        </div>


                                        <div class="row academic-info-section">
                                            <div class="col-md-12">
                                                <h5 class="font-weight-bold"> {{__('admin.course.academic_information')}}</h5>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="card-body row">
                                                    <div class="col-md-6 academic-qualification-jsc mb-2">
                                                        <div class="card col-md-12 custom-bg-gradient-info"
                                                             style="height: 100%;">
                                                            <div class="card-header">
                                                                <h3 class="card-title text-primary d-inline-flex">{{ __('admin.examination_name.jsc')}}
                                                                    /{{ __('generic.equivalent')}}
                                                                    ({{ __('generic.pass')}})</h3>
                                                            </div>
                                                            <div class="card-body jsc_collapse hide">

                                                                <input type="hidden"
                                                                       name="academicQualification[jsc][examination]"
                                                                       value="{{ \App\Models\TraineeAcademicQualification::EXAMINATION_JSC }}">

                                                                <div class="form-row form-group">
                                                                    <label for="jsc_examination_name"
                                                                           class="col-md-4 col-form-label">{{ __('generic.examination')}}
                                                                    </label>
                                                                    <div class="col-md-8">
                                                                        <select
                                                                            name="academicQualification[jsc][examination_name]"
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

                                                                <input type="hidden"
                                                                       name="academicQualification[jsc][result]"
                                                                       value="5">
                                                                <div class="form-row form-group mt-2">
                                                                    <label for="jsc_result"
                                                                           class="col-md-4 col-form-label">{{ __('generic.result')}}
                                                                    </label>
                                                                    <div class="col-md-8">
                                                                        <input type="number"
                                                                               name="academicQualification[jsc][grade]"
                                                                               id="jsc_gpa" class="form-control"
                                                                               width="10"
                                                                               placeholder="{{ __('generic.result')}}"
                                                                               value="{{ isset($academicQualifications[\App\Models\TraineeAcademicQualification::EXAMINATION_JSC]) ? $academicQualifications[\App\Models\TraineeAcademicQualification::EXAMINATION_JSC]->grade :  old('academicQualification.jsc.grade') }}">
                                                                    </div>
                                                                    <div class="col-md-4"></div>
                                                                </div>

                                                                <div class="form-row form-group mt-2">
                                                                    <label for="jsc_passing_year"
                                                                           class="col-md-4 col-form-label">{{ __('generic.passing_year')}}</label>
                                                                    <div class="col-md-8">
                                                                        <select
                                                                            name="academicQualification[jsc][passing_year]"
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
                                                        <div class="card col-md-12 custom-bg-gradient-info"
                                                             style="height: 100%;">
                                                            <div class="card-header">
                                                                <h3 class="card-title text-primary d-inline-flex">
                                                                    {{ __('admin.examination_name.ssc')}}
                                                                    /{{ __('generic.equivalent')}}
                                                                    /{{ __('generic.o-level')}}
                                                                    ({{ __('generic.pass')}}) </h3>
                                                            </div>
                                                            <div class="card-body ssc_collapse {{--collapse--}} hide">

                                                                <input type="hidden"
                                                                       name="academicQualification[ssc][examination]"
                                                                       value="{{ \App\Models\TraineeAcademicQualification::EXAMINATION_SSC }}">

                                                                <div class="form-row form-group">
                                                                    <label for="ssc_examination_name"
                                                                           class="col-md-4 col-form-label">{{ __('generic.examination')}}
                                                                    </label>
                                                                    <div class="col-md-8">
                                                                        <select
                                                                            name="academicQualification[ssc][examination_name]"
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
                                                                        <select
                                                                            name="academicQualification[ssc][result]"
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
                                                                               width="10"
                                                                               placeholder="{{__('generic.gpa')}}"
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
                                                                        <select
                                                                            name="academicQualification[ssc][passing_year]"
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
                                                        <div class="card custom-bg-gradient-info col-md-12"
                                                             style="height: 100%;">
                                                            <div class="card-header">
                                                                <h3 class="card-title text-primary d-inline-flex">
                                                                    {{ __('admin.examination_name.hsc')}}
                                                                    /{{ __('generic.equivalent')}}
                                                                    ({{ __('generic.pass')}})
                                                                </h3>
                                                            </div>
                                                            <div class="card-body hsc_collapse hide">
                                                                <input type="hidden"
                                                                       name="academicQualification[hsc][examination]"
                                                                       value="{{ \App\Models\TraineeAcademicQualification::EXAMINATION_HSC }}">
                                                                <div class="form-row form-group">
                                                                    <label for="hsc_examination_name"
                                                                           class="col-md-4 col-form-label">{{ __('generic.examination')}}
                                                                    </label>

                                                                    <div class="col-md-8">
                                                                        <select
                                                                            name="academicQualification[hsc][examination_name]"
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
                                                                        <input type="text"
                                                                               name="academicQualification[hsc][reg_no]"
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
                                                                        <select
                                                                            name="academicQualification[hsc][result]"
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
                                                                               width="10"
                                                                               placeholder="{{__('generic.gpa')}}"
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
                                                                        <select
                                                                            name="academicQualification[hsc][passing_year]"
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
                                                        <div class="card col-md-12 custom-bg-gradient-info"
                                                             style="height: 100%;">
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
                                                                            id="graduation_examination_name"
                                                                            class="select2">
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
                                                                               id="graduation_subject"
                                                                               class="form-control"
                                                                               value="{{ isset($academicQualifications[\App\Models\TraineeAcademicQualification::EXAMINATION_GRADUATION]) ? $academicQualifications[\App\Models\TraineeAcademicQualification::EXAMINATION_GRADUATION]->subject :  old('academicQualification.graduation.subject') }}">
                                                                    </div>
                                                                    <div class="col-md-4"></div>
                                                                </div>

                                                                <div class="form-row form-group mt-2">
                                                                    <label for="graduation_institute"
                                                                           class="col-md-4 col-form-label">{{ __('generic.institute')}}
                                                                        /{{ __('generic.university')}}</label>
                                                                    <div class="col-md-8">
                                                                        <select
                                                                            name="academicQualification[graduation][institute]"
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
                                                                        <select
                                                                            name="academicQualification[graduation][result]"
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
                                                                    <div class="" id="graduation_gpa_div">
                                                                        <input type="number"
                                                                               name="academicQualification[graduation][grade]"
                                                                               id="graduation_gpa"
                                                                               class="form-control" width="10"
                                                                               placeholder="{{__('generic.cgpa')}}"
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
                                                                            id="graduation_passing_year"
                                                                            class="select2">
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
                                                                            id="graduation_course_duration"
                                                                            class="select2">
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
                                                        <div class="card col-md-12 custom-bg-gradient-info"
                                                             style="height: 100%;">
                                                            <div class="card-header">
                                                                <h3 class="card-title text-primary d-inline-flex">{{ __('admin.examination_name.masters')}}
                                                                    ({{ __('generic.pass')}}) </h3>
                                                            </div>
                                                            <div
                                                                class="card-body masters_collapse {{--collapse--}} hide">
                                                                <input type="hidden"
                                                                       name="academicQualification[masters][examination]"
                                                                       value="{{ \App\Models\TraineeAcademicQualification::EXAMINATION_MASTERS }}">
                                                                <div class="form-row form-group">
                                                                    <label for="masters_examination_name"
                                                                           class="col-md-4 col-form-label">{{ __('generic.examination')}}</label>
                                                                    <div class="col-md-8">
                                                                        <select
                                                                            name="academicQualification[masters][examination_name]"
                                                                            id="masters_examination_name"
                                                                            class="select2">
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
                                                                        <select
                                                                            name="academicQualification[masters][institute]"
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
                                                                        <select
                                                                            name="academicQualification[masters][result]"
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
                                                                    <div class="" id="masters_gpa_div">
                                                                        <input type="number"
                                                                               name="academicQualification[masters][grade]"
                                                                               id="masters_gpa"
                                                                               class="form-control" width="10"
                                                                               placeholder="{{__('generic.cgpa')}}"
                                                                               value="{{ isset($academicQualifications[\App\Models\TraineeAcademicQualification::EXAMINATION_MASTERS]) ? $academicQualifications[\App\Models\TraineeAcademicQualification::EXAMINATION_MASTERS]->grade :  old('academicQualification.masters.grade') }}"
                                                                               hidden>
                                                                    </div>
                                                                    <div class="col-md-4"></div>
                                                                </div>

                                                                <div class="form-row form-group mt-2">
                                                                    <label for="masters_passing_year"
                                                                           class="col-md-4 col-form-label">
                                                                        {{ __('generic.passing_year')}}</label>
                                                                    <div class="col-md-8">
                                                                        <select
                                                                            name="academicQualification[masters][passing_year]"
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
                                                                            id="masters_course_duration"
                                                                            class="select2">
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

                                        <div class="row guardian-info-section">
                                            <div class="col-md-12">
                                                <h5 class="font-weight-bold">Guardian information</h5>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="card">
                                                    <div class="card-header">
                                                        <h3 class="card-title font-weight-bold text-primary">Father
                                                            information</h3>
                                                    </div>
                                                    <div class="card-body">
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label for="father_name">Name:</label>
                                                                    <input type="text"
                                                                           name="familyMember[father][name]"
                                                                           id="father_name"
                                                                           value="{{ !empty($guardian[\App\Models\TraineeFamilyMemberInfo::GUARDIAN_FATHER]) ? $guardian[\App\Models\TraineeFamilyMemberInfo::GUARDIAN_FATHER]->name : old('familyMember.father.name')  }}"
                                                                           class="form-control">
                                                                </div>
                                                            </div>

                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label for="fathers_date_of_birth">Date of

                                                                        birth:</label>
                                                                    <input type="text"
                                                                           name="familyMember[father][date_of_birth]"
                                                                           id="fathers_date_of_birth"
                                                                           value="{{ !empty($guardian[\App\Models\TraineeFamilyMemberInfo::GUARDIAN_FATHER]) ? $guardian[\App\Models\TraineeFamilyMemberInfo::GUARDIAN_FATHER]->date_of_birth : old('familyMember.father.date_of_birth')  }}"
                                                                           class="flat-date form-control">
                                                                </div>
                                                            </div>

                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label for="fathers_mobile">Mobile:</label>
                                                                    <input type="text"
                                                                           name="familyMember[father][mobile]"
                                                                           id="fathers_mobile"
                                                                           value="{{ !empty($guardian[\App\Models\TraineeFamilyMemberInfo::GUARDIAN_FATHER]) ? $guardian[\App\Models\TraineeFamilyMemberInfo::GUARDIAN_FATHER]->mobile : old('familyMember.father.mobile')  }}"
                                                                           class="form-control">
                                                                </div>
                                                            </div>
                                                            <input type="hidden"
                                                                   name="familyMember[father][relation_with_trainee]"
                                                                   value="{{ \App\Models\TraineeFamilyMemberInfo::GUARDIAN_FATHER }}">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="card">
                                                    <div class="card-header">
                                                        <h3 class="card-title font-weight-bold text-primary">Mother
                                                            information</h3>
                                                    </div>
                                                    <div class="card-body">
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label for="mother_name">Name:</label>
                                                                    <input type="text"
                                                                           name="familyMember[mother][name]"
                                                                           value="{{ !empty($guardian[\App\Models\TraineeFamilyMemberInfo::GUARDIAN_MOTHER]) ? $guardian[\App\Models\TraineeFamilyMemberInfo::GUARDIAN_MOTHER]->name : old('familyMember.mother.name')  }}"
                                                                           id="mother_name"
                                                                           class="form-control">
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label for="mothers_date_of_birth">Date of
                                                                        birth:</label>
                                                                    <input type="text"
                                                                           name="familyMember[mother][date_of_birth]"
                                                                           id="mothers_date_of_birth"
                                                                           value="{{ !empty($guardian[\App\Models\TraineeFamilyMemberInfo::GUARDIAN_MOTHER]) ? $guardian[\App\Models\TraineeFamilyMemberInfo::GUARDIAN_MOTHER]->date_of_birth : old('familyMember.mother.date_of_birth')  }}"
                                                                           class="flat-date form-control">
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label for="mothers_mobile">Mobile:</label>
                                                                    <input type="text"
                                                                           name="familyMember[mother][mobile]"
                                                                           id="mothers_mobile"
                                                                           value="{{ !empty($guardian[\App\Models\TraineeFamilyMemberInfo::GUARDIAN_MOTHER]) ? $guardian[\App\Models\TraineeFamilyMemberInfo::GUARDIAN_MOTHER]->mobile : old('familyMember.mother.mobile')  }}"
                                                                           class="form-control">
                                                                </div>
                                                            </div>
                                                            <input type="hidden"
                                                                   name="familyMember[mother][relation_with_trainee]"
                                                                   value="{{ \App\Models\TraineeFamilyMemberInfo::GUARDIAN_MOTHER }}">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label for="guardian"
                                                       class="font-weight-bold">Guardian:
                                                </label>
                                                <div class="input-group">
                                                    <div class="custom-control custom-radio mr-3">
                                                        <input class="custom-control-input" type="radio"
                                                               value="{{ App\Models\TraineeFamilyMemberInfo::GUARDIAN_FATHER }}"
                                                               id="guardian-father"
                                                               name="guardian" {{ old('guardian') == App\Models\TraineeFamilyMemberInfo::GUARDIAN_FATHER ? 'checked' : ''}}>
                                                        <label for="guardian-father"
                                                               class="custom-control-label">Father</label>
                                                    </div>

                                                    <div class="custom-control custom-radio mr-3">
                                                        <input class="custom-control-input" type="radio"
                                                               value="{{ App\Models\TraineeFamilyMemberInfo::GUARDIAN_MOTHER }}"
                                                               id="guardian-mother"
                                                               name="guardian" {{ old('guardian') == App\Models\TraineeFamilyMemberInfo::GUARDIAN_MOTHER ? 'checked' : ''}}>
                                                        <label for="guardian-mother"
                                                               class="custom-control-label">Mother</label>
                                                    </div>

                                                    <div class="custom-control custom-radio mr-3">
                                                        <input class="custom-control-input" type="radio"
                                                               value="{{ App\Models\TraineeFamilyMemberInfo::GUARDIAN_OTHER }}"
                                                               id="guardian-other"
                                                               name="guardian" {{ old('guardian') == App\Models\TraineeFamilyMemberInfo::GUARDIAN_OTHER ? 'checked' : ''}}>
                                                        <label for="guardian-other"
                                                               class="custom-control-label">Other</label>
                                                    </div>

                                                </div>

                                                <div class="card guardian-another" style="display: none">
                                                    <div class="row card-body">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="guardian_name">Name:</label>
                                                                <input type="text"
                                                                       name="familyMember[guardian][name]"
                                                                       value="{{old('familyMember.guardian.name')}}"
                                                                       id="guardian_name"
                                                                       class="form-control">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="guardian_date_of_birth">Date of
                                                                    birth:</label>
                                                                <input type="text"
                                                                       name="familyMember[guardian][date_of_birth]"
                                                                       value="{{ old('familyMember.guardian.date_of_birth') }}"
                                                                       id="guardian_date_of_birth"
                                                                       class="flat-date form-control">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="guardian_mobile">Mobile</label>
                                                                <input type="text"
                                                                       name="familyMember[guardian][mobile]"
                                                                       id="guardian_mobile"
                                                                       value="{{ old('familyMember.guardian.mobile') }}"
                                                                       class="form-control">
                                                            </div>
                                                        </div>

                                                        <input type="hidden"
                                                               name="familyMember[guardian][relation_with_trainee]"
                                                               id="guardian_relation_with_trainee"
                                                               value="{{ \App\Models\TraineeFamilyMemberInfo::GUARDIAN_OTHER }}"
                                                               class="form-control">

                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label
                                                                    for="relation">Relation:</label>
                                                                <input type="text"
                                                                       name="familyMember[guardian][relation]"
                                                                       id="relation"
                                                                       value="{{ old('familyMember.guardian.relation_with_trainee') }}"
                                                                       class="form-control">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                        <div class="col-md-12 mt-2">
                                            <div class="row justify-content-center">
                                                <input type="submit" class="btn btn-primary"
                                                       value="{{ __("generic.enroll") }}">
                                            </div>
                                        </div>
                                        <div class="overlay" style="display: none">
                                            <i class="fas fa-2x fa-sync-alt fa-spin"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    @endif

                </div>
            </div>
        </div>
    </div>

@endsection
@push('css')
    <style>
        .card-background-white {
            background: #faf8fb;
        }

        .form-control {
            border: 1px solid #671688;
            color: #671688;
        }

        .form-control:focus {
            border-color: #671688;
        }

        .button-bg {
            background: #671688;
            border-color: #671688;
        }

        .button-bg:hover {
            color: #ffffff;
            background-color: #671688 !important;
            border-color: #671688 !important;
        }

        .button-bg:active {
            color: #ffffff;
            background-color: #671688 !important;
            border-color: #671688 !important;
        }

        .button-bg:focus {
            color: #ffffff;
            background-color: #671688 !important;
            border-color: #671688 !important;
        }

        .button-bg:visited {
            color: #ffffff;
            background-color: #671688 !important;
            border-color: #671688 !important;
        }

        .card-header-title {
            min-height: 48px;
        }

        .card-bar-home-course img {
            height: 14vw;
        }

        .gray-color {
            color: #73727f;
        }

        .course-heading-wrap {
            text-overflow: ellipsis;
            white-space: nowrap;
            overflow: hidden;
        }

        .course-heading-wrap:hover {
            overflow: visible;
            white-space: normal;
            cursor: pointer;
        }

        .course-p {
            font-size: 14px;
            font-weight: 400;
            color: #671688;
        }

        .header-bg {
            background: #671688;
            color: white;
        }

        .modal-header .close, .modal-header .mailbox-attachment-close {
            padding: 1rem;
            margin: -1rem -1rem -1rem auto;
            color: white;
            outline: none;
        }

        .card-p1 {
            color: #671688;
        }
    </style>
@endpush
@push('js')
    <x-generic-validation-error-toastr></x-generic-validation-error-toastr>
    <script>
        const COURSE = {!! $course !!};
        const APPLICATION_FORM_SETTINGS = COURSE?.application_form_settings;
        const isRequiredGuardianInfo = APPLICATION_FORM_SETTINGS?.guardianInfo?.is_required;
        const isRequiredJSCInfo = !!APPLICATION_FORM_SETTINGS?.JSCInfo?.is_required;
        const isRequiredSSCInfo = !!APPLICATION_FORM_SETTINGS?.SSCInfo?.is_required;
        const isRequiredHSCInfo = !!APPLICATION_FORM_SETTINGS?.HSCInfo?.is_required;
        const isRequiredHonsInfo = !!APPLICATION_FORM_SETTINGS?.HonoursInfo?.is_required;
        const isRequiredMastersInfo = !!APPLICATION_FORM_SETTINGS?.MastersInfo?.is_required;
        const isRequiredAddress = !!APPLICATION_FORM_SETTINGS?.address?.is_required;
        const isRequiredEthnicGroup = !!APPLICATION_FORM_SETTINGS?.ethnicGroup?.is_required;

        const courseEnrollmentForm = $(".courseEnrollmentForm");

        const validator = courseEnrollmentForm.validate({
            errorElement: "em",
            onkeyup: false,
            errorPlacement: function (error, element) {
                error.addClass("help-block");
                element.parents(".form-group").addClass("has-feedback");

                if (element.parents(".form-group").length) {
                    error.insertAfter(element.parents(".form-group").first().children().last());
                } else if (element.hasClass('select2') || element.hasClass('select2-ajax-custom') || element.hasClass('select2-ajax')) {
                    error.insertAfter(element.parents(".form-group").first().find('.select2-container'));
                } else {
                    error.insertAfter(element);
                }
            },
            highlight: function (element, errorClass, validClass) {
                $(element).parents(".form-group").addClass("has-error").removeClass("has-success");
                $(element).closest('.help-block').remove();
            },
            unhighlight: function (element, errorClass, validClass) {
                $(element).parents(".form-group").addClass("has-success").removeClass("has-error");
            },

            rules: {
                "academicQualification[jsc][examination_name]": {
                    required: isRequiredJSCInfo,
                },
                "academicQualification[jsc][board]": {
                    required: isRequiredJSCInfo,
                },
                "academicQualification[jsc][roll_no]": {
                    required: isRequiredJSCInfo,
                    pattern: "^[1-9]\\d*$",
                },

                "academicQualification[jsc][reg_no]": {
                    required: isRequiredJSCInfo,
                    pattern: "^[1-9]\\d*$",
                },
                "academicQualification[jsc][result]": {
                    required: isRequiredJSCInfo,
                },
                "academicQualification[jsc][group]": {
                    required: isRequiredJSCInfo,
                },
                "academicQualification[jsc][passing_year]": {
                    required: isRequiredJSCInfo,
                },
                "academicQualification[jsc][grade]": {
                    required: isRequiredJSCInfo,
                    min: 1,
                    max: 5
                },

                "academicQualification[ssc][examination_name]": {
                    required: isRequiredSSCInfo,
                },
                "academicQualification[ssc][board]": {
                    required: isRequiredSSCInfo,
                },
                "academicQualification[ssc][roll_no]": {
                    required: isRequiredSSCInfo,
                    pattern: "^[1-9]\\d*$",
                },

                "academicQualification[ssc][reg_no]": {
                    required: isRequiredSSCInfo,
                    pattern: "^[1-9]\\d*$",
                },
                "academicQualification[ssc][result]": {
                    required: isRequiredSSCInfo,
                },
                "academicQualification[ssc][group]": {
                    required: isRequiredSSCInfo,
                },
                "academicQualification[ssc][passing_year]": {
                    required: isRequiredSSCInfo,
                },
                "academicQualification[ssc][grade]": {
                    required: function () {
                        return $('#ssc_result').val() == {!! App\Models\TraineeAcademicQualification::EXAMINATION_RESULT_GPA_OUT_OF_FOUR !!} || $('#ssc_result').val() == {!! App\Models\TraineeAcademicQualification::EXAMINATION_RESULT_GPA_OUT_OF_FIVE !!};
                    },
                    min: 1,
                    max: function () {
                        if ($('#ssc_result').val() == {!! App\Models\TraineeAcademicQualification::EXAMINATION_RESULT_GPA_OUT_OF_FOUR !!}) {
                            return 4
                        }
                        if ($('#ssc_result').val() == {!! App\Models\TraineeAcademicQualification::EXAMINATION_RESULT_GPA_OUT_OF_FIVE !!}) {
                            return 5;
                        }
                    },
                },

                "academicQualification[hsc][examination_name]": {
                    required: isRequiredHSCInfo,
                },
                "academicQualification[hsc][board]": {
                    required: isRequiredHSCInfo,
                },

                "academicQualification[hsc][roll_no]": {
                    required: isRequiredHSCInfo,
                    pattern: "^[1-9]\\d*$",
                },
                "academicQualification[hsc][reg_no]": {
                    required: isRequiredHSCInfo,
                    pattern: "^[1-9]\\d*$",
                },
                "academicQualification[hsc][group]": {
                    required: isRequiredHSCInfo,
                },
                "academicQualification[hsc][passing_year]": {
                    required: isRequiredHSCInfo,
                },
                "academicQualification[hsc][result]": {
                    required: isRequiredHSCInfo,
                },
                "academicQualification[hsc][grade]": {
                    required: function () {
                        return $('#hsc_result').val() == {!! App\Models\TraineeAcademicQualification::EXAMINATION_RESULT_GPA_OUT_OF_FOUR !!} || $('#hsc_result').val() == {!! App\Models\TraineeAcademicQualification::EXAMINATION_RESULT_GPA_OUT_OF_FIVE !!};
                    },
                    min: 1,
                    max: function () {
                        if ($('#hsc_result').val() == {!! App\Models\TraineeAcademicQualification::EXAMINATION_RESULT_GPA_OUT_OF_FOUR !!}) {
                            return 4
                        }
                        if ($('#hsc_result').val() == {!! App\Models\TraineeAcademicQualification::EXAMINATION_RESULT_GPA_OUT_OF_FIVE !!}) {
                            return 5;
                        }
                    },
                },
                "academicQualification[graduation][examination_name]": {
                    required: isRequiredHonsInfo,
                },

                "academicQualification[graduation][institute]": {
                    required: isRequiredHonsInfo,
                },

                "academicQualification[graduation][subject]": {
                    required: isRequiredHonsInfo,
                },

                "academicQualification[graduation][result]": {
                    required: isRequiredHonsInfo,
                },


                "academicQualification[graduation][grade]": {
                    required: function () {
                        return $('#graduation_result').val() == {!! App\Models\TraineeAcademicQualification::EXAMINATION_RESULT_GPA_OUT_OF_FOUR !!} || $('#graduation_result').val() == {!! App\Models\TraineeAcademicQualification::EXAMINATION_RESULT_GPA_OUT_OF_FIVE !!};
                    },
                    min: 1,
                    max: function () {
                        if ($('#graduation_result').val() == {!!App\Models\TraineeAcademicQualification::EXAMINATION_RESULT_GPA_OUT_OF_FOUR !!}) {
                            return 4
                        }
                        if ($('#graduation_result').val() == {!!App\Models\TraineeAcademicQualification::EXAMINATION_RESULT_GPA_OUT_OF_FIVE !!}) {
                            return 5;
                        }
                    },
                },
                "academicQualification[graduation][passing_year]": {
                    required: isRequiredHonsInfo,
                },
                "academicQualification[graduation][course_duration]": {
                    required: isRequiredHonsInfo,
                },

                "academicQualification[masters][examination_name]": {
                    required: isRequiredMastersInfo,
                },
                "academicQualification[masters][institute]": {
                    required: isRequiredMastersInfo,
                },
                "academicQualification[masters][subject]": {
                    required: isRequiredMastersInfo,
                },
                "academicQualification[masters][result]": {
                    required: isRequiredMastersInfo,
                },
                "academicQualification[masters][grade]": {
                    required: function () {
                        return $('#masters_result').val() == {!! App\Models\TraineeAcademicQualification::EXAMINATION_RESULT_GPA_OUT_OF_FOUR !!} || $('#masters_result').val() == {!! App\Models\TraineeAcademicQualification::EXAMINATION_RESULT_GPA_OUT_OF_FIVE !!};
                    },
                    min: 1,
                    max: function () {
                        if ($('#masters_result').val() == {!! App\Models\TraineeAcademicQualification::EXAMINATION_RESULT_GPA_OUT_OF_FOUR !!}) {
                            return 4
                        }
                        if ($('#masters_result').val() == {!! App\Models\TraineeAcademicQualification::EXAMINATION_RESULT_GPA_OUT_OF_FIVE !!}) {
                            return 5;
                        }
                    },
                },
                "academicQualification[masters][passing_year]": {
                    required: isRequiredMastersInfo,
                },
                "academicQualification[masters][course_duration]": {
                    required: isRequiredMastersInfo,
                },

                ethnic_group: {
                    required: isRequiredEthnicGroup,
                },
                "familyMember[father][name]": {
                    required: isRequiredGuardianInfo,
                },
                "familyMember[father][nid]": {
                    required: isRequiredGuardianInfo,
                },
                "familyMember[father][mobile]": {
                    required: isRequiredGuardianInfo,
                },
                "familyMember[father][date_of_birth]": {
                    required: isRequiredGuardianInfo,
                },
                "familyMember[mother][name]": {
                    required: isRequiredGuardianInfo,
                },
                "familyMember[mother][nid]": {
                    required: isRequiredGuardianInfo,
                },
                "familyMember[mother][mobile]": {
                    required: isRequiredGuardianInfo,
                },
                "familyMember[mother][date_of_birth]": {
                    required: isRequiredGuardianInfo,
                },
                guardian: {
                    required: function () {
                        return $(".guardian-information").css('display') == 'block';
                    }
                },
                "familyMember[guardian][name]": {
                    required: function () {
                        return $("input[name = 'guardian']:checked").val() == {!! App\Models\TraineeFamilyMemberInfo::GUARDIAN_OTHER !!};
                    },
                },
                "familyMember[guardian][date_of_birth]": {
                    required: function () {
                        return $("input[name = 'guardian']:checked").val() == {!! App\Models\TraineeFamilyMemberInfo::GUARDIAN_OTHER !!};
                    }
                },
                "familyMember[guardian][mobile]": {
                    required: false,
                },
                "familyMember[guardian][relation_with_trainee]": {
                    required: function () {
                        return $("input[name = 'guardian']:checked").val() == {!! App\Models\TraineeFamilyMemberInfo::GUARDIAN_OTHER !!};
                    }
                },
                "familyMember[guardian][nid]": {
                    required: false,
                },

                current_employment_status: {
                    required: function () {
                        return $('.occupation-information').css('display') == 'block';
                    },
                },
                year_of_experience: {
                    number: true,
                },
            },
            messages: {},

            submitHandler: function (htmlForm) {
                $('.overlay').show();

                // Get form values
                const form = $(htmlForm);
                const formData = new FormData(htmlForm);
                const url = form.attr("action");

                $.ajax({
                    url: url,
                    data: formData,
                    type: 'POST',
                    processData: false,
                    contentType: false,
                }).then((response) => {
                    let alertType = response.alertType;
                    let alertMessage = response.message;
                    let alerter = toastr[alertType];
                    alerter ? alerter(alertMessage) : toastr.error("toastr alert-type " + alertType + " is unknown");
                    redirectToCourseEnrollmentSuccessPage(response, COURSE.id);
                }).catch((error) => {
                    if (typeof error.responseJSON.errors !== 'undefined') {
                        $.each(error.responseJSON.errors, function (key, value) {
                            if (Array.isArray(value)) {
                                toastr.error(value[0]);
                            } else {
                                toastr.error(value);
                            }
                        });
                    } else if (typeof error.responseJSON.message !== 'undefined') {
                        toastr.error(error.responseJSON.message);
                    } else {
                        toastr.error(error.responseJSON);
                    }
                }).always(() => {
                    $('.overlay').hide();
                })

                return false;
            }
        });

        function redirectToCourseEnrollmentSuccessPage(response, courseId) {
            if (response.alertType == 'success' && courseId) {
                window.location.href = '{{ route('frontend.show.course-enroll-success', ['courseId' => '__']) }}'.replace('__', courseId);
            }
        }

        function setFormFields(settings) {

            if (settings?.FreedomFighter?.should_present_in_form) {
                $('#freedom-fighter-status-section').show();
            } else {
                $('#freedom-fighter-status-section').hide();
            }

            if (settings?.ethnicGroup?.should_present_in_form) {
                $('#ethnic-group-section').show();
            } else {
                $('#ethnic-group-section').hide();
            }

            settings?.JSCInfo?.should_present_in_form ? $('.jsc_collapse').parent().parent().show() : $('.jsc_collapse').parent().parent().hide()
            settings?.SSCInfo?.should_present_in_form ? $('.ssc_collapse').parent().parent().show() : $('.ssc_collapse').parent().parent().hide();
            settings?.HSCInfo?.should_present_in_form ? $('.hsc_collapse').parent().parent().show() : $('.hsc_collapse').parent().parent().hide();
            settings?.HonoursInfo?.should_present_in_form ? $('.graduation_collapse').parent().parent().show() : $('.graduation_collapse').parent().parent().hide();
            settings?.MastersInfo?.should_present_in_form ? $('.masters_collapse').parent().parent().show() : $('.masters_collapse').parent().parent().hide();
            settings?.address?.should_present_in_form ? $('.address-section').show() : $('.address-section').hide();
            settings?.guardianInfo?.should_present_in_form ? $('.guardian-info-section').show() : $('.guardian-info-section').hide();


            if (settings?.SSCInfo) {
                showGPAInputField('ssc');
            }

            if (settings?.HSCInfo) {
                showGPAInputField('hsc');
            }

            if (settings?.HonoursInfo) {
                showGPAInputField('graduation');
            }

            if (settings?.MastersInfo) {
                showGPAInputField('masters');
            }

            // if no setting's found for academic information then hide the academic information header
            if (!settings?.SSCInfo?.should_present_in_form || !settings.HSCInfo?.should_present_in_form || !settings?.HonoursInfo?.should_present_in_form || !settings?.MastersInfo?.should_present_in_form) {
                $('.academic-info-section').hide();
            }
        }


        function showGPAInputField(examName) {
            if ($('#' + examName + '_result').val() == {!! App\Models\TraineeAcademicQualification::EXAMINATION_RESULT_GPA_OUT_OF_FOUR !!} || $('#' + examName + '_result').val() == {!! App\Models\TraineeAcademicQualification::EXAMINATION_RESULT_GPA_OUT_OF_FIVE !!}) {
                $('#' + examName + '_gpa').removeAttr('hidden');
                $('#' + examName + '_result_div').removeAttr('class').addClass('col-md-6');
                $('#' + examName + '_gpa_div').addClass('col-md-2');
            } else {
                $('#' + examName + '_gpa').attr('hidden', true);
                $('#' + examName + '_result_div').removeAttr('class').addClass('col-md-8');
                $('#' + examName + '_gpa_div').removeAttr('class');
            }

            return false;
        }

        $(document).ready(function () {
            setFormFields(APPLICATION_FORM_SETTINGS);
            let count = 1;

            $('.batch-list').on('click', function () {
                if ($(this).is(':checked')) {
                    $('.selected-batch-list').append('<li id="batch-list-item-' + $(this).attr("data-id") + '" class="list-group-item"><span class="count"><strong>' + count + '. </strong></span><input name="batches[]"' +
                        ' type="hidden" value="' + $(this).attr("data-id") + '"/><label for="Batch title">' + $(this).val() + '</lable></li>');
                    count++;
                } else {
                    $("#batch-list-item-" + $(this).attr("data-id")).remove();
                    count--;

                    $('.selected-batch-list li').each(function () {
                        $(this).find('span').remove();
                        $(this).prepend("<span class='count'><strong>" + ($(this).index() + 1) + ". </strong></span>");
                    });
                }
            });


            $("input[name = 'guardian']").on('change', function () {

                if ($(this).val() == {!! App\Models\TraineeFamilyMemberInfo::GUARDIAN_OTHER !!}) {
                    $('.guardian-another').show(500);
                } else {
                    $('.guardian-another').hide(500);
                }
            });
        });


        $("#ssc_result").on('change', function () {
            if ($(this).val() == {!! App\Models\TraineeAcademicQualification::EXAMINATION_RESULT_GPA_OUT_OF_FOUR !!}
                || $(this).val() == {!! App\Models\TraineeAcademicQualification::EXAMINATION_RESULT_GPA_OUT_OF_FIVE !!}) {
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
            if ($(this).val() == {!! App\Models\TraineeAcademicQualification::EXAMINATION_RESULT_GPA_OUT_OF_FOUR !!}
                || $(this).val() == {!! App\Models\TraineeAcademicQualification::EXAMINATION_RESULT_GPA_OUT_OF_FIVE !!}) {
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
            if ($(this).val() == {!! App\Models\TraineeAcademicQualification::EXAMINATION_RESULT_GPA_OUT_OF_FOUR !!}
                || $(this).val() == {!! App\Models\TraineeAcademicQualification::EXAMINATION_RESULT_GPA_OUT_OF_FIVE !!}) {
                $('#graduation_gpa').removeAttr('hidden');

                $('#graduation_result_div').removeAttr('class');
                $('#graduation_result_div').addClass('col-md-6');
                $('#graduation_gpa_div').addClass('col-md-2');
            } else {
                $('#graduation_gpa').attr('hidden', true);

                $('#graduation_result_div').removeAttr('class');
                $('#graduation_result_div').addClass('col-md-8');
                $('#graduation_gpa_div').removeAttr('class');
            }
        });

        $("#masters_result").on('change', function () {
            if ($(this).val() == {!! App\Models\TraineeAcademicQualification::EXAMINATION_RESULT_GPA_OUT_OF_FOUR !!}
                || $(this).val() == {!! App\Models\TraineeAcademicQualification::EXAMINATION_RESULT_GPA_OUT_OF_FIVE !!}) {
                $('#masters_gpa').removeAttr('hidden');

                $('#masters_result_div').removeAttr('class');
                $('#masters_result_div').addClass('col-md-6');
                $('#masters_gpa_div').addClass('col-md-2');
            } else {
                $('#masters_gpa').attr('hidden', true);

                $('#masters_result_div').removeAttr('class');
                $('#masters_result_div').addClass('col-md-8');
                $('#masters_gpa_div').removeAttr('class');
            }
        });
    </script>
@endpush
