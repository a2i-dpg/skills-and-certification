@extends('master::layouts.master')

@section('title')
    {{__('admin.gallery.index')}}
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-4">
                <div class="user-details card mb-3">
                    <div
                        class="card-header custom-bg-gradient-info font-weight-bold text-primary">{{__('admin.gallery.media')}}</div>
                    @if( \App\Models\Gallery::CONTENT_TYPE_IMAGE == $gallery->content_type)
                        <div class="col-md-12 custom-view-box text-center mt-3">

                            <div class="card-header d-flex justify-content-between custom-bg-gradient-info">
                                <img src="{{asset('storage/' .$gallery->content_path)}}" class="img-fluid"
                                     alt="Responsive image" style="height: 300px; width: 100%">
                            </div>
                            <p class="label-text">{{__('admin.gallery.image')}}</p>

                        </div>
                    @elseif(\App\Models\Gallery::CONTENT_TYPE_VIDEO == $gallery->content_type && $gallery->is_youtube_video )
                        <div class="col-md-12 custom-view-box text-center mt-3">
                            <iframe width="100%" height="100%"
                                    src={{"https://www.youtube.com/embed/".$gallery->you_tube_video_id}}>
                            </iframe>

                            <p class="label-text">{{__('admin.gallery.youtube_video_content')}}</p>

                        </div>

                    @elseif(\App\Models\Gallery::CONTENT_TYPE_VIDEO == $gallery->content_type)
                        <div class="col-md-12 custom-view-box text-center mt-3">
                            <iframe width="100%" height="100%"
                                    src="{{asset('storage/' .$gallery->content_path)}}">
                            </iframe>
                            <p class="label-text">{{__('admin.gallery.uploaded_video_content')}}</p>

                        </div>
                    @endif
                </div>

            </div>
            <div class="col-md-8">

                <div class="card">
                    <div class="card-header text-primary custom-bg-gradient-info">
                        <h3 class="card-title font-weight-bold">{{__('admin.gallery.index')}}</h3>

                        <div class="card-tools">
                            <div class="btn-group">
                                <a href="{{route('admin.galleries.edit', [$gallery->id])}}"
                                   class="btn btn-sm btn-outline-primary btn-rounded">
                                    <i class="fas fa-plus-circle"></i> {{__('admin.gallery.edit')}}
                                </a>
                                <a href="{{route('admin.galleries.index')}}"
                                   class="btn btn-sm btn-outline-primary btn-rounded">
                                    <i class="fas fa-backward"></i> {{__('admin.common.back')}}
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="row card-body">
                        <div class="col-md-6 custom-view-box">
                            <p class="label-text"> {{__('admin.gallery.caption')}}</p>
                            <div class="input-box">
                                {{ $gallery->content_title }}
                            </div>
                        </div>

                        <div class="col-md-6 custom-view-box">
                            <p class="label-text">{{__('admin.gallery.content_type')}}</p>
                            <div class="input-box">
                                {{ \App\Models\Gallery::CONTENT_TYPES[$gallery->content_type]}}
                            </div>
                        </div>

                        <div class="col-md-6 custom-view-box">
                            <p class="label-text">{{__('admin.gallery.institute_title')}}</p>
                            <div class="input-box">
                                {{ $gallery->institute->title }}
                            </div>
                        </div>
                        <div class="col-md-6 custom-view-box">
                            <p class="label-text">{{__('admin.gallery.publish_date')}}</p>
                            <div class="input-box">
                                {{ $gallery->publish_date }}
                            </div>
                        </div>
                        <div class="col-md-6 custom-view-box">
                            <p class="label-text">{{__('admin.gallery.archive_date')}}</p>
                            <div class="input-box">
                                {{ $gallery->archive_date }}
                            </div>
                        </div>
                        @if($gallery->content_type==\App\Models\Gallery::CONTENT_TYPE_VIDEO)
                            <div class="col-md-6 custom-view-box">
                                <p class="label-text">{{__('admin.gallery.video_type')}}</p>
                                <div class="input-box">
                                    {{ $gallery->is_youtube_video ? __('admin.gallery.youtube'): __('admin.gallery.upload_video')}}
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

            </div>

        </div>

    </div>
@endsection
