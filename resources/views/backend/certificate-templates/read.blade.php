@php
    /** @var \App\Models\User $authUser */
    $authUser = \App\Helpers\Classes\AuthHelper::getAuthUser();
@endphp


@extends('master::layouts.master')

@section('title')
    {{ __('admin.certificate-template.index') }}
@endsection

@section('content')


    <div class="container-fluid">
        <div class="card">
            <div class="card-header text-primary custom-bg-gradient-info">
                <h3 class="card-title font-weight-bold">{{ __('admin.certificate-template.index') }}</h3>

                <div class="card-tools">
                    <div class="btn-group">
                        <a href="{{route('admin.certificate-templates.edit', [$certificateTemplate->id])}}"
                           class="btn btn-sm btn-outline-primary btn-rounded">
                            <i class="fas fa-plus-circle"></i> {{ __('admin.certificate-template.edit') }}
                        </a>
                        <a href="{{route('admin.certificate-templates.index')}}"
                           class="btn btn-sm btn-outline-primary btn-rounded">
                            <i class="fas fa-backward"></i> {{__('admin.common.back')}}
                        </a>
                    </div>
                </div>
            </div>

            <div class="row card-body">

                <div class="col-md-6 custom-view-box">
                    <p class="label-text">{{ __('admin.certificate-template.title') }}</p>
                    <div class="input-box">
                        {{ $certificateTemplate->title }}
                    </div>
                </div>
                @if(!$authUser->isUserBelongsToInstitute())
                    <div class="col-md-6 custom-view-box">
                        <p class="label-text">{{ __('admin.certificate-template.institute_title') }}</p>
                        <div class="input-box">
                            {{ $certificateTemplate->institute->title }}
                        </div>
                    </div>
                @endif


                <div class="col-md-6 custom-view-box mt-2">
                    <p class="label-text">{{ __('admin.common.status') }}</p>
                    <div class="input-box">
                        {!! $certificateTemplate->row_status == 1 ? '<span class="badge badge-success">Yes</span>' : '<span class="badge badge-danger">No</span>' !!}
                    </div>
                </div>

                <div class="col-md-6 mt-2 custom-view-box">
                    <p class="label-text">{{ __('admin.certificate-template.cover_image') }}</p>
                    <div class="input-box">
                        <img src="{{ asset("storage/{$certificateTemplate->image}") }}" alt="Cover Image" title="" height="22px"  />
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
