@php
    $edit = !empty($gallery->id);
    /** @var \App\Models\User $authUser */
    $authUser = \App\Helpers\Classes\AuthHelper::getAuthUser();

@endphp

@extends('master::layouts.master')

@section('title')
    {{ ! $edit ? __('admin.gallery.edit') : __('admin.gallery.update')}}
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card card-outline">
                    <div class="card-header text-primary custom-bg-gradient-info">
                        <h3 class="card-title font-weight-bold">{{ ! $edit ? __('admin.gallery.edit') : __('admin.gallery.update') }}</h3>

                        <div class="card-tools">
                            <a href="{{route('admin.galleries.index')}}"
                               class="btn btn-sm btn-outline-primary btn-rounded">
                                <i class="fas fa-backward"></i> {{__('admin.common.back')}}
                            </a>
                        </div>

                    </div>


                    <div class="card-body">
                        <form class="row edit-add-form" method="post" enctype="multipart/form-data"
                              action="{{ $edit ? route('admin.galleries.update',$gallery->id) : route('admin.galleries.store')}}">
                            @csrf
                            @if($edit)
                                @method('put')
                            @endif
                            {{--<input type="hidden" name="id" id="id" value="{{$edit ? $gallery->id : ''}}">--}}

                            <div class="form-group col-md-6">
                                <label for="gallery_category_id">{{__('admin.gallery.album')}} <span
                                        style="color: red"> * </span></label>
                                <select class="form-control select2-ajax-wizard"
                                        name="gallery_category_id"
                                        id="gallery_category_id"
                                        data-model="{{base64_encode(App\Models\GalleryCategory::class)}}"
                                        data-label-fields="{title}"
                                        @if($edit && $gallery->gallery_category_id)
                                        data-preselected-option="{{json_encode(['text' => $gallery->galleryCategory->title, 'id' => $gallery->gallery_category_id])}}"
                                        @endif
                                        data-placeholder="{{__('admin.gallery.album')}} "
                                >
                                </select>
                            </div>

                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="content_title">{{__('admin.gallery.caption')}} <span
                                            style="color: red"> * </span></label>
                                    <input type="text" class="form-control" id="content_title"
                                           name="content_title"
                                           value="{{ $edit ? $gallery->content_title : old('content_title') }}"
                                           placeholder="{{__('admin.gallery.caption')}}">
                                </div>
                            </div>


                            @if($authUser->isUserBelongsToInstitute())
                                <input type="hidden" id="institute_id" name="institute_id"
                                       value="{{$authUser->institute_id}}">
                            @else
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label for="institute_id">{{__('admin.gallery.institute_title')}}<span
                                                style="color: red"> * </span></label>
                                        <select name="institute_id" id="institute_id" class="form-control select2"
                                                data-placeholder="{{__('admin.gallery.institute_title')}}">
                                            <option></option>
                                            @foreach($institutes as $institute)
                                                <option
                                                    value="{{ $institute->id }}" {{($edit && $gallery->institute_id == $institute->id ) || old('institute_id') == $institute->id ? 'selected' : ''}}>
                                                    {{ $institute->title }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            @endif

                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label
                                        for="publish_date">{{__('admin.gallery.publish_date')}} <span
                                            style="color: red"> * </span></label>
                                    <input type="hidden" id="today">
                                    <input type="text"
                                           class="flat-datetime form-control publish_date flat-date-custom-bg"
                                           name="publish_date"
                                           id="publish_date"
                                           value="{{$edit ? $gallery->publish_date : old('publish_date')}}">
                                </div>
                            </div>

                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label
                                        for="archive_date">{{__('admin.gallery.archive_date')}} <span
                                            style="color: red"> * </span></label>
                                    <input type="text"
                                           class="flat-datetime form-control flat-date-custom-bg"
                                           name="archive_date"
                                           id="archive_date"
                                           value="{{$edit ? $gallery->archive_date : old('archive_date')}}">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="content_type">{{__('admin.gallery.content_type')}}<span
                                            class="required">*</span> :</label>
                                    @foreach(\App\Models\Gallery::CONTENT_TYPES as $key => $type)
                                        <div class="custom-control custom-radio">
                                            <input class="custom-control-input" type="radio" id="{{$type}}"
                                                   name="content_type"
                                                   value="{{$key}}"
                                                {{ ($edit && $gallery->content_type == $key) || old('content_type') == $key  ? 'checked' : '' }}>
                                            <label for="{{$type}}" class="custom-control-label">{{$type}}</label>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            <div class="col-sm-6" id="video_type"
                                 style="display:{{($edit && \App\Models\Gallery::CONTENT_TYPE_VIDEO == $gallery->content_type) || old('content_type') ==\App\Models\Gallery::CONTENT_TYPE_VIDEO  ? 'block' : 'none' }}">
                                <div class="form-group">
                                    <label for="is_youtube_video">{{__('admin.gallery.video_type')}}<span
                                            style="color: red"> * </span></label>
                                    <div class="custom-control custom-radio">
                                        <input class="custom-control-input" type="radio" id="is_youtube_video_no"
                                               name="is_youtube_video"
                                               value="0"
                                            {{ ($edit && !$gallery->is_youtube_video) || (old('is_youtube_video') != null && !old('is_youtube_video'))  ? 'checked' : '' }}>
                                        <label for="is_youtube_video_no" class="custom-control-label">{{__('admin.gallery.custom')}}</label>
                                    </div>
                                    <div class="custom-control custom-radio ">
                                        <input class="custom-control-input" type="radio"
                                               name="is_youtube_video"
                                               id="is_youtube_video_yes"
                                               value="1"
                                            {{ ($edit && $gallery->is_youtube_video) || old('is_youtube_video')  ? 'checked' : '' }}>
                                        <label for="is_youtube_video_yes" class="custom-control-label col-4">{{__('admin.gallery.youtube')}}
                                            </label>
                                    </div>

                                </div>

                            </div>

                            <div class="col-sm-6" id="youtube_id"
                                 style="display:{{($edit && $gallery->is_youtube_video) || old('is_youtube_video')  ? 'block' : 'none' }}">
                                <div class="form-group">
                                    <label for="you_tube_video_id">{{__('admin.gallery.youtube_link')}}<span
                                            style="color: red"> * </span></label>
                                    <input type="text" class="form-control" id="you_tube_video_id"
                                           name="you_tube_video_id"
                                           value="{{ $edit && $gallery->is_youtube_video ? $gallery->content_path : old('you_tube_video_id') }}"
                                           placeholder="{{__('admin.gallery.youtube_link')}}">
                                </div>
                            </div>

                            <div class="form-group col-md-6" id="file_upload"
                                 style="display:{{ (($edit && (\App\Models\Gallery::CONTENT_TYPE_IMAGE == $gallery->content_type || $gallery->is_youtube_vide)) || ((old('is_youtube_video')!=null && !old('is_youtube_video'))|| old('content_type') == \App\Models\Gallery::CONTENT_TYPE_IMAGE) || ($edit && ($gallery->is_youtube_vide == 0 && $gallery->content_type == \App\Models\Gallery::CONTENT_TYPE_VIDEO)) ) ? 'block' : 'none' }}">
                                <label for="content_path">{{__('admin.gallery.gallery_content')}} <span style="color: red"> * </span></label>
                                <input type="file" class="form-control custom-input-box" name="content_path"
                                       id="content_path"
                                       value="{{$edit ? $gallery->content_path : old('content_path')}}">
                                <p class="font-italic text-secondary m-0 p-0" id="photo-info-display">(Image file type must be jpg,jpeg,bmp or png)</p>
                                <p class="font-italic text-secondary m-0 p-0" id="video-info-display">(Video file type must be video/avi,video/mpeg,video/quicktime,video/mp4 and max size 2Mb)</p>
                            </div>

                            @if($edit)
                                @if( \App\Models\Gallery::CONTENT_TYPE_IMAGE == $gallery->content_type)
                                    <div class="col-md-6 custom-view-box">
                                        <p class="label-text">{{__('admin.gallery.content')}}</p>

                                        <div class="card-header d-flex justify-content-between custom-bg-gradient-info">
                                            <img src="{{asset('storage/' .$gallery->content_path)}}" class="img-fluid"
                                                 alt="Responsive image" style="height: 300px; width: 100%">
                                        </div>
                                    </div>
                                @elseif(\App\Models\Gallery::CONTENT_TYPE_VIDEO == $gallery->content_type && $gallery->is_youtube_video)
                                    <div class="col-md-6 custom-view-box">
                                        <p class="label-text">{{__('admin.gallery.video_content')}}</p>
                                        <iframe width="100%" height="100%"
                                                src={{"https://www.youtube.com/embed/".$gallery->you_tube_video_id}}>
                                        </iframe>
                                    </div>

                                @elseif(\App\Models\Gallery::CONTENT_TYPE_VIDEO == $gallery->content_type)
                                    <div class="col-md-6 custom-view-box">
                                        <p class="label-text">{{__('admin.gallery.content')}}</p>
                                        <iframe width="100%" height="100%"
                                                src="{{asset('storage/' .$gallery->content_path)}}">
                                        </iframe>
                                    </div>
                                @endif
                            @endif


                            <div class="col-sm-12 text-right">
                                <button type="submit"
                                        class="btn btn-success">{{ $edit ? __('admin.gallery.update') :__('admin.gallery.add') }}</button>
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

@push('css')
    <style>
        .flat-date-custom-bg {
            background-color: #fafdff !important;
        }
    </style>
@endpush

@push('js')
    <x-generic-validation-error-toastr></x-generic-validation-error-toastr>
    <script>
        const EDIT = !!'{{$edit}}';
        const content_types = @json(\App\Models\Gallery::CONTENT_TYPES);

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
                gallery_category_id: {
                    required: true,
                },
                content_title: {
                    required: true,
                },
                institute_id: {
                    required: true,
                },
                content_type: {
                    required: true,
                },
                content_path: {
                    required: function () {
                        if ((!$('input[name="is_youtube_video"]:checked').val() || $('input[name="content_type"]:checked').val() == {{\App\Models\Gallery::CONTENT_TYPE_IMAGE}} || $('#content_path').val() == '') && !EDIT) {
                            return true
                        } else {
                            return false
                        }
                    },
                    accept: function () {
                        if ($('input[name="content_type"]:checked').val() == {{\App\Models\Gallery::CONTENT_TYPE_IMAGE}}) {
                            return "image/*";
                        } else {
                            if ($('input[name="is_youtube_video"]:checked').val()) {
                                return "video/*";
                            }
                        }
                    }
                },
                is_youtube_video: {
                    required: function () {
                        return ($('input[name="content_type"]:checked').val() == {{\App\Models\Gallery::CONTENT_TYPE_VIDEO}})
                    },
                },
                you_tube_video_id: {
                    required: function () {
                        return ($('input[name="content_type"]:checked').val() == {{\App\Models\Gallery::CONTENT_TYPE_VIDEO}} && $('input[name="is_youtube_video"]:checked').val() && !EDIT);
                    },
                    pattern: /^(?:https?:\/\/)?(?:www\.)?(?:youtu\.be\/|youtube\.com\/(?:embed\/|v\/|watch\?v=|watch\?.+&v=))((\w|-){11})(?:\S+)?$/
                },
                publish_date: {
                    required: true,
                    greaterThan: function () {
                        if (!EDIT) {
                            return '#today';
                        }
                    }
                },
                archive_date: {
                    required: true,
                    greaterThan: ".publish_date"
                },
            },
            messages: {
                content_path: {
                    accept: function () {
                        if ($('input[name="content_type"]:checked').val() == {{\App\Models\Gallery::CONTENT_TYPE_IMAGE}}) {
                            return "Please upload valid Image";
                        } else {
                            if ($('input[name="is_youtube_video"]:checked').val()) {
                                return "Please upload valid video";
                            }
                        }
                    }
                },
                publish_date: {
                    greaterThan: 'Publish Date will not be less than today',
                },

                archive_date: {
                    greaterThan: "Please select Archive Date greater than Publish Date"
                },

            },
            submitHandler: function (htmlForm) {
                $('.overlay').show();
                htmlForm.submit();
            }
        });


        $(document).ready(function () {
            const videoFieldDisplay = (id) => {
                if (!id) {
                    $("#youtube_id").css('display', 'none');
                    $("#file_upload").css('display', 'block');
                } else {
                    $("#youtube_id").css('display', 'block');
                    $("#file_upload").css('display', 'none');
                }
            }

            $('input[name="content_type').on('change', function () {
                let content_type = this.value;
                $("#youtube_id").css('display', 'none');

                if ({{\App\Models\Gallery::CONTENT_TYPE_VIDEO}} == content_type
                ) {
                    $("#video_type").css('display', 'block');
                    $("#file_upload").css('display', 'none');
                    if ($("#is_youtube_video").val()) {
                        videoFieldDisplay(parseInt($('input[name="is_youtube_video').val()));
                    }

                } else {
                    $("#video_type").css('display', 'none');
                    $("#file_upload").css('display', 'block');
                }
            });

            $('input[name="is_youtube_video').on('change', function () {
                videoFieldDisplay(parseInt(this.value));
            });
        });

        let today = new Date();
        today = today.getFullYear() + '-' + ("0" + (today.getMonth() + 1)).slice(-2) + '-' + ("0" + (today.getDate() - 1)).slice(-2);
        console.log('Today: ' + today)
        $('#today').val(today + " 12:00");

        $('#institute_id').change(function () {
            if ($(this).val() != "") {
                $(this).valid();
            }
        });
        $('.publish_date').change(function () {
            if ($(this).val() != "") {
                $(this).valid();
            }
        });

        $('#archive_date').change(function () {
            if ($(this).val() != "") {
                $(this).valid();
            }
        });

        $('#is_youtube_video_yes').on('click', function () {
            $('#content_path').prop("disabled", true);
            $('#you_tube_video_id').prop("disabled", false);
        })

        $('#is_youtube_video_no').on('click', function () {
            $('#content_path').prop("disabled", false);
            $('#you_tube_video_id').prop("disabled", true);


            $('#video-info-display').show();
            $('#photo-info-display').hide();
        });

        $('#Image').on('click', function () {
            $('#video-info-display').hide();
            $('#photo-info-display').show();
        });


    </script>
@endpush


