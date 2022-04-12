@php
    $layout = 'master::layouts.front-end';
@endphp
@extends($layout)

@section('title')
{{$siteSettingInfo->site_title}} :: {{__('generic.feedback')}}
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card py-3 mb-2">
                    <div class="container">
                        <div class="row">
                            <div class="col-md-8 mx-auto advice-form">
                                <div class="portlet light">
                                    <div class="advice-portlet-body form fix">
                                        <div class="form-body fix">
                                            <div class="advice-portlet-title fix">
                                                <div class="text-center">
                                                    <h3 class="green-heading title-content">{{ $currentInstitute && $currentInstitute->title ? $currentInstitute->title:'' }}
                                                        {{__('generic.fill_out_form')}}</h3>
                                                </div>
                                                <hr>
                                            </div>
                                            <div class="col-md-12 input_area">

                                                <form action="{{ route('frontend.visitor-feedback.store') }}"
                                                      method="POST" class="edit-add-form">
                                                    @csrf
                                                    <div class="form-group row" aria-required="true">
                                                        <label for="name"
                                                               class="col-sm-2 control-label">{{__('generic.name')}}
                                                            <span style="color: red"> * </span></label>
                                                        <div class="col-sm-10 container_name">
                                                            <input required="required" maxlength="255" id="name"
                                                                   class="form-control" type="text" name="name"
                                                                   aria-required="true">
                                                            <input type="hidden" name="institute_id" id="institute_id"
                                                                   value="{{$currentInstitute && $currentInstitute->id}}">
                                                            <input type="hidden" name="form_type"
                                                                   value="{{\App\Models\VisitorFeedback::FORM_TYPE_FEEDBACK}}">
                                                        </div>
                                                    </div>
                                                    <div class="form-group row" aria-required="true">

                                                        <label for="mobile" class="col-sm-2 control-label">
                                                            {{__('generic.mobile_number')}}
                                                            <span style="color: red"> * </span></label>
                                                        <div class="col-sm-10">
                                                            <input required="required" maxlength="255" id="mobile"
                                                                   class="form-control" type="text" name="mobile"
                                                                   aria-required="true">
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <label for="email" class="col-sm-2 control-label"
                                                        >{{__('generic.email')}} <span
                                                                style="color: red"> * </span></label>
                                                        <div class="col-sm-10 container_email">
                                                            <input maxlength="50" id="email" class="form-control"
                                                                   type="text" name="email" aria-required="true">
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <label for="address"
                                                               class="col-sm-2 control-label">{{__('generic.address')}}</label>
                                                        <div class="col-sm-10">
                                                        <textarea class="form-control" name="address" rows="2"
                                                                  id="address"></textarea>
                                                        </div>
                                                    </div>
                                                    <div class="form-group row" aria-required="true">
                                                        <label for="suggestion"
                                                               class="col-sm-2 control-label">{{__('generic.options')}}
                                                            <span style="color: red"> * </span></label>
                                                        <div class="col-sm-10">
                                                        <textarea class="form-control" name="comment" rows="4"
                                                                  id="comment"
                                                                  aria-required="true"></textarea>
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <div class="col-md-12 text-center">
                                                            <div class="submit">
                                                                <input type="submit" class="btn btn-default btn_save"
                                                                       value="{{__('generic.save')}}">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('css')
    <style>
        h3.green-heading.title-content {
            font-size: 18px !important;
            padding: 15px;
        }

        .green-heading {
            font-weight: bold !important;
            color: #4b77be !important;
            line-height: 24px;
        }

        .btn_save {
            background: #4b77be !important;
            color: #fff !important;
        }

        .btn_save:hover {
            background: #1650ae !important;
        }

        .input_area {
            font-size: 12px;
        }

        .advice-portlet-body {
            padding: 15px 15px;
            border: 1px solid #e1e1e1 !important;
            border-radius: 8px !important;
        }

        .download-area {
            text-align: center;
        }

        .advice-download-area {
            border-left: 1px solid #e1e1e1;
        }

        .advice-form {
            border: 1px solid #f9f9f9;
            padding: 20px;
            box-shadow: 0 0 15px #eee;
            min-height: 100%;
        }
    </style>
@endpush

@push('js')
    <script>
        $.validator.addMethod(
            "nameValidation",
            function (value, element) {
                let regexp = /^[a-zA-Z0-9()[^-_-. ]+$/i;
                let regexp1 = /^[\s-'\u0980-\u09ff)(. _-]{1,255}$/i;
                let re = new RegExp(regexp);
                let re1 = new RegExp(regexp1);
                return this.optional(element) || re.test(value) || re1.test(value);
            },
            "{{__('generic.enter_your_correct_name')}}"
        );

        $.validator.addMethod(
            "mobileValidation",
            function (value, element) {
                let regexp1 = /^(?:\+88|88)?(01[3-9]\d{8})$/i;
                let regexp = /^(?:\+৮৮|৮৮)?(০১[৩-৯][০-৯]{8})$/i;
                let re = new RegExp(regexp);
                let re1 = new RegExp(regexp1);
                return this.optional(element) || re.test(value) || re1.test(value);
            },
            "{{__('generic.enter_your_correct_mobile_no')}}"
        );

        $.validator.addMethod(
            "textEnBnWithoutSpecialChar",
            function (value, element) {
                let en = /^[a-zA-Z0-9 .,?&।\s'\u0980-\u09ff\n\r]*$/i;
                let reEn = new RegExp(en);
                return this.optional(element) || reEn.test(value);
            },
            "textEnBnWithoutSpecialChar is require"
        );

        $.validator.addMethod(
            "addressWithoutSpecialChar",
            function (value, element) {
                let en = /^[a-zA-Z0-9\s'\u0980-\u09ff\n\r ,./।-]*$/i;
                let reEn = new RegExp(en);
                return this.optional(element) || reEn.test(value);
            },
            "addressWithoutSpecialChar is require"
        );

        const editAddForm = $('.edit-add-form');
        editAddForm.validate({
            rules: {
                name: {
                    required: true,
                    nameValidation: true,
                },
                mobile: {
                    required: true,
                    mobileValidation: true,
                },
                email: {
                    required: true,
                    pattern: /^([a-zA-Z0-9_\.\-\+])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/,
                },
                address: {
                    addressWithoutSpecialChar: true,
                },
                comment: {
                    required: true,
                    textEnBnWithoutSpecialChar: true,
                }
            },
            messages: {
                name: {
                    required: "{{__('generic.enter_your_name_here')}}"
                },
                mobile: {
                    required: "{{__('generic.enter_your_mobile_number_here')}}",
                },
                email: {
                    required: "{{__('generic.enter_your_email_here')}}",
                    pattern: "{{__('generic.enter_your_email_correct_here')}}",
                },
                address: {
                    addressWithoutSpecialChar: "{{__('generic.email_validation_message')}}",
                },
                comment: {
                    required: "{{__('generic.write_your_opinion_here')}}",
                    textEnBnWithoutSpecialChar: "{{__('generic.feedback_validation_message')}}"
                }
            },
            submitHandler: function (htmlForm) {
                $('.overlay').show();
                htmlForm.submit();
            }
        });
    </script>
@endpush
