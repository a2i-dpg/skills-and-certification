@php
    /** @var \App\Models\User $authUser */
    $authUser = \App\Helpers\Classes\AuthHelper::getAuthUser();
@endphp


@extends('master::layouts.master')

@section('title')
    {{ __('Batch') }}
@endsection

@section('content')
    <div class="container-fluid">
        <div class="card">
            <div class="card-header d-flex justify-content-between custom-bg-gradient-info">
                <h3 class="card-title font-weight-bold text-primary">{{__('admin.batch.index')}}</h3>
                <div class="card-tools">
                    <div class="btn-group">
                        <a href="{{route('admin.batches.edit', [$batch->id])}}"
                           class="btn btn-sm btn-outline-primary btn-rounded">
                            <i class="fas fa-plus-circle"></i> {{__('admin.batch.edit')}}
                        </a>
                        <a href="{{route('admin.batches.index')}}"
                           class="btn btn-sm btn-outline-primary btn-rounded">
                            <i class="fas fa-backward"></i> {{__('admin.common.back')}}
                        </a>
                    </div>
                </div>
            </div>
            <div class="row card-body">

                <div class="col-md-6 custom-view-box">
                    <p class="label-text">{{__('admin.batch.title')}}</p>
                    <div class="input-box">
                        {{ $batch->title }}
                    </div>
                </div>

                <div class="col-md-6 custom-view-box">
                    <p class="label-text">{{__('admin.batch.course')}}</p>
                    <div class="input-box">
                        {{ $batch->institute->title .' - ' .$batch->title }}
                    </div>
                </div>

                <div class="col-md-6 custom-view-box">
                    <p class="label-text">{{__('admin.batch.code')}}</p>
                    <div class="input-box">
                        {{ $batch->code }}
                    </div>
                </div>

                <div class="col-md-6 custom-view-box">
                    <p class="label-text">{{ __('admin.batch.application_start_date') }}</p>
                    <div class="input-box flat">
                        {{ $batch->application_start_date }}
                    </div>
                </div>
                <div class="col-md-6 custom-view-box">
                    <p class="label-text">{{ __('admin.batch.application_end_date') }}</p>
                    <div class="input-box">
                        {{ $batch->application_end_date }}
                    </div>
                </div>
                <div class="col-md-6 custom-view-box">
                    <p class="label-text">{{ __('admin.batch.batch_start_date') }}</p>
                    <div class="input-box">
                        {{ $batch->batch_start_date }}
                    </div>
                </div> <div class="col-md-6 custom-view-box">
                    <p class="label-text">{{ __('admin.batch.batch_end_date') }}</p>
                    <div class="input-box">
                        {{ $batch->batch_end_date }}
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection
