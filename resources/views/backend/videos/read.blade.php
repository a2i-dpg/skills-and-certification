@extends('master::layouts.master')

@section('title')
    {{ __('admin.videos.index')  }}
@endsection

@section('content')
    <div class="container-fluid">
        <div class="card">
            <div class="card-header  custom-bg-gradient-info">
                <h3 class="card-title text-primary font-weight-bold">{{ __('admin.videos.index')  }}</h3>

                <div class="card-tools">
                    <div class="btn-group">
                        <a href="{{route('admin.videos.edit', [$video->id])}}"
                           class="btn btn-sm btn-outline-primary btn-rounded">
                            <i class="fas fa-plus-circle"></i> {{ __('admin.videos.edit')  }}
                        </a>
                        <a href="{{route('admin.videos.index')}}" class="btn btn-sm btn-outline-primary btn-rounded">
                            <i class="fas fa-backward"></i> {{__('admin.common.back')}}
                        </a>
                    </div>
                </div>

            </div>
            <div class="row card-body">
                <div class="col-md-6 custom-view-box">
                    <p class="label-text">{{ __('admin.videos.title')  }}</p>
                    <div class="input-box">
                        {{ $video->title }}
                    </div>
                </div>

                <div class="col-md-6 custom-view-box">
                    <p class="label-text">{{ __('admin.videos.institute_title')  }}</p>
                    <div class="input-box">
                        {{ $video->institute->title }}
                    </div>
                </div>

                <div class="col-md-6 custom-view-box">
                    <p class="label-text">{{ __('admin.videos.video_category')  }}</p>
                    <div class="input-box">
                        {{ optional($video->videoCategory)->title }}
                    </div>
                </div>
                <div class="col-md-6 custom-view-box">
                    <p class="label-text">{{ __('admin.videos.video_type')  }}</p>
                    <div class="input-box">
                        {{ $video->video_type == App\Models\Video::VIDEO_TYPE_YOUTUBE_VIDEO ? 'Youtube video' : 'Uploaded video' }}
                    </div>
                </div>
                <div class="col-md-6 custom-view-box">
                    <p class="label-text">{{ __('admin.videos.youtube_video_id')  }}</p>
                    <div class="input-box">
                        {{ $video->youtube_video_id ?? 'N/A' }}
                    </div>
                </div>

                <div class="col-md-6 custom-view-box">
                    <p class="label-text">{{ __('admin.videos.video_category')  }}</p>
                    <div class="input-box">
                        {{ $video->uploaded_video_path ?? 'N/A' }}
                    </div>
                </div>

                <div class="col-md-6 custom-view-box">
                    <p class="label-text">{{ __('admin.common.status')  }}</p>
                    <div class="input-box">
                        {!! $video->getCurrentRowStatus(true) !!}
                    </div>
                </div>

                <div class="col-md-6 custom-view-box">
                    <p class="label-text">{{ __('admin.videos.video_content')  }}</p>
                    <div class="input-box">
                        @if($video->video_type == \App\Models\Video::VIDEO_TYPE_YOUTUBE_VIDEO)
                            <div class="embed-responsive embed-responsive-16by9"
                                 style="height: 200px; width: 100%;">
                                <iframe class="embed-responsive-item"
                                        src="{{ 'https://www.youtube.com/embed/'. $video->youtube_video_id .'?rel=0' }}"
                                        allowfullscreen></iframe>
                            </div>
                        @elseif($video->video_type == \App\Models\Video::VIDEO_TYPE_UPLOADED_VIDEO)
                            <div class="embed-responsive embed-responsive-16by9"
                                 style="height: 200px; width: 100%;">
                                <video controls>
                                    <source src="{{ '/storage/'.$video->uploaded_video_path }}"
                                            type="video/mp4">
                                </video>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
