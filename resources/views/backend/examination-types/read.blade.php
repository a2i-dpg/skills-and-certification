@php
    /** @var \App\Models\User $authUser */
    $authUser = \App\Helpers\Classes\AuthHelper::getAuthUser();
@endphp
@extends('master::layouts.master')

@section('title')
    {{ __('admin.examination_type.list') }} :: {{ __('admin.common.read') }}
@endsection

@section('content')
    <div class="container-fluid">
        <div class="card">
            <div class="card-header text-primary custom-bg-gradient-info">
                <h3 class="card-title font-weight-bold">{{ __('admin.examination_type.read') }}</h3>

                <div class="card-tools">
                    <div class="btn-group">
                        <a href="{{route('admin.examination-types.edit', [$examinationType->id])}}" class="btn btn-sm btn-outline-primary btn-rounded">
                            <i class="fas fa-plus-circle"></i> {{ __('admin.common.edit') }}
                        </a>
                        <a href="{{route('admin.examination-types.index')}}" class="btn btn-sm btn-outline-primary btn-rounded">
                            <i class="fas fa-backward"></i> {{ __('admin.common.back') }}
                        </a>
                    </div>
                </div>
            </div>

            <div class="row card-body">

                <div class="col-md-6 custom-view-box">
                    <p class="label-text">{{__('admin.examination_type.title')}}</p>
                    <div class="input-box">
                        {{ $examinationType->title }}
                    </div>
                </div>



            </div>
        </div>
    </div>
@endsection
