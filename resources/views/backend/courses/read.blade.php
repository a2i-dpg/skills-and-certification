@extends('master::layouts.master')
@php
    $otherOptionsToDisplayInForm = [
            'ethnicGroup' => ['label' => 'Ethnic group', 'should_present_in_form' => false, 'is_required' => false],
            'FreedomFighter' => ['label' => 'Freedom fighter', 'should_present_in_form' => false, 'is_required' => false],
            'OccupationInfo' => ['label' => 'Occupation info', 'should_present_in_form' => false, 'is_required' => false],
            'SSCInfo' => ['label' => 'SSC info', 'should_present_in_form' => false, 'is_required' => false],
            'HSCInfo' => ['label' => 'HSC info', 'should_present_in_form' => false, 'is_required' => false],
            'HonoursInfo' => ['label' => 'Honours info', 'should_present_in_form' => false, 'is_required' => false],
            'MastersInfo' => ['label' => 'Masters info', 'should_present_in_form' => false, 'is_required' => false],
        ];

        if ($course->application_form_settings && count($course->application_form_settings)) {
            foreach ($otherOptionsToDisplayInForm as $key => $setting) {
                if (!empty($course->application_form_settings[$key])) {
                    $otherOptionsToDisplayInForm[$key]['should_present_in_form'] = !empty($course->application_form_settings[$key]['should_present_in_form']) && $course->application_form_settings[$key]['should_present_in_form'];
                    $otherOptionsToDisplayInForm[$key]['is_required'] = !empty($course->application_form_settings[$key]['is_required']) && $course->application_form_settings[$key]['is_required'];
                }
            }
        }
@endphp
@section('title')
    {{ __('Course') }}
@endsection

@section('content')
    <div class="container-fluid">
        <div class="card">
            <div class="card-header text-primary custom-bg-gradient-info">
                <h3 class="card-title font-weight-bold">{{ __('admin.course.index') }}</h3>

                <div class="card-tools">
                    <div class="btn-group">
                        <a href="{{route('admin.courses.edit', [$course->id])}}"
                           class="btn btn-sm btn-outline-primary btn-rounded">
                            <i class="fas fa-plus-circle"></i> {{ __('admin.course.edit') }}
                        </a>
                        <a href="{{route('admin.courses.index')}}" class="btn btn-sm btn-outline-primary btn-rounded">
                            <i class="fas fa-backward"></i> {{__('admin.common.back')}}
                        </a>
                    </div>
                </div>

            </div>
            <div class="row card-body">
                <div class="col-md-12 custom-view-box">
                    <div class="card-header d-flex justify-content-between custom-bg-gradient-info">
                        <img src="{{asset('storage/' .$course->cover_image)}}" class="img-fluid" alt="Responsive image"
                             style="height: 300px; width: 100%">
                    </div>
                </div>


                <div class="col-md-6 custom-view-box">
                    <p class="label-text">{{ __('admin.course.institute') }}</p>
                    <div class="input-box">
                        {{ optional($course->institute)->title }}
                    </div>
                </div>

                <div class="col-md-6 custom-view-box">
                    <p class="label-text">{{ __('generic.programme') }}</p>
                    <div class="input-box">
                        {{ optional($course->programme)->title }}
                    </div>
                </div>

                <div class="col-md-6 custom-view-box">
                    <p class="label-text">{{ __('admin.course.title') }}</p>
                    <div class="input-box">
                        {{ $course->title }}
                    </div>
                </div>

                <div class="col-md-6 custom-view-box">
                    <p class="label-text">{{ __('admin.course.code') }}</p>
                    <div class="input-box">
                        {{ $course->code }}
                    </div>
                </div>

                <div class="col-md-6 custom-view-box">
                    <p class="label-text">{{ __('admin.course.course_fee') }}</p>
                    <div class="input-box">
                        {{ $course->course_fee }}
                    </div>
                </div>

                <div class="col-md-6 custom-view-box">
                    <p class="label-text">{{ __('admin.course.duration') }}</p>
                    <div class="input-box">
                        {{ $course->duration }}
                    </div>
                </div>

                <div class="col-md-6 custom-view-box">
                    <p class="label-text">{{ __('admin.course.target_group') }}</p>
                    <div class="input-box">
                        {{ $course->target_group }}
                    </div>
                </div>

                <div class="col-md-6 custom-view-box">
                    <p class="label-text">{{ __('admin.course.object') }}</p>
                    <div class="input-box">
                        {{ $course->objects }}
                    </div>
                </div>

                <div class="col-md-6 custom-view-box">
                    <p class="label-text">{{ __('admin.course.content') }}</p>
                    <div class="input-box">
                        {{ $course->contents }}
                    </div>
                </div>

                <div class="col-md-6 custom-view-box">
                    <p class="label-text">{{ __('admin.course.training_methodology') }}</p>
                    <div class="input-box">
                        {{ $course->training_methodology }}
                    </div>
                </div>

                <div class="col-md-6 custom-view-box">
                    <p class="label-text">{{ __('admin.course.evaluation_system') }}</p>
                    <div class="input-box">
                        {{ $course->evaluation_system }}
                    </div>
                </div>

                <div class="col-md-6 custom-view-box">
                    <p class="label-text">{{ __('admin.course.course_prerequisite') }}</p>
                    <div class="input-box">
                        {{ $course->prerequisite ?? ""}}
                    </div>
                </div>
                <div class="col-md-6 custom-view-box">
                    <p class="label-text">{{ __('admin.course.eligibility') }}</p>
                    <div class="input-box">
                        {{ $course->eligibility ?? ""}}
                    </div>
                </div>

                <div class="col-md-6 custom-view-box">
                    <p class="label-text">{{ __('admin.course.description') }}</p>
                    <div class="input-box">
                        {{ $course->description ?? "" }}
                    </div>
                </div>

                <div class="col-md-6 custom-view-box">
                    <p class="label-text">{{ __('admin.course.status') }}</p>
                    <div class="input-box">
                        {!! $course->getCurrentRowStatus(true) !!}
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="row">
                        <div class="col-md-12">
                            <label for="Other information for course enrollment form.">Other information for
                                course enrollment form.</label>
                        </div>
                        @foreach($otherOptionsToDisplayInForm as $option => $details)
                            <div class="col-md-6">
                                <div class="form-group form-inline">
                                    <div class="form-check">
                                        <input class="form-check-input"
                                               name="applicationFormSettings[{{$option}}][should_present_in_form]"
                                               type="checkbox"
                                               onchange="onChangeOptionalSettings(this)"
                                               @if($details['should_present_in_form']) checked @endif
                                               id="{{$option}}">
                                        <label class="form-check-label" for="{{$option}}">
                                            {{$details['label']}}
                                        </label>
                                    </div>

                                    <div class="custom-control custom-switch ml-5"
                                         @if(!$details['should_present_in_form']) style="display: none" @endif>
                                        <input type="checkbox"
                                               name="applicationFormSettings[{{$option}}][is_required]"
                                               class="custom-control-input"
                                               id="{{$option}}-is-required"
                                               @if($details['is_required']) checked @endif>
                                        <label class="custom-control-label"
                                               for="{{$option}}-is-required">Required?</label>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
