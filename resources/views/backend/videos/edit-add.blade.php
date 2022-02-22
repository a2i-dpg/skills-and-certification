@php
    $edit = !empty($video->id);
    /** @var \App\Models\User $authUser */
    $authUser = \App\Helpers\Classes\AuthHelper::getAuthUser();
@endphp

@extends('master::layouts.master')

@section('title')
    {{ ! $edit ? __('admin.videos.add')  : __('admin.videos.update')  }}
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card card-outline">
                    <div class="card-header  custom-bg-gradient-info">
                        <h3 class="card-title text-primary font-weight-bold">{{ ! $edit ? __('admin.videos.add')  : __('admin.videos.update')  }}</h3>

                        <div class="card-tools">
                            <a href="{{route('admin.videos.index')}}"
                               class="btn btn-sm btn-outline-primary btn-rounded">
                                <i class="fas fa-backward"></i> {{__('admin.common.back')}}
                            </a>
                        </div>
                    </div>

                    <div class="card-body">
                        <form class="row edit-add-form" method="post" enctype="multipart/form-data"
                              action="{{ $edit ? route('admin.videos.update', [$video->id]) : route('admin.videos.store')}}">
                            @csrf
                            @if($edit)
                                @method('put')
                            @endif
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="title">{{__('admin.videos.title')  }}<span
                                            class="required"> * </span></label>
                                    <input type="text" class="form-control" id="title"
                                           name="title"
                                           value="{{ $edit ? $video->title : old('title') }}"
                                           placeholder="{{__('admin.videos.title')  }}">
                                </div>
                            </div>

                            @if($authUser->isUserBelongsToInstitute())
                                <input type="hidden" id="institute_id" name="institute_id"
                                       value="{{$authUser->institute_id}}">
                            @else
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label for="institute_id">{{__('admin.videos.institute_title')  }}<span
                                                class="required"> * </span></label>
                                        <select class="form-control select2-ajax-wizard"
                                                name="institute_id"
                                                id="institute_id"
                                                data-model="{{base64_encode(App\Models\Institute::class)}}"
                                                data-label-fields="{title}"
                                                data-dependent-fields="#video_category_id"
                                                @if($edit)
                                                data-preselected-option="{{json_encode(['text' =>  $video->institute->title, 'id' =>  $video->institute->id])}}"
                                                @endif
                                                data-placeholder="{{__('admin.videos.institute_title')  }}"
                                        >
                                        </select>
                                    </div>
                                </div>
                            @endif

                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="video_category_id">{{__('admin.videos.video_category')  }}</label>
                                    <select class="form-control select2-ajax-wizard"
                                            name="video_category_id"
                                            id="video_category_id"
                                            data-model="{{base64_encode(App\Models\VideoCategory::class)}}"
                                            data-label-fields="{title}"
                                            data-depend-on="institute_id"
                                            @if($edit && $video->videoCategory)
                                            data-preselected-option="{{json_encode(['text' =>  $video->videoCategory->title, 'id' =>  $video->videoCategory->id])}}"
                                            @endif
                                            data-placeholder="{{__('admin.videos.video_category')  }}"
                                    >
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="video_type">{{__('admin.videos.video_type')  }}<span
                                            class="required">*</span> :</label>
                                    <div class="custom-control custom-radio">
                                        <input class="custom-control-input" type="radio" id="video_type_youtube_video"
                                               name="video_type"
                                               value="{{ \App\Models\Video::VIDEO_TYPE_YOUTUBE_VIDEO }}"
                                            {{ ($edit && $video->video_type == \App\Models\Video::VIDEO_TYPE_YOUTUBE_VIDEO) || old('video_type') == \App\Models\Video::VIDEO_TYPE_YOUTUBE_VIDEO ? 'checked' : '' }}>
                                        <label for="video_type_youtube_video" class="custom-control-label">{{__('admin.videos.youtube')  }}
                                            Video</label>
                                    </div>

                                    <div class="custom-control custom-radio">
                                        <input class="custom-control-input" type="radio" id="video_type_uploaded_video"
                                               name="video_type"
                                               value="{{ \App\Models\Video::VIDEO_TYPE_UPLOADED_VIDEO }}"
                                            {{ ($edit && $video->video_type == \App\Models\Video::VIDEO_TYPE_UPLOADED_VIDEO) || old('video_type') == \App\Models\Video::VIDEO_TYPE_UPLOADED_VIDEO ? 'checked' : '' }}>
                                        <label for="video_type_uploaded_video" class="custom-control-label">{{__('admin.videos.upload')  }}
                                            Video</label>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6" style="display: none;">
                                <div class="form-group">
                                    <label for="youtube_video_url">{{__('admin.videos.youtube_video_url')  }} <span
                                            class="required">*</span></label>
                                    <input type="text"
                                           class="form-control"
                                           name="youtube_video_url"
                                           id="youtube_video_url"
                                           placeholder="Youtube Video URL"
                                           value="{{ $edit ? $video->youtube_video_url : old('youtube_video_url') }}">
                                </div>
                            </div>

                            <div class="col-md-6" style="display: none;">
                                <div class="form-group">
                                    <label for="uploaded_video_path">{{__('admin.videos.upload_video')  }} <span
                                            class="required">*</span></label>
                                    <input type="file"
                                           class="form-control"
                                           name="uploaded_video_path"
                                           id="uploaded_video_path"
                                           data-value="{{ $edit ? $video->uploaded_video_path : old('uploaded_video_path') }}"
                                    >
                                </div>
                                <p class="font-italic text-secondary m-0 p-0" id="video-msg-display">
                                    (Video file type must be video/avi,video/mpeg,video/quicktime,video/mp4 and max size 2Mb)
                                </p>
                            </div>

                            @if($edit)
                                <div class="col-md-6">
                                    <label>{{__('admin.videos.video_content')  }}</label>
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
                            @endif

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="name">{{__('admin.videos.description')  }}</label>
                                    <textarea class="form-control" id="description"
                                              name="description"
                                              rows="3"
                                              placeholder="{{__('admin.videos.description')  }}">{{ $edit ? $video->description : old('description') }}</textarea>
                                </div>
                            </div>

                            @if($edit)
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="row_status">{{__('admin.common.status')  }}</label>
                                        <div class="custom-control custom-radio">
                                            <input class="custom-control-input" type="radio" id="row_status_active"
                                                   name="row_status"
                                                   value="{{ \App\Models\Video::ROW_STATUS_ACTIVE }}"
                                                {{ ($edit && $video->row_status == \App\Models\Video::ROW_STATUS_ACTIVE) || old('row_status') == \App\Models\Video::ROW_STATUS_ACTIVE ? 'checked' : '' }}>
                                            <label for="row_status_active" class="custom-control-label">{{__('admin.status.active')  }}</label>
                                        </div>

                                        <div class="custom-control custom-radio">
                                            <input class="custom-control-input" type="radio" id="row_status_inactive"
                                                   name="row_status"
                                                   value="{{ \App\Models\Video::ROW_STATUS_INACTIVE }}"
                                                {{ ($edit && $video->row_status == \App\Models\Video::ROW_STATUS_INACTIVE) || old('row_status') == \App\Models\Video::ROW_STATUS_ACTIVE ? 'checked' : '' }}>
                                            <label for="row_status_inactive"
                                                   class="custom-control-label">{{__('admin.status.inactive')  }}</label>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            <div class="col-sm-12 text-right">
                                <button type="submit"
                                        class="btn btn-success">{{ $edit ? __('admin.videos.update') :__('admin.videos.add')  }}</button>
                            </div>
                        </form>
                    </div><!-- /.card-body -->
                    <div class="overlay" style="display: none">
                        <i class="fas fa-2x fa-sync-alt fa-spin"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <x-generic-validation-error-toastr></x-generic-validation-error-toastr>

    <script>
        const EDIT = !!'{{$edit}}';

        const editAddForm = $('.edit-add-form');
        editAddForm.validate({
            errorElement: "em",
            onkeyup: false,
            errorPlacement: function (error, element) {
                error.addClass("help-block");
                element.parents(".form-group").addClass("has-feedback");

                if (element.parents(".form-group").length) {
                    error.insertAfter(element.parents(".form-group").first().children().last());
                } else if (element.hasClass('select2') || element.hasClass('select2-ajax-custom') || element.hasClass('select2-ajax')) {
                    error.insertAfter(element.parents(".form-group").first().find('.select2-container'));
                } else {
                    error.insertAfter(element);
                }
            },
            highlight: function (element, errorClass, validClass) {
                $(element).parents(".form-group").addClass("has-error").removeClass("has-success");
                $(element).closest('.help-block').remove();
            },
            unhighlight: function (element, errorClass, validClass) {
                $(element).parents(".form-group").addClass("has-success").removeClass("has-error");
            },
            rules: {
                title: {
                    required: true,
                },
                video_type: {
                    required: true
                },
                youtube_video_url: {
                    required: function () {
                        return $('input[name = "video_type"]:checked').val() == {!! \App\Models\Video::VIDEO_TYPE_YOUTUBE_VIDEO !!};
                    },
                    pattern: /^(?:https?:\/\/)?(?:www\.)?(?:youtu\.be\/|youtube\.com\/(?:embed\/|v\/|watch\?v=|watch\?.+&v=))((\w|-){11})(?:\S+)?$/
                },
                uploaded_video_path: {
                    required: function () {
                        return !$('input[name="uploaded_video_path"]').data('value') && $('input[name = "video_type"]:checked').val() == {!! \App\Models\Video::VIDEO_TYPE_UPLOADED_VIDEO !!};
                    },
                    accept: "video/*",
                },
                institute_id: {
                    required: true,
                },

                active_status: {
                    required: false,
                },
            },
            messages: {
                youtube_video_id: {
                    pattern: "invalid youtube video url",
                },
                uploaded_video_path: {
                    accept: "Please upload valid video file"
                }
            },
            submitHandler: function (htmlForm) {
                $('.overlay').show();
                htmlForm.submit();
            }
        });

        function readURL(input) {
            if (input.files && input.files[0]) {
                let reader = new FileReader();
                reader.onload = function (e) {

                }
                reader.onprogress = function () {
                    $('.overlay').show();
                }
                reader.onloadend = function () {
                    $('.overlay').hide();
                }
                reader.readAsDataURL(input.files[0]); // convert to base64 string
            }
        }

        $(document).ready(function () {
            let videoType = $('input[name="video_type"]:checked').val();
            if (videoType == {!! \App\Models\Video::VIDEO_TYPE_YOUTUBE_VIDEO !!}) {
                $('#youtube_video_url').parent().parent().show();
                $('#uploaded_video_path').parent().parent().hide();
            }
            if (videoType == {!! \App\Models\Video::VIDEO_TYPE_UPLOADED_VIDEO !!}) {
                $('#uploaded_video_path').parent().parent().show();
                $('#youtube_video_url').parent().parent().hide();
            }

            $('input[name="video_type').on('change', function () {
                if ($(this).val() == {!! \App\Models\Video::VIDEO_TYPE_YOUTUBE_VIDEO !!}) {
                    $('#youtube_video_url').parent().parent().show();
                    $('#uploaded_video_path').parent().parent().hide();
                } else {
                    $('#uploaded_video_path').parent().parent().show();
                    $('#youtube_video_url').parent().parent().hide();
                }
            });

            $('#uploaded_video_path').change(function () {
                readURL(this);
            })
        });
    </script>
@endpush


