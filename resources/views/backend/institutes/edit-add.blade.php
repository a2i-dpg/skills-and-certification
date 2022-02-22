@php
    $edit = !empty($institute->id);
@endphp

@extends('master::layouts.master')

@section('title')
    {{ ! $edit ? __('admin.institute.add') : __('admin.institute.update') }}
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card card-outline">
                    <div class="card-header text-primary custom-bg-gradient-info">
                        <h3 class="card-title font-weight-bold text-primary">{{ ! $edit ? __('admin.institute.add') : __('admin.institute.update') }}</h3>

                        <div class="card-tools">
                            @can('viewAny', \App\Models\Institute::class)
                                <a href="{{route('admin.institutes.index')}}"
                                   class="btn btn-sm btn-outline-primary btn-rounded">
                                    <i class="fas fa-backward"></i>{{__('admin.common.back')}}
                                </a>
                            @endcan
                        </div>
                    </div>

                    <div class="card-body">
                        <form class="row edit-add-form" method="post"
                              enctype="multipart/form-data"
                              action="{{ $edit ? route('admin.institutes.update', [$institute->id]) : route('admin.institutes.store')}}">
                            @csrf
                            @if($edit)
                                @method('put')
                            @endif
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="title">{{ __('admin.institute.title') }}<span
                                            style="color: red"> * </span></label>
                                    <input type="text" class="form-control" id="title"
                                           name="title"
                                           value="{{ $edit ? $institute->title : old('title') }}"
                                           placeholder="{{ __('admin.institute.title') }}">
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="email">{{ __('generic.email') }}<span
                                            style="color: red"> * </span></label>
                                    <input type="text" class="form-control" id="email"
                                           name="email"
                                           data-code="{{ $edit ? $institute->email : '' }}"
                                           value="{{ $edit ? $institute->email : old('email') }}"
                                           placeholder="{{ __('generic.email') }}">
                                </div>
                            </div>

                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="mobile">{{ __('generic.mobile') }}<span
                                            style="color: red"> * </span></label>
                                    <input type="text" class="form-control" id="mobile"
                                           name="mobile"
                                           value="{{ $edit ? $institute->mobile : old('mobile') }}"
                                           placeholder="{{ __('generic.mobile') }}">
                                </div>
                            </div>

                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="address">{{ __('generic.address') }}<span
                                            style="color: red"> * </span></label>
                                    <input type="text" class="form-control" id="address"
                                           name="address"
                                           value="{{ $edit ? $institute->address : old('address') }}"
                                           placeholder="{{ __('generic.address') }}">
                                </div>
                            </div>

                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="office_head_name">{{ __('generic.office_head_name') }}<span
                                            style="color: red"> * </span></label>
                                    <input type="text" class="form-control" id="office_head_name"
                                           name="office_head_name"
                                           value="{{ $edit ? $institute->office_head_name : old('office_head_name') }}"
                                           placeholder="{{ __('generic.office_head_name') }}">
                                </div>
                            </div>

                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="office_head_post">{{ __('generic.office_head_post') }}<span
                                            style="color: red"> * </span></label>
                                    <input type="text" class="form-control" id="office_head_post"
                                           name="office_head_post"
                                           value="{{ $edit ? $institute->office_head_post : old('office_head_post') }}"
                                           placeholder="{{ __('generic.office_head_post') }}">
                                </div>
                            </div>

                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="contact_person_name">{{ __('generic.contact_person_name') }}<span
                                            style="color: red"> * </span></label>
                                    <input type="text" class="form-control" id="contact_person_name"
                                           name="contact_person_name"
                                           value="{{ $edit ? $institute->contact_person_name : old('contact_person_name') }}"
                                           placeholder="{{ __('generic.contact_person_name') }}">
                                </div>
                            </div>

                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="contact_person_post">{{ __('generic.contact_person_post') }}<span
                                            style="color: red"> * </span></label>
                                    <input type="text" class="form-control" id="contact_person_post"
                                           name="contact_person_post"
                                           value="{{ $edit ? $institute->contact_person_post : old('contact_person_name') }}"
                                           placeholder="{{ __('generic.contact_person_name') }}">
                                </div>
                            </div>

                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="contact_person_mobile">{{ __('generic.contact_person_mobile') }}<span
                                            style="color: red"> * </span></label>
                                    <input type="text" class="form-control" id="contact_person_mobile"
                                           name="contact_person_mobile"
                                           value="{{ $edit ? $institute->contact_person_mobile : old('contact_person_mobile') }}"
                                           placeholder="{{ __('generic.contact_person_mobile') }}">
                                </div>
                            </div>

                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="contact_person_name">{{ __('generic.contact_person_email') }}<span
                                            style="color: red"> * </span></label>
                                    <input type="text" class="form-control" id="contact_person_email"
                                           name="contact_person_email"
                                           value="{{ $edit ? $institute->contact_person_email : old('contact_person_email') }}"
                                           placeholder="{{ __('generic.contact_person_email') }}">
                                </div>
                            </div>

                            @unless($edit)
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label for="contact_person_password">{{ __('generic.password') }}<span
                                                style="color: red"> * </span></label>
                                        <input type="password" class="form-control"
                                               id="contact_person_password"
                                               name="contact_person_password"
                                               value="{{ old('contact_person_password') }}"
                                               placeholder="{{ __('generic.password') }}">

                                    </div>
                                </div>

                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label
                                            for="contact_person_password_confirmation">{{ __('generic.retype_password') }}
                                            <span
                                                style="color: red"> * </span></label>
                                        <input type="password" class="form-control"
                                               id="contact_person_password_confirmation"
                                               name="contact_person_password_confirmation"
                                               value="{{ old('contact_person_password_confirmation') }}"
                                               placeholder="{{ __('generic.retype_password') }}">

                                    </div>
                                </div>
                            @endunless


                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="google_map_src">{{ __('admin.institute.google_map_src') }}</label>
                                    <textarea class="form-control" id="google_map_src" name="google_map_src"
                                              placeholder="Google Map SRC"
                                              rows="3">{{ $edit ? $institute->google_map_src : old('google_map_src') }}</textarea>
                                </div>
                            </div>

                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="description">{{ __('admin.institute.description') }}</label>
                                    <textarea class="form-control" id="description" name="description"
                                              rows="3">{{ $edit ? $institute->description : old('description') }}</textarea>
                                </div>
                            </div>

                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="logo">{{ __('admin.institute.logo') }} <span
                                            style="color: red"> * </span></label>
                                    <div class="input-group">
                                        <div class="logo-upload-section">
                                            <div class="avatar-preview text-center">
                                                <label for="logo">
                                                    <img class="figure-img"
                                                         src={{ $edit && $institute->logo ? asset('storage/'. $institute->logo) :  "https://via.placeholder.com/350x350?text=Institute+Logo"}}
                                                             width="200" height="200"
                                                         alt="Institute logo"/>
                                                    <span class="p-1 bg-gray"
                                                          style="position: relative; right: 0; bottom: 50%; border: 2px solid #afafaf; border-radius: 50%;margin-left: -31px; overflow: hidden">
                                                        <i class="fa fa-pencil-alt text-white"></i>
                                                    </span>
                                                </label>
                                            </div>
                                            <input type="file" name="logo" style="display: none"
                                                   id="logo">
                                        </div>
                                    </div>
                                    <p class="font-italic text-secondary m-0 p-0">
                                        (Image max 500kb, file type
                                        must be jpeg,jpg,png or gif)</p>
                                </div>
                            </div>

                            <div class="col-sm-12 text-right">
                                <button type="submit"
                                        class="btn btn-success">{{ $edit ? __('admin.institute.update') : __('admin.institute.add') }}</button>
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

        $.validator.addMethod(
            "logoSize",
            function (value, element) {
                let isHeightMatched = $('.avatar-preview').find('img')[0].naturalHeight === 70;
                let isWidthMatched = $('.avatar-preview').find('img')[0].naturalWidth === 370;
                return this.optional(element) || (isHeightMatched && isWidthMatched);
            },
            "Invalid logo size. Size must be 370 x 70",
        );
        $.validator.addMethod('filesize', function (value, element, param) {
            return this.optional(element) || (element.files[0].size <= param)
        }, 'File size must be less than {0} bytes');

        $.validator.addMethod(
            "mobileValidation",
            function (value, element) {
                let regexp1 = /^(?:\+88|88)?(01[3-9]\d{8})$/i;
                let regexp = /^(?:\+৮৮|৮৮)?(০১[৩-৯][০-৯]{8})$/i;
                let re = new RegExp(regexp);
                let re1 = new RegExp(regexp1);
                return this.optional(element) || re.test(value) || re1.test(value);
            },
            "Please enter valid mobile number"
        );

        $.validator.addMethod(
            "phoneValidation",
            function (value, element) {
                let regexp = /^[0-9[+-]+$/i;
                let regexp1 = /^[\s-'\u0980-\u09ff+-]{1,255}$/i;
                let re = new RegExp(regexp);
                let re1 = new RegExp(regexp1);
                return this.optional(element) || re.test(value) || re1.test(value);
            },
            "Please enter valid phone number"
        );

        editAddForm.validate({
            rules: {
                title: {
                    required: true,
                },
                email: {
                    required: true,
                    pattern: /^([a-zA-Z0-9_\.\-\+])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/,
                },
                mobile: {
                    required: true,
                    mobileValidation: true
                },
                address: {
                    required: true,
                },
                office_head_name: {
                    required: true,
                },
                office_head_post: {
                    required: false,
                },
                contact_person_name: {
                    required: true,
                },
                contact_person_email: {
                    required: true,
                    pattern: /^([a-zA-Z0-9_\.\-\+])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/,

                },
                contact_person_mobile: {
                    required: true,
                    mobileValidation: true,
                },
                contact_person_post: {
                    required: true,
                },
                contact_person_password: {
                    required: true,
                },
                contact_person_password_confirmation: {
                    required: true,
                    equalTo: "#contact_person_password"
                },
                logo: {
                    required: !EDIT,
                    accept: 'image/*',
                    filesize: 500000,
                },
            },
            messages: {
                email: {
                    required: "Please enter an email address",
                    pattern: "Please enter valid email address"
                },
                logo: {
                    accept: 'Please upload valid image file',
                    filesize: "Image size max 500Kb",
                },
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
            $('#logo').change(async function () {
                await readURL(this); //preview image
                editAddForm.validate().element("#logo");
            });
        })
    </script>
@endpush


