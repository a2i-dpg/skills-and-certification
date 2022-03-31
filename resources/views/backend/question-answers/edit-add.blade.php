@php
    $edit = !empty($questionAnswer->id);
    /** @var \App\Models\User $authUser */
    $authUser = \App\Helpers\Classes\AuthHelper::getAuthUser();
@endphp

@extends('master::layouts.master')

@section('title')
    {{ ! $edit ? __('admin.question_answer.add') : __('admin.question_answer.update') }}
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card card-outline">
                    <div class="card-header  custom-bg-gradient-info">
                        <h3 class="card-title text-primary font-weight-bold">{{ ! $edit ? __('admin.question_answer.add') : __('admin.question_answer.update') }}</h3>

                        <div class="card-tools">
                            <a href="{{route('admin.question-answers.index')}}"
                               class="btn btn-sm btn-outline-primary btn-rounded">
                                <i class="fas fa-backward"></i> {{__('admin.common.back')}}
                            </a>
                        </div>
                    </div>

                    <div class="card-body">
                        <form class="row edit-add-form" method="post" enctype="multipart/form-data"
                              action="{{ $edit ? route('admin.question-answers.update', [$questionAnswer->id]) : route('admin.question-answers.store')}}">
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
                                        <label for="institute_id">{{ __('admin.question_answer.institute_title1') }}</label>
                                        <select class="form-control select2-ajax-wizard"
                                                name="institute_id"
                                                id="institute_id"
                                                data-model="{{base64_encode(App\Models\Institute::class)}}"
                                                data-label-fields="{title}"
                                                data-dependent-fields="#video_category_id"
                                                @if($edit)
                                                    @if (!empty($questionAnswer->institute->title))
                                                        data-preselected-option="{{json_encode(['text' =>  @$questionAnswer->institute->title, 'id' =>  @$questionAnswer->institute->id])}}"
                                                    @endif
                                                @endif
                                                data-placeholder="{{ __('admin.common.select') }}"
                                        >
                                        </select>
                                    </div>
                                </div>
                            @endif


                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="question">{{ __('admin.question_answer.question') }}: <span
                                            class="required">*</span></label>
                                    <input type="text"
                                           class="form-control"
                                           name="question"
                                           id="question"
                                           placeholder="Question"
                                           value="{{ $edit ? $questionAnswer->question : old('question') }}">
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="answer">{{ __('admin.question_answer.answer') }}: <span
                                            class="required">*</span></label>

                                    <textarea class="form-control"
                                              id="answer"
                                              name="answer"
                                              rows="3">{{ $edit ? $questionAnswer->answer : old('answer') }}</textarea>
                                </div>
                            </div>

                            <div class="col-sm-12 text-right">
                                <button type="submit"
                                        class="btn btn-success">{{ $edit ? __('admin.question_answer.update') : __('admin.question_answer.add') }}</button>
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
            rules: {
                question: {
                    required: true,
                },
                answer: {
                    required: true,
                },
            },
            messages: {
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


