@php
    $currentInstitute = app('currentInstitute');
    $layout = 'master::layouts.front-end';
@endphp

@extends($layout)

@section('title')
{{$siteSettingInfo->site_title}} :: {{__('generic.video')}}
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card mb-2">
                    <div class="card-header p-5">
                        <h2 class="text-center text-dark font-weight-bold">{{ $traineeVideos && $traineeVideos->title ? $traineeVideos->title:'' }}</h2>
                    </div>
                    <div class="px-5 py-4">
                        <div class="row">
                            <div class="col-md-10 mx-auto">
                                @if($traineeVideos->youtube_video_id !=null)
                                    <iframe
                                        src="https://www.youtube.com/embed/{{ !empty($traineeVideos)? $traineeVideos->youtube_video_id:'' }}?autoplay=1"
                                        frameborder="0"
                                        height="500px" width="100%"
                                        allow='accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share'
                                        allowFullScreen>
                                    </iframe>
                                @else
                                    <iframe
                                        src="{{ asset("storage/{$traineeVideos->uploaded_video_path}") }}"
                                        frameborder="0"
                                        height="500px" width="100%"
                                        allow='accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share'
                                        allowFullScreen>
                                    </iframe>
                                @endif
                            </div>
                        </div>

                    </div>
                </div>
            </div>

        </div>
@endsection

