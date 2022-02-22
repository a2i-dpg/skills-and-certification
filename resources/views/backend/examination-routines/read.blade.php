@php
    /** @var \App\Models\User $authUser */
    $authUser = \App\Helpers\Classes\AuthHelper::getAuthUser();
@endphp
@extends('master::layouts.master')

@section('title')
    {{ __('admin.examination_routine.list') }} :: {{ __('admin.common.read') }}
@endsection

@section('content')
    <div class="container-fluid">
        <div class="card">
            <div class="card-header text-primary custom-bg-gradient-info">
                <h3 class="card-title font-weight-bold">{{ __('admin.examination.read') }}</h3>

                <div class="card-tools">
                    <div class="btn-group">
                        <a href="{{route('admin.examination-routines.edit', [$examinationRoutine->id])}}" class="btn btn-sm btn-outline-primary btn-rounded">
                            <i class="fas fa-plus-circle"></i> {{ __('admin.common.edit') }}
                        </a>
                        <a href="{{route('admin.examination-routines.index')}}" class="btn btn-sm btn-outline-primary btn-rounded">
                            <i class="fas fa-backward"></i> {{ __('admin.common.back') }}
                        </a>
                    </div>
                </div>
            </div>

            <div class="row card-body">

                <div class="col-md-6 custom-view-box">
                    <p class="label-text">{{__('admin.examination_routine.date')}}</p>
                    <div class="input-box">
                        {{ $examinationRoutine->date }}
                    </div>
                </div>

                <div class="col-md-6 custom-view-box">
                    <p class="label-text">{{__('admin.examination_routine.training_center')}}</p>
                    <div class="input-box">
                        {{ $examinationRoutine->trainingCenter->title }}
                    </div>
                </div>
                <div class="col-md-6 custom-view-box">
                    <p class="label-text">{{__('admin.examination_routine.batch_title')}}</p>
                    <div class="input-box">
                        {{ $examinationRoutine->batch->title }}
                    </div>
                </div>

                <div class="col-sm-12 course-sessions mt-5">
                    <div class="card">
                        <div class="card-header custom-bg-gradient-info">
                            <h3 class="card-title text-primary font-weight-bold">{{__('admin.examination_routine.day_routine')}} </h3>
                        </div>
                        <div class="card-body">
                            <div class="row">



                                <div class="col-md-12 course-session-contents">
                                    @foreach($examinationRoutine->examinationRoutineDetail as $examinationRoutineDetail)
                                        <div class="card" id="session-no-0">
                                            <div class="card-body">
                                                <div class="row">

                                                    <div class="col-md-6 custom-view-box">
                                                        <p class="label-text">{{__('admin.examination_routine.examination')}}</p>
                                                        <div class="input-box">
                                                            {{ @$examinationRoutineDetail->examination->code}} -- {{ substr(@$examinationRoutineDetail->examination->exam_details, 0 , 100)}}
                                                        </div>
                                                    </div>


                                                    <div class="col-md-6 custom-view-box">
                                                        <p class="label-text">{{__('admin.examination_routine.start_time')}}</p>
                                                        <div class="input-box">
                                                            {{ date("g:i A", strtotime($examinationRoutineDetail->start_time)) }}
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6 custom-view-box">
                                                        <p class="label-text">{{__('admin.examination_routine.end_time')}}</p>
                                                        <div class="input-box">
                                                            {{ date("g:i A", strtotime($examinationRoutineDetail->end_time)) }}
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection
