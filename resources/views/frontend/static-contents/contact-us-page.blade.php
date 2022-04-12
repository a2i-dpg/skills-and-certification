@php
    $layout = 'master::layouts.front-end';
@endphp
@extends($layout)

@section('title')
{{$siteSettingInfo->site_title}} :: {{__('generic.contact')}}
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card py-3 mb-2">
                    <div class="container">
                        <div class="row">
                            <div class="col-md-6 contact-us-form-area">
                                <div class="contact-us-form">
                                    <div class="contact-us-portlet-body form fix">
                                        <div class="form-body fix">
                                            <div class="contact-us-portlet-title fix">
                                                <div class="text-center">
                                                    <h3 class="green-heading titleContent">
                                                        {{__('generic.contact_us')}} </h3>
                                                </div>
                                                <hr>
                                            </div>
                                            <div class="col-md-12 input_area">
                                                <form action="{{ route('frontend.visitor-feedback.store') }}"
                                                      method="POST"
                                                      class="edit-add-form">
                                                    @csrf
                                                    <div class="form-group row" aria-required="true">
                                                        <label for="receiver" class="col-sm-2 control-label">{{__('generic.to')}}
                                                            <span style="color: red"> * </span></label>
                                                        <div class="col-sm-10 container_name">
                                                            <select {{--required="required"--}} name="receiver"
                                                                    class="form-control map-change"
                                                                    id="receiver">
                                                                <optgroup label="{{__('generic.institute')}}">
                                                                    <option
                                                                        value="{{ !empty($currentInstitute->google_map_src)? $currentInstitute->google_map_src: 'institute_id_'.$currentInstitute->id }}"
                                                                        data-address="{{ !empty($currentInstitute->address)? $currentInstitute->address: 'N/A' }}"
                                                                        data-mobile="{{ !empty($currentInstitute->primary_mobile)? $currentInstitute->primary_mobile: 'N/A' }}"
                                                                    >
                                                                        {{ $currentInstitute->title }}
                                                                    </option>
                                                                </optgroup>
                                                                @if(\App\Models\TrainingCenter::where(['institute_id'=>$currentInstitute->id])->count()>0)
                                                                    <optgroup label="{{__('generic.training_center')}}">
                                                                        @foreach(\App\Models\TrainingCenter::where(['institute_id'=>$currentInstitute->id])->get() as $trainingCenter)
                                                                            <option
                                                                                value="{{ !empty($trainingCenter->google_map_src)?$trainingCenter->google_map_src: 'training_center_id_'.$trainingCenter->id }}"
                                                                                data-address="{{ !empty($trainingCenter->address)? $trainingCenter->address: 'N/A' }}"
                                                                                data-mobile="{{ !empty($trainingCenter->mobile)? $trainingCenter->mobile: 'N/A' }}"
                                                                            >
                                                                                {{ $trainingCenter->title }}
                                                                            </option>
                                                                        @endforeach
                                                                    </optgroup>
                                                                @endif
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <hr>

                                                    <div class="form-group row" aria-required="true">
                                                        <label for="name" class="col-sm-2 control-label">{{__('generic.name')}}
                                                            <span style="color: red"> * </span></label>
                                                        <div class="col-sm-10 container_name">
                                                            <input required="required" maxlength="255" id="name"
                                                                   class="form-control" type="text" name="name"
                                                                   aria-required="true">
                                                            <input type="hidden" name="institute_id" id="institute_id"
                                                                   value="{{$currentInstitute->id}}">
                                                            <input type="hidden" name="form_type"
                                                                   value="{{\App\Models\VisitorFeedback::FORM_TYPE_CONTACT}}">
                                                        </div>
                                                    </div>
                                                    <div class="form-group row" aria-required="true">

                                                        <label for="mobile" class="col-sm-2 control-label">
                                                            {{__('generic.mobile')}}
                                                            <span style="color: red"> * </span></label>
                                                        <div class="col-sm-10">
                                                            <input required="required" maxlength="255" id="mobile"
                                                                   class="form-control" type="text" name="mobile"
                                                                   aria-required="true">
                                                        </div>
                                                    </div>

                                                    <div class="form-group row" aria-required="true">
                                                        <label for="suggestion"
                                                               class="col-sm-2 control-label"> {{__('generic.opinions')}}
                                                            <span style="color: red"> * </span></label>
                                                        <div class="col-sm-10">
                                                        <textarea class="form-control" name="comment" rows="5"
                                                                  required="required" id="comment"
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
                            <div class="col-md-6 contact-us-form-area">
                                <div class="contact-us-form">

                                    <div class="contact-us-portlet-body form fix">
                                        <div class="form-body fix">
                                            <div class="contact-us-portlet-title fix">
                                                <div class="text-center">
                                                    <h3 class="green-heading titleContent">
                                                    {{__('generic.see_our_location_on_map')}} </h3>
                                                </div>
                                                <hr>
                                            </div>

                                            <div class="contact-us-portlet-body form fix">
                                                <div class="">
                                                    <div id="select-map-div">
                                                        <select name="google_map_src" class="form-control map-change"
                                                                id="google_map_src">
                                                            <optgroup label="{{__('generic.institute')}}">
                                                                <option
                                                                    value="{{ !empty($currentInstitute->google_map_src)? $currentInstitute->google_map_src: 'institute_id_'.$currentInstitute->id }}"
                                                                    data-address="{{ !empty($currentInstitute->address)? $currentInstitute->address: 'N/A' }}"
                                                                    data-mobile="{{ !empty($currentInstitute->primary_mobile)? $currentInstitute->primary_mobile: 'N/A' }}"
                                                                >{{ $currentInstitute->title }}
                                                                </option>
                                                            </optgroup>
                                                            @if(\App\Models\TrainingCenter::where(['institute_id'=>$currentInstitute->id])->count()>0)
                                                                <optgroup label="{{__('generic.training_center')}}">
                                                                    @foreach(\App\Models\TrainingCenter::where(['institute_id'=>$currentInstitute->id])->get() as $trainingCenter)
                                                                        <option
                                                                            value="{{ !empty($trainingCenter->google_map_src)?$trainingCenter->google_map_src: 'training_center_id_'.$trainingCenter->id }}"
                                                                            data-address="{{ !empty($trainingCenter->address)? $trainingCenter->address: 'N/A' }}"
                                                                            data-mobile="{{ !empty($trainingCenter->mobile)? $trainingCenter->mobile: 'N/A' }}"
                                                                        >
                                                                            {{ $trainingCenter->title }}
                                                                        </option>
                                                                    @endforeach
                                                                </optgroup>
                                                            @endif
                                                        </select>
                                                        <div class="p-3"
                                                             style="border: 2px solid #ddf1ff;border-radius: 5px;margin-top: 5px;">
                                                            <p class="p-0 m-0">Address: <span
                                                                    id="address-area-id">{{ !empty($currentInstitute->address)? $currentInstitute->address: 'N/A' }}</span>
                                                            </p>
                                                            <p class="p-0 m-0">Mobile: <span
                                                                    id="mobile-area-id">{{ !empty($currentInstitute->primary_mobile)? $currentInstitute->primary_mobile:'N/A' }}</span>
                                                            </p>
                                                        </div>
                                                    </div>

                                                    <div class="mt-2" style="display: block;">
                                                        <div class="">
                                                            <iframe class="google_map_src_iframe" frameborder="0"
                                                                    src="{{ $currentInstitute->google_map_src? $currentInstitute->google_map_src :'' }}"
                                                                    style="height: 242px;border: 2px solid #ddf1ff; border-radius: 5px;"
                                                                    width="100%"></iframe>
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
            </div>
            <div class="col-md-12">
                <div class="contact-us-bottom-area">
                    <aside class="light-bg contact-address">
                        <div class="container">
                            <div class="row">
                                <!-- Addresse-->
                                <div class="col-md-4 contact-box text-center">
                                    <div class="d-inline-flex" style="width: 215px">
                                        <i class="fas fa-map-marked template-contact-icon"></i>
                                        <p style="text-align: justify; color: #869099;">
                                            {{  !empty($currentInstitute->address)?$currentInstitute->address:'' }}
                                        </p>
                                    </div>
                                </div>

                                <!--     Phone Numbers-->
                                <div class="col-md-4 contact-box text-center">
                                    <div class="d-inline-flex" style="width: 215px">
                                        <i class="fas fa-phone-square-alt template-contact-icon"></i>
                                        <p>
                                            @if(!empty($currentInstitute->phone_numbers))
                                                @foreach($currentInstitute->phone_numbers as $phoneNumber)
                                                    <a style="color: #869099;"
                                                       href="tel:{{  $phoneNumber }}"
                                                       onclick="">{{  $phoneNumber }}
                                                    </a><br>
                                                @endforeach
                                            @endif
                                        </p>
                                    </div>
                                </div>

                                <!-- Email Details -->
                                <div class="col-md-4 contact-box text-center">
                                    <div class="d-inline-flex" style="width: 215px">
                                        <i class="fas fa-envelope-open-text template-contact-icon"></i>
                                        <p>
                                            <a class="footer-email" style="color: #869099;"
                                               href="mailto:{{  !empty($currentInstitute->email)?$currentInstitute->email:'' }}">
                                                <span
                                                    style="font-family:'Roboto', sans-serif; font-size: 17px;">
                                                    {{  !empty($currentInstitute->email)?$currentInstitute->email:'' }}
                                                </span>
                                            </a>
                                        </p>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </aside>

                </div>
            </div>
        </div>
    </div>
@endsection

@push('css')
    <style>
        h3.green-heading.titleContent {
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

        .contact-us-form-area {

        }

        .contact-us-form {
            border: 1px solid #f9f9f9;
            padding: 20px;
            box-shadow: 0 0 15px #eee;
            min-height: 100%;
        }

        .contact-us-bottom-area {
            padding: 30px 0;
            background: #F3F6F8;
            box-shadow: 0 0 5px 0 #dddddd;
        }

        .contact-box {
            padding: 10px 20px;
            margin: 0;
        }

        i.template-contact-icon {
            width: 50px;
            float: left;
            margin-right: 15px;
            font-size: 40px;
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

        //contact-us-page map jQuery
        $('.map-change').on('change', function () {
            $("#google_map_src,#receiver").val(this.value);
            let google_map_src = $(this).val();
            if (google_map_src === '') {
                $('.google_map_src_iframe').attr("src", 'null');
                $('.google_map_src_iframe').remove();
            } else {
                $('.google_map_src_iframe').attr("src", google_map_src);
            }


            let address = $(this).find(':selected').attr('data-address');
            $('#address-area-id').html(address);

            let mobile = $(this).find(':selected').attr('data-mobile');
            $('#mobile-area-id').html(mobile);
        })
    </script>

@endpush
