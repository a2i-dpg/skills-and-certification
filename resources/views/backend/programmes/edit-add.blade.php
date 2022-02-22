@php
    $edit = !empty($programme->id);
    /** @var \App\Models\User $authUser */
    $authUser = \App\Helpers\Classes\AuthHelper::getAuthUser();
@endphp
@extends('master::layouts.master')

@section('title')
    {{ $edit? __('admin.programme.edit'): __('admin.programme.add') }}
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header text-primary custom-bg-gradient-info">
                        <h3 class="card-title font-weight-bold">{{ $edit?  __('admin.programme.edit') : __('admin.programme.add') }}</h3>

                        <div class="card-tools">
                            <a href="{{route('admin.programmes.index')}}"
                               class="btn btn-sm btn-outline-primary btn-rounded">
                                <i class="fas fa-backward"></i> {{__('admin.common.back')}}
                            </a>
                        </div>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <form
                            action="{{$edit ? route('admin.programmes.update', $programme->id) : route('admin.programmes.store')}}"
                            method="POST" class="edit-add-form" enctype="multipart/form-data">
                            @csrf
                            @if($edit)
                                @method('put')
                            @endif
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label for="title">{{ __('admin.programme.title') }} <span
                                            style="color: red"> * </span></label>
                                    <input type="text" class="form-control custom-input-box" name="title"
                                           id="title"
                                           value="{{$edit ? $programme->title : old('title')}}"
                                           placeholder="{{ __('admin.programme.title') }}" required>
                                </div>

                                @if($authUser->isUserBelongsToInstitute())
                                    <input type="hidden" id="institute_id" name="institute_id"
                                           value="{{$authUser->institute_id}}">
                                @else
                                    <div class="form-group col-md-6">
                                        <label for="institute_id">{{ __('admin.programme.institute_title') }} <span
                                                style="color: red"> * </span></label>
                                        <select class="form-control select2-ajax-wizard"
                                                name="institute_id"
                                                id="institute_id"
                                                data-model="{{base64_encode(\App\Models\Institute::class)}}"
                                                data-label-fields="{title}"
                                                @if($edit && $programme->institute)
                                                data-preselected-option="{{json_encode(['text' => $programme->institute->title, 'id' => $programme->institute_id])}}"
                                                @endif
                                                data-placeholder="{{ __('admin.programme.institute_title') }}"
                                        >
                                        </select>
                                    </div>
                                @endif

                                <div class="form-group col-md-6">
                                    <label for="code">{{ __('admin.programme.code') }} <span style="color: red"> * </span></label>
                                    <input type="text" class="form-control custom-input-box" name="code"
                                           id="code"
                                           data-code="{{ $edit ? $programme->code : '' }}"
                                           value="{{$edit ? $programme->code : old('code')}}"
                                           placeholder="Code" required>
                                </div>

                                <div class="form-group col-md-12">
                                    <label for="description">{{ __('admin.programme.description') }}</label>
                                    <textarea class="form-control custom-input-box" name="description"
                                              id="description"
                                              placeholder="Description"
                                              style="height: 100px">{{$edit ? $programme->description : old('description')}}</textarea>

                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="logo">{{ __('admin.programme.logo') }}</label>
                                        <div class="input-group">
                                            <div class="programme-logo-upload-section">
                                                <div class="avatar-preview text-center">
                                                    <label for="logo">
                                                        <img class="figure-img"
                                                             src={{ $edit && $programme->logo ? asset('storage/'.$programme->logo) : "https://via.placeholder.com/350x350?text=Programme+logo"}}
                                                                 height="200" width="200"
                                                             alt="Programme logo"/>
                                                        <span class="p-1 bg-gray"
                                                              style="position: relative; right: 0; bottom: 50%; border: 2px solid #afafaf; border-radius: 50%;margin-left: -31px; overflow: hidden">
                                                        <i class="fa fa-pencil-alt text-white"></i>
                                                    </span>
                                                    </label>
                                                    <div class="imgRemove" style="display: none">
                                                    </div>
                                                </div>
                                                <input type="file" name="logo" style="display: none"
                                                       id="logo">
                                            </div>
                                        </div>
                                        <p class="font-italic text-secondary m-0 p-0">(Image size must be 80x80, file type
                                            must be jpg,bmp,png,jpeg or svg)</p>

                                    </div>
                                </div>
                                <div class="col-sm-12 text-right">
                                    <button type="submit"
                                            class="btn btn-success">{{ $edit ? __('admin.programme.update') : __('admin.programme.add') }}</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('utils.delete-confirm-modal')

@endsection

@push('css')
    <style>
        .imgRemove {
            position: absolute;
            bottom: 172px;
            left: 32%;
            border: none;
            font-size: 30px;
            outline: none;
        }

        .imgRemove::after {
            content: ' \21BA';
            color: #fff;
            font-weight: 900;
            border-radius: 8px;
            cursor: pointer;
        }
    </style>
@endpush
@push('js')
    <x-generic-validation-error-toastr></x-generic-validation-error-toastr>
    <script>
        const EDIT = !!'{{$edit}}';

        $.validator.addMethod(
            "logoSize",
            function (value, element) {
                let isHeightMatched = $('.avatar-preview').find('img')[0].naturalHeight === 80;
                let isWidthMatched = $('.avatar-preview').find('img')[0].naturalWidth === 80;
                return this.optional(element) || (isHeightMatched && isWidthMatched);
            },
            "Invalid picture size. Size must be  80x80",
        );

        const editAddForm = $('.edit-add-form');
        editAddForm.validate({
            rules: {
                title: {
                    required: true,
                },
                institute_id: {
                    required: true
                },
                code: {
                    required: true,
                    remote: {
                        param: {
                            type: "post",
                            url: "{{ route('admin.check-programme-code') }}",
                        },
                        depends: function (element) {
                            return !EDIT && $(element).val() !== $('#code').attr('data-code');
                        },
                    },
                },
                logo: {
                    required: false,
                    accept: "image/*",
                    logoSize: true,
                },
            },
            messages: {
                code: {
                    required: "This field is required",
                    remote: "Code already in use!",
                },
                logo: {
                    accept: "Please upload valid image file only"
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
            $('#logo').change(function () {
                readURL(this); //preview image

                setTimeout(function () {
                    editAddForm.validate().element('#logo');
                }, 200);

                $('.imgRemove').css('display', 'block');
            });

            $('.imgRemove').on('click', function () {
                $('#logo').parent().find('.avatar-preview img').attr('src', "https://via.placeholder.com/350x350?text=Programme+logo");
                $('#logo').val("").valid();
                $(this).css('display', 'none');
            })
        })
    </script>
@endpush


