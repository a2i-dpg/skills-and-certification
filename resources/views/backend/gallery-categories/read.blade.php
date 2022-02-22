@php
    /** @var \App\Models\User $authUser */
    $authUser = \App\Helpers\Classes\AuthHelper::getAuthUser();
@endphp


@extends('master::layouts.master')

@section('title')
    {{ __('admin.gallery-album.index') }}
@endsection

@section('content')


    <div class="container-fluid">
        <div class="card">
            <div class="card-header text-primary custom-bg-gradient-info">
                <h3 class="card-title font-weight-bold">{{ __('admin.gallery-album.index') }}</h3>

                <div class="card-tools">
                    <div class="btn-group">
                        <a href="{{route('admin.gallery-categories.edit', [$galleryCategory->id])}}"
                           class="btn btn-sm btn-outline-primary btn-rounded">
                            <i class="fas fa-plus-circle"></i> {{ __('admin.gallery-album.edit') }}
                        </a>
                        <a href="{{route('admin.gallery-categories.index')}}"
                           class="btn btn-sm btn-outline-primary btn-rounded">
                            <i class="fas fa-backward"></i> {{__('admin.common.back')}}
                        </a>
                    </div>
                </div>
            </div>

            <div class="row card-body">

                <div class="col-md-6 custom-view-box">
                    <p class="label-text">{{ __('admin.gallery-album.title') }}</p>
                    <div class="input-box">
                        {{ $galleryCategory->title }}
                    </div>
                </div>
                @if(!$authUser->isUserBelongsToInstitute())
                    <div class="col-md-6 custom-view-box">
                        <p class="label-text">{{ __('admin.gallery-album.institute_title') }}</p>
                        <div class="input-box">
                            {{ $galleryCategory->institute->title }}
                        </div>
                    </div>
                @endif


                <div class="col-md-6 custom-view-box">
                    <p class="label-text">{{ __('admin.gallery-album.batch_title') }}</p>
                    <div class="input-box">
                        {{ !empty($galleryCategory->batch->title) }}
                    </div>
                </div>

                <div class="col-md-6 custom-view-box">
                    <p class="label-text">{{ __('generic.featured') }}</p>
                    <div class="input-box">
                        {!! $galleryCategory->featured == 1 ? '<span class="badge badge-success">Yes</span>' : '<span class="badge badge-danger">No</span>' !!}
                    </div>
                </div>
                <div class="col-md-6 custom-view-box mt-2">
                    <p class="label-text">{{ __('admin.common.status') }}</p>
                    <div class="input-box">
                        {!! $galleryCategory->row_status == 1 ? '<span class="badge badge-success">Yes</span>' : '<span class="badge badge-danger">No</span>' !!}
                    </div>
                </div>

                <div class="col-md-6 mt-2 custom-view-box">
                    <p class="label-text">{{ __('admin.gallery-album.cover_image') }}</p>
                    <div class="input-box">
                        <img src="{{ asset("storage/{$galleryCategory->image}") }}" alt="Cover Image" title="" height="22px"  />
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
