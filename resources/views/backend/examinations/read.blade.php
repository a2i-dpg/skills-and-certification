@php
    /** @var \App\Models\User $authUser */
    $authUser = \App\Helpers\Classes\AuthHelper::getAuthUser();
@endphp
@extends('master::layouts.master')

@section('title')
    {{ __('admin.examination.list') }} :: {{ __('admin.examination.read') }}
@endsection

@section('content')
    <div class="container-fluid">
        <div class="card">
            <div class="card-header text-primary custom-bg-gradient-info">
                <h3 class="card-title font-weight-bold">{{ __('admin.examination.read') }}</h3>

                <div class="card-tools">
                    <div class="btn-group">
                        <a href="{{route('admin.examinations.edit', [$examination->id])}}" class="btn btn-sm btn-outline-primary btn-rounded">
                            <i class="fas fa-plus-circle"></i> {{ __('admin.common.edit') }}
                        </a>
                        <a href="{{route('admin.examinations.index')}}" class="btn btn-sm btn-outline-primary btn-rounded">
                            <i class="fas fa-backward"></i> {{ __('admin.common.back') }}
                        </a>
                    </div>
                </div>
            </div>

            <div class="row card-body">
                <div class="col-md-6 custom-view-box">
                    <p class="label-text">{{__('admin.examination.training_center')}}</p>
                    <div class="input-box">
                        {{ $examination->trainingCenter->title }}
                    </div>
                </div>
                <div class="col-md-6 custom-view-box">
                    <p class="label-text">{{__('admin.examination.batch_title')}}</p>
                    <div class="input-box">
                        {{ $examination->batch->title }}
                    </div>
                </div>
                <div class="col-md-6 custom-view-box">
                    <p class="label-text">{{__('admin.examination.examination_type')}}</p>
                    <div class="input-box">
                        {{ $examination->examinationType->title }}
                    </div>
                </div>
                <div class="col-md-6 custom-view-box">
                    <p class="label-text">{{__('admin.examination.code')}}</p>
                    <div class="input-box">
                        {{ $examination->code }}
                    </div>
                </div>
                <div class="col-md-6 custom-view-box">
                    <p class="label-text">{{__('admin.examination.total_mark')}}</p>
                    <div class="input-box">
                        {{ $examination->total_mark }}
                    </div>
                </div>
                <div class="col-md-6 custom-view-box">
                    <p class="label-text">{{__('admin.examination.pass_mark')}}</p>
                    <div class="input-box">
                        {{ $examination->pass_mark }}
                    </div>
                </div>

                <div class="col-md-6 custom-view-box">
                    <p class="label-text">{{__('admin.examination.exam_details')}}</p>
                    <div class="input-box">
                        {{ $examination->exam_details }}
                    </div>
                </div>



            </div>
        </div>
    </div>
@endsection
