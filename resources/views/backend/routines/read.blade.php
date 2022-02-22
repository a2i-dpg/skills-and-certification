@php
    /** @var \App\Models\User $authUser */
    $authUser = \App\Helpers\Classes\AuthHelper::getAuthUser();
@endphp
@extends('master::layouts.master')

@section('title')
    {{ __('admin.routine.list') }} :: {{ __('admin.common.read') }}
@endsection

@section('content')
    <div class="container-fluid">
        <div class="card">
            <div class="card-header text-primary custom-bg-gradient-info">
                <h3 class="card-title font-weight-bold">{{ __('admin.common.read') }}</h3>

                <div class="card-tools">
                    <div class="btn-group">
                        <a href="{{route('admin.routines.edit', [$routine->id])}}" class="btn btn-sm btn-outline-primary btn-rounded">
                            <i class="fas fa-plus-circle"></i> {{ __('admin.common.edit') }}
                        </a>
                        <a href="{{route('admin.routines.index')}}" class="btn btn-sm btn-outline-primary btn-rounded">
                            <i class="fas fa-backward"></i> {{ __('admin.common.back') }}
                        </a>
                    </div>
                </div>
            </div>
            <div class="row card-body">
                <div class="col-md-6 custom-view-box">
                    <p class="label-text">{{__('admin.routine.date')}}</p>
                    <div class="input-box">
                        {{ $routine->date }}
                    </div>
                </div>
                <div class="col-md-6 custom-view-box">
                    <p class="label-text">{{__('admin.routine.training_center')}}</p>
                    <div class="input-box">
                        {{ $routine->trainingCenter->title }}
                    </div>
                </div>
                <div class="col-md-6 custom-view-box">
                    <p class="label-text">{{__('admin.routine.batch_title')}}</p>
                    <div class="input-box">
                        {{ $routine->batch->title }}
                    </div>
                </div>

                <div class="col-sm-12 course-sessions mt-5">
                    <div class="card">
                        <div class="card-header custom-bg-gradient-info">
                            <h3 class="card-title text-primary font-weight-bold">{{__('admin.daily_routine.day_routine')}} </h3>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12 course-session-contents">
                                    @foreach($routineClasses as $routineClass)
                                        <div class="card" id="session-no-0">
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-md-6 custom-view-box">
                                                        <p class="label-text"> {{__('admin.daily_routine.trainer')}}</p>
                                                        <div class="input-box">
                                                            {{ optional($routineClass->trainer)->name}}
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6 custom-view-box">
                                                        <p class="label-text">{{__('admin.daily_routine.class')}}</p>
                                                        <div class="input-box">
                                                            {{ $routineClass->class }}
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6 custom-view-box">
                                                        <p class="label-text">{{__('admin.daily_routine.start_time')}}</p>
                                                        <div class="input-box">
                                                            {{ date("g:i A", strtotime($routineClass->start_time)) }}
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6 custom-view-box">
                                                        <p class="label-text">{{__('admin.daily_routine.end_time')}}</p>
                                                        <div class="input-box">
                                                            {{ date("g:i A", strtotime($routineClass->end_time)) }}
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
