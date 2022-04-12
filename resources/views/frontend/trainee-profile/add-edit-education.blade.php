@extends('master::layouts.front-end')

@section('title')
{{$siteSettingInfo->site_title}} :: {{ 'Trainee education' }}
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <form class="row edit-add-form" method="post"
                      enctype="multipart/form-data"
                      action="{{route('frontend.trainee-education-info.store')}}">
                    @csrf

                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header  academic-qualifications">
                                <h3 class="card-title font-weight-bold"><i
                                        class="fa fa-address-book"> </i> {{ __('generic.academic_qualification')}}
                                </h3>
                                <div class="card-tools">
                                    <a href="{{route('frontend.trainee')}}"
                                       class="btn btn-sm btn-outline-primary btn-rounded">
                                        <i class="fas fa-backward"></i> {{ __('generic.back_to_profile') }}
                                    </a>
                                </div>
                            </div>
                            <div class="card-body row">
                                <div class="col-md-6 academic-qualification-jsc mb-2">
                                    <div class="card col-md-12 " style="height: 100%;">
                                        <div class="card-header">
                                            <h3 class="card-title text-bold d-inline-flex">{{ __('admin.examination_name.jsc')}}
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
                                    <div class="card col-md-12 " style="height: 100%;">
                                        <div class="card-header">
                                            <h3 class="card-title text-bold d-inline-flex">
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
                                    <div class="card  col-md-12" style="height: 100%;">
                                        <div class="card-header">
                                            <h3 class="card-title text-bold d-inline-flex">
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
                                    <div class="card col-md-12 " style="height: 100%;">
                                        <div class="card-header">
                                            <h3 class="card-title text-bold d-inline-flex">{{ __('admin.examination_name.honors')}}
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
                                    <div class="card col-md-12 " style="height: 100%;">
                                        <div class="card-header">
                                            <h3 class="card-title text-bold d-inline-flex">{{ __('admin.examination_name.masters')}}
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
                                                           placeholder="{{ __('generic.cgpa')}}"
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

                    <div class="col-sm-12 text-center pb-3">
                        <button type="submit"
                                class="btn btn-success btn-lg">{{ __('generic.save') }}</button>
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/lodash.js/4.17.21/lodash.js"></script>

    <x-generic-validation-error-toastr></x-generic-validation-error-toastr>

    <script>

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
                'academicQualification[jsc][grade]': {
                    max: 5
                }
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

    </script>
@endpush



