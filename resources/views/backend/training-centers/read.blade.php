@php
    /** @var \App\Models\User $authUser */
    $authUser = \App\Helpers\Classes\AuthHelper::getAuthUser();
@endphp

@extends('master::layouts.master')

@section('title')
    {{__('admin.training_center.index') }}
@endsection

@section('content')
    <div class="container-fluid">
        <div class="card">
            <div class="card-header text-primary custom-bg-gradient-info">
                <h3 class="card-title font-weight-bold"> {{__('admin.training_center.index') }}</h3>

                <div class="card-tools">
                    <div class="btn-group">
                        <a href="{{route('admin.training-centers.edit', [$trainingCenter->id])}}"
                           class="btn btn-sm btn-outline-primary btn-rounded">
                            <i class="fas fa-plus-circle"></i> {{__('admin.training_center.edit') }}
                        </a>
                        <a href="{{route('admin.training-centers.index')}}"
                           class="btn btn-sm btn-outline-primary btn-rounded">
                            <i class="fas fa-backward"></i> {{__('admin.common.back')}}
                        </a>
                    </div>
                </div>

            </div>
            <div class="row card-body">
                <div class="col-md-6 custom-view-box">
                    <p class="label-text"> {{__('admin.training_center.title') }}</p>
                    <div class="input-box">
                        {{ $trainingCenter->title }}
                    </div>
                </div>

                <div class="col-md-6 custom-view-box">
                    <p class="label-text"> {{__('admin.training_center.address') }}</p>
                    <div class="input-box" style="min-height: 100px;">
                        {{ $trainingCenter->address }}
                    </div>
                </div>

                <div class="col-md-6 custom-view-box">
                    <p class="label-text"> {{__('admin.training_center.google_map_src') }}</p>
                    <div class="input-box" style="min-height: 100px;">
                        {{ $trainingCenter->google_map_src }}
                    </div>
                </div>

                @if(!$authUser->isUserBelongsToInstitute())
                    <div class="col-md-6 mt-2 custom-view-box">
                        <p class="label-text"> {{__('admin.training_center.institute_title') }}</p>
                        <div class="input-box">
                            {{ $trainingCenter->institute->title }}
                        </div>
                    </div>
                @endif

                <div class="col-md-6 custom-view-box">
                    <p class="label-text">{{__('admin.training_center.course_coordinator_signature') }}</p>
                    <div class="input-box">
                        <img src="{{ asset("storage/$trainingCenter->course_coordinator_signature") }}" alt="" title=""
                             height="50px"/>
                    </div>
                </div>

                <div class="col-md-6 custom-view-box">
                    <p class="label-text">{{__('admin.training_center.course_director_signature') }}</p>
                    <div class="input-box">
                        <img src="{{ asset("storage/$trainingCenter->course_director_signature") }}" alt="" title=""
                             height="50px"/>
                    </div>
                </div>

                <div class="col-md-6 custom-view-box">
                    <p class="label-text">{{__('admin.training_center.mobile') }}</p>
                    <div class="input-box">
                        {{ $trainingCenter->mobile }}
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection
