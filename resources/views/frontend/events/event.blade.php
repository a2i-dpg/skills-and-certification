@php
    $currentInstitute = app('currentInstitute');
    $layout = 'master::layouts.front-end';
@endphp
@extends($layout)

@section('title')
{{$siteSettingInfo->site_title}} :: {{ !empty($event)?$event->caption:'' }}
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12 p-0">
                <div class="col-md-12 p-0">
                    <div class="card mb-0">
                        <div class="card-header p-5">
                            <h2 class="text-center text-dark font-weight-bold mt-4">{{ strtoupper(!empty($event)?$event->caption:'N/A') }}</h2>
                        </div>
                        <div class="card-body bg-gray-light">
                            <div class="row">
                                <div class="col-md-10 mx-auto">
                                    <div>
                                        @if(!empty($event->image))
                                            <img src="{{ asset("storage/{$event->image}") }}" width="100%">
                                        @endif
                                        <div class="bg-info p-2">
                                            <p class="mb-0">
                                                <i class="far fa-calendar-minus gray-color"></i>
                                                <span
                                                    class="accordion-date">{{ !empty($event)? \App\Helpers\Classes\EnglishToBanglaDate::dateFormatEnglishToBangla(date("j F Y(l) h:i A", strtotime($event->date))):'' }}</span>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-10 mx-auto py-5">
                                    {{ !empty($event)?$event->details:'' }}
                                </div>

                            </div>
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
        .modal {
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

    </style>
@endpush

@push('js')
    <script>
    </script>
@endpush
