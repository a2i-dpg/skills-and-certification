@php
    /** @var \App\Models\User $authUser */
    $authUser = \App\Helpers\Classes\AuthHelper::getAuthUser();
@endphp


@extends('master::layouts.master')

@section('title')
    {{ __('admin.intro-video.index') }}
@endsection

@section('content')
    <div class="container-fluid">
        <div class="card">
            <div class="card-header  custom-bg-gradient-info">
                <h3 class="card-title text-primary font-weight-bold">{{ __('admin.intro-video.index') }}</h3>

                <div class="card-tools">
                    <div class="btn-group">
                        <a href="{{route('admin.intro-videos.edit', [$introVideo->id])}}"
                           class="btn btn-sm btn-outline-primary btn-rounded">
                            <i class="fas fa-plus-circle"></i> {{ __('admin.intro-video.edit') }}
                        </a>
                        <a href="{{route('admin.intro-videos.index')}}"
                           class="btn btn-sm btn-outline-primary btn-rounded">
                            <i class="fas fa-backward"></i> {{__('admin.common.back')}}
                        </a>
                    </div>
                </div>

            </div>
            <div class="row card-body">

                @if(!$authUser->isUserBelongsToInstitute())
                    <div class="col-md-6 custom-view-box">
                        <p class="label-text">{{ __('admin.intro-video.institute_title') }}</p>
                        <div class="input-box">
                            {{ $introVideo->institute->title }}
                        </div>
                    </div>
                @endif


                    <div class="col-md-6 custom-view-box">
                        <p class="label-text">{{ __('admin.intro-video.youtube_video_url') }}</p>
                        <div class="input-box">
                            {{ $introVideo->youtube_video_url ?? 'N/A' }}
                        </div>
                    </div>

                    <div class="col-md-6 custom-view-box">
                        <p class="label-text">{{ __('admin.intro-video.youtube_video_id') }}</p>
                        <div class="input-box">
                            {{ $introVideo->youtube_video_id ?? 'N/A' }}
                        </div>
                    </div>

                    <div class="col-md-6 custom-view-box">
                        <p class="label-text">{{ __('admin.common.status') }}</p>
                        <div class="input-box">
                            {!! $introVideo->getCurrentRowStatus(true) !!}
                        </div>
                    </div>

                    <div class="col-md-6 custom-view-box">
                        <p class="label-text">{{ __('admin.intro-video.video_content') }}</p>
                        <div class="input-box">
                            <div class="embed-responsive embed-responsive-16by9"
                                 style="height: 200px; width: 100%;">
                                <iframe class="embed-responsive-item"
                                        src="{{ 'https://www.youtube.com/embed/'. $introVideo->youtube_video_id .'?rel=0' }}"
                                        allowfullscreen></iframe>
                            </div>
                        </div>
                    </div>
            </div>
        </div>
    </div>
@endsection
