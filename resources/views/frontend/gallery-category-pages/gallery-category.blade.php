@php
    $currentInstitute = app('currentInstitute');
    $layout = 'master::layouts.front-end';
@endphp
@extends($layout)

@section('title')
{{$siteSettingInfo->site_title}} :: {{__('generic.albums')}} - ({{ $galleryCategory->title }})
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
                <div class="col-md-12">
                    <div class="card mb-0">
                        <div class="card-header p-5">
                            <h2 class="text-center text-dark font-weight-bold mt-4">{{ $galleryCategory->title }}</h2>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                @if($galleries->count())
                                    @foreach($galleries as $gallery)
                                        <div class="col-md-3">
                                            <div class="card">
                                                <div class="card-body p-0">
                                                    <a href="" data-toggle="modal"
                                                       data-target="#gallery_id_{{$gallery->id}}">
                                                        @if($gallery->content_type == \App\Models\Gallery::CONTENT_TYPE_IMAGE)
                                                            <img class="img-responsive"
                                                                 style="width: 100%; height: 200px"
                                                                 src="{{asset('/storage/'. $gallery->content_path)}}">
                                                        @elseif(\App\Models\Gallery::CONTENT_TYPE_VIDEO == $gallery->content_type && $gallery->is_youtube_video )
                                                            <div class="position-relative">
                                                                <div class="iframe-layer"></div>
                                                                <div class="iframe-class">
                                                                    <iframe width="100%" height="200px"
                                                                            style="border: none"
                                                                            src={{"https://www.youtube.com/embed/".$gallery->you_tube_video_id}}>
                                                                    </iframe>
                                                                </div>
                                                            </div>
                                                        @elseif(\App\Models\Gallery::CONTENT_TYPE_VIDEO == $gallery->content_type)
                                                            <div class="position-relative">
                                                                <div class="iframe-layer"></div>
                                                                <div class="iframe-class">
                                                                    <iframe width="100%" height="200px"
                                                                            style="border: none"
                                                                            src={{asset('storage/' .$gallery->content_path)}}>
                                                                    </iframe>
                                                                </div>
                                                            </div>
                                                        @endif
                                                    </a>
                                                </div>
                                                <div class="card-footer overflow-hidden">
                                                    <h6 class="gallery-heading-wrap">{{$gallery->content_title}}</h6>
                                                    <h6 class="float-right ">{{$gallery->created_at->format('d-m-Y')}}</h6>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                @else
                                    <div class="col-md-12 text-center">
                                        <i class="fa fa-sad-tear fa-2x text-warning mb-3"></i>
                                        <h5 class="text-danger">{{__('generic.this_album_is_empty')}}</h5>
                                    </div>
                                @endif

                            </div>
                        </div>
                    </div>
                </div>
        </div>

    </div>

    <!-- Modal -->
    @if(!empty($galleries))
        @foreach($galleries as $gallery)
            <div class="modal fade" id="gallery_id_{{ $gallery->id }}" tabindex="-1" role="dialog"
                 aria-labelledby="gallery_id_{{ $gallery->id }}" aria-hidden="true">
                <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                    <div class="modal-content gallery_modal">
                        <div class="modal-header">
                            <button type="button" class="close modal_close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div>
                            @if($gallery->content_type == \App\Models\Gallery::CONTENT_TYPE_IMAGE)
                                <div class="card-overlay bg-black">
                                    <img class="img-responsive gallery-image"
                                         src="{{asset('/storage/'. $gallery->content_path)}}" width="100%">
                                </div>
                            @elseif(\App\Models\Gallery::CONTENT_TYPE_VIDEO == $gallery->content_type && $gallery->is_youtube_video)
                                <div class="embed-responsive embed-responsive-16by9">
                                    <iframe class="embed-responsive-item"
                                            src="{{'https://www.youtube.com/embed/'.$gallery->you_tube_video_id}}">
                                    </iframe>
                                </div>
                            @elseif(\App\Models\Gallery::CONTENT_TYPE_VIDEO == $gallery->content_type)
                                <div class="embed-responsive embed-responsive-16by9">
                                    <iframe class="embed-responsive-item"
                                            src="{{asset('storage/' .$gallery->content_path)}}">
                                    </iframe>
                                </div>
                            @endif
                        </div>
                        <div class="modal-footer justify-content-between">
                            <h5>{{$gallery->content_title}}</h5>
                            <h5>{{$gallery->created_at->format('d-m-Y')}}</h5>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    @endif
@endsection

@push('css')
    <style>
        .modal{
            background: #000b16;
        }
        .gallery-image {
            display: block;
            position: relative;
            left: 0;
            top: 0;
            z-index: 0;
            background: url("http://lorempixel.com/1000/600/") no-repeat center center;
            background-size: cover;
        }

        .iframe-class {
            z-index: -1;
        }

        .iframe-layer {
            position: absolute;
            height: 100%;
            z-index: 99;
            width: 100%;
            opacity: .0001;
        }

        .modal_close {
            z-index: 999;
        }
        .gallery-heading-wrap {
            text-overflow: ellipsis;
            white-space: nowrap;
            overflow: hidden;
        }
        .gallery-heading-wrap:hover {
            overflow: visible;
            white-space: normal;
            cursor: pointer;
        }

        .card{
            box-shadow: 0px 5px 5px #e5e5e5;
        }

    </style>
@endpush

@push('js')
    <script>
    </script>
@endpush
