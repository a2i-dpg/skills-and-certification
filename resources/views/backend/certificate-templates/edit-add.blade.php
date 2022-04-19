@php
    $edit = !empty($certificateTemplate->id);
    /** @var \App\Models\User $authUser */
    $authUser = \App\Helpers\Classes\AuthHelper::getAuthUser();


@endphp

@extends('master::layouts.master')

@section('title')
    {{ ! $edit ? __('admin.certificate-template.edit') : __('admin.certificate-template.update') }}
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card card-outline">
                    <div class="card-header text-primary custom-bg-gradient-info">
                        <h3 class="card-title font-weight-bold">{{ ! $edit ? __('admin.certificate-template.add') : __('admin.certificate-template.update') }}</h3>

                        <div class="card-tools">
                            <a href="{{route('admin.certificate-templates.index')}}"
                               class="btn btn-sm btn-outline-primary btn-rounded">
                                <i class="fas fa-backward"></i> {{__('admin.common.back')}}
                            </a>
                        </div>

                    </div>


                    <div class="card-body">
                        <form class="row edit-add-form" method="post" enctype="multipart/form-data"
                              action="{{ $edit ? route('admin.certificate-templates.update', [$certificateTemplate->id]) : route('admin.certificate-templates.store')}}">
                            @csrf
                            @if($edit)
                                @method('put')
                            @endif

                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="content_title">{{ __('admin.certificate-template.title') }}<span
                                            style="color: red"> * </span></label>
                                    <input type="text" class="form-control" id="title"
                                           name="title"
                                           value="{{ $edit ? $certificateTemplate->title : old('title') }}"
                                           placeholder="{{ __('admin.certificate-template.title') }}">
                                </div>
                            </div>

                            @if($authUser->isUserBelongsToInstitute())
                                <input type="hidden" id="institute_id" name="institute_id"
                                       value="{{$authUser->institute_id}}">
                            @else
                                <div class="form-group col-md-6">
                                    <label for="institute_id">{{ __('admin.certificate-template.institute_title') }} <span
                                            style="color: red"> * </span></label>
                                    <select class="form-control select2-ajax-wizard"
                                            name="institute_id"
                                            id="institute_id"
                                            data-model="{{base64_encode(\App\Models\Institute::class)}}"
                                            data-label-fields="{title}"
                                            data-dependent-fields="#programme_id|#batch_id"
                                            @if($edit && $certificateTemplate->institute_id)
                                            data-preselected-option="{{json_encode(['text' => $certificateTemplate->institute->title, 'id' => $certificateTemplate->institute_id])}}"
                                            @endif
                                            data-placeholder="{{ __('admin.certificate-template.institute_title') }}"
                                    >
                                    </select>
                                </div>
                            @endif

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="image">{{ __('admin.certificate-template.template') }}</label>
                                    <div class="input-group">
                                        <div class="programme-image-upload-section">
                                            <div class="avatar-preview text-center">
                                                <label for="image">
                                                    <img class="figure-img"
                                                         src={{ $edit && $certificateTemplate->image ? asset('storage/'.$certificateTemplate->image) : "https://via.placeholder.com/350x350?text=Photo+Album"}}
                                                             height="300" width="500"
                                                         alt="Photo Album"/>
                                                    <span class="p-1 bg-gray"
                                                          style="position: relative; right: 0; bottom: 50%; border: 2px solid #afafaf; border-radius: 50%;margin-left: -31px; overflow: hidden">
                                                        <i class="fa fa-pencil-alt text-white"></i>
                                                    </span>
                                                </label>
                                                <div class="imgRemove" style="display: none">
                                                </div>
                                            </div>
                                            <input type="file" name="image" style="display: none"
                                                   id="image">
                                        </div>
                                    </div>
                                    <p class="font-italic text-secondary">
                                        (Image max 2Mb & file type must be jpg,bmp,png,jpeg or svg)
                                    </p>
                                </div>
                            </div>

                            {{-- @if($edit)
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="row_status">{{ __('admin.common.status') }}</label>
                                        <div class="custom-control custom-radio">
                                            <input class="custom-control-input" type="radio" id="row_status_active"
                                                   name="row_status"
                                                   value="{{ \App\Models\certificateTemplate::ROW_STATUS_ACTIVE }}"
                                                {{ ($edit && $certificateTemplate->row_status == \App\Models\certificateTemplate::ROW_STATUS_ACTIVE) || old('row_status') == \App\Models\certificateTemplate::ROW_STATUS_ACTIVE ? 'checked' : '' }}>
                                            <label for="row_status_active" class="custom-control-label">{{ __('admin.status.active') }}</label>
                                        </div>

                                        <div class="custom-control custom-radio">
                                            <input class="custom-control-input" type="radio" id="row_status_inactive"
                                                   name="row_status"
                                                   value="{{ \App\Models\certificateTemplate::ROW_STATUS_INACTIVE }}"
                                                {{ ($edit && $certificateTemplate->row_status == \App\Models\certificateTemplate::ROW_STATUS_INACTIVE) || old('row_status') == \App\Models\certificateTemplate::ROW_STATUS_INACTIVE ? 'checked' : '' }}>
                                            <label for="row_status_inactive"
                                                   class="custom-control-label">{{ __('admin.status.inactive') }}</label>
                                        </div>
                                    </div>
                                </div>
                            @endif --}}


                            <div class="col-sm-12 text-right">
                                <button type="submit"
                                        class="btn btn-success">{{ $edit ? __('admin.certificate-template.update') : __('admin.certificate-template.add') }}</button>
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
        const content_types = @json(\App\Models\Gallery::CONTENT_TYPES);

        // $.validator.addMethod(
        //     "coverImageSize",
        //     function (value, element) {
        //         let isHeightMatched = $('.avatar-preview').find('img')[0].naturalHeight === 1080;
        //         let isWidthMatched = $('.avatar-preview').find('img')[0].naturalWidth === 1920;
        //         return this.optional(element) || (isHeightMatched && isWidthMatched);
        //     },
        //     "Invalid picture size. Size must be  1920x1080",
        // );
        $.validator.addMethod('filesize', function(value, element, param) {
            return this.optional(element) || (element.files[0].size <= param)
        }, 'Please upload maximum 2Mb Image size');

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
                institute_id: {
                    required: true,
                },
                image: {
                    accept: "image/*",
                    extension: "jpg|bmp|png|jpeg|svg",
                    filesize:1000*2048,
                }

            },
            messages: {
                title: {
                    pattern: "This field is required",
                },
                image:{
                    accept:"Please upload valid image",
                    extension:"Please upload valid image extension",
                    filesize:"File size must be less than 500Kb"
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
                    $(input).parent().find('.avatar-preview img').attr('src', e.target.result);
                }
                reader.readAsDataURL(input.files[0]); // convert to base64 string
            }
        }

        $(document).ready(function () {
            $('#image').change(function () {
                readURL(this); //preview image

                setTimeout(function () {
                    editAddForm.validate().element('#image');
                }, 200);

                $('.imgRemove').css('display', 'block');
            });

            $('.imgRemove').on('click', function () {
                $('#image').parent().find('.avatar-preview img').attr('src', "https://via.placeholder.com/350x350?text=Photo+Album");
                $('#image').val("").valid();
                $(this).css('display', 'none');
            })
        })
    </script>
@endpush


