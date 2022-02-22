@php
    $edit = !empty($introVideo->id);
    /** @var \App\Models\User $authUser */
    $authUser = \App\Helpers\Classes\AuthHelper::getAuthUser();
@endphp

@extends('master::layouts.master')

@section('title')
    {{ ! $edit ? __('admin.intro-video.add') : __('admin.intro-video.update') }}
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card card-outline">
                    <div class="card-header  custom-bg-gradient-info">
                        <h3 class="card-title text-primary font-weight-bold">{{ ! $edit ? __('admin.intro-video.add') : __('admin.intro-video.update') }}</h3>

                        <div class="card-tools">
                            <a href="{{route('admin.intro-videos.index')}}"
                               class="btn btn-sm btn-outline-primary btn-rounded">
                                <i class="fas fa-backward"></i> {{__('admin.common.back')}}
                            </a>
                        </div>
                    </div>

                    <div class="card-body">
                        <form class="row edit-add-form" method="post" enctype="multipart/form-data"
                              action="{{ $edit ? route('admin.intro-videos.update', [$introVideo->id]) : route('admin.intro-videos.store')}}">
                            @csrf
                            @if($edit)
                                @method('put')
                            @endif

                            @if($authUser->isUserBelongsToInstitute())
                                <input type="hidden" id="institute_id" name="institute_id"
                                       value="{{$authUser->institute_id}}">
                            @else
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label for="institute_id">{{ __('admin.intro-video.institute_title') }}<span
                                                class="required"> * </span></label>
                                        <select class="form-control select2-ajax-wizard"
                                                name="institute_id"
                                                id="institute_id"
                                                data-model="{{base64_encode(App\Models\Institute::class)}}"
                                                data-label-fields="{title}"
                                                data-dependent-fields="#video_category_id"
                                                @if($edit && !empty($introVideo->institute))
                                                data-preselected-option="{{json_encode(['text' =>  $introVideo->institute->title, 'id' =>  $introVideo->institute->id])}}"
                                                @endif
                                                data-placeholder="{{ __('generic.select_placeholder') }}"
                                        >
                                        </select>
                                    </div>
                                </div>
                            @endif


                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="youtube_video_url">{{ __('admin.intro-video.youtube_video_url') }} <span
                                            class="required">*</span></label>
                                    <input type="text"
                                           class="form-control"
                                           name="youtube_video_url"
                                           id="youtube_video_url"
                                           placeholder="Youtube Video URL"
                                           value="{{ $edit ? $introVideo->youtube_video_url : old('youtube_video_url') }}">
                                </div>
                            </div>

                            <div class="col-sm-12 text-right">
                                <button type="submit"
                                        class="btn btn-success">{{ $edit ? __('admin.intro-video.update')  : __('admin.intro-video.add') }}</button>
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
                youtube_video_url: {
                    required: true,
                    pattern: /^(?:https?:\/\/)?(?:www\.)?(?:youtu\.be\/|youtube\.com\/(?:embed\/|v\/|watch\?v=|watch\?.+&v=))((\w|-){11})(?:\S+)?$/
                },
                uploaded_video_path: {
                    required: function () {
                        return !$('input[name="uploaded_video_path"]').data('value') && $('input[name = "video_type"]:checked').val() == {!! \App\Models\Video::VIDEO_TYPE_UPLOADED_VIDEO !!};
                    },
                    accept: "video/*",
                },
                institute_id: {
                    required: false,
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


