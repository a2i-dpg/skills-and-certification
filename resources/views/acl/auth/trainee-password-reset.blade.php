@extends('master::layouts.front-end')
@section('title', __('generic.access_key_recovery'))


@section('header', '')
@section('footer', '')

@section('full_page_content')

    <div class="bitac-login-area">
        <div class="login-area text-center">
            <div class="row">
                <div class="col-sm-12 pt-5">
                    <h2 class="pt-4 pb-4"
                        style="color: #636f41">Training and Certificate Management</h2>
                </div>
                <div class="col-sm-4 mx-auto">
                    <form class="login-form" action="{{ route('frontend.trainee.recover-access-key') }}" method="post">
                        {{ csrf_field() }}
                        <div class="form-group">
                            <!--ie8, ie9 does not support html5 placeholder, so we just show field title for that-->
                            <label class="control-label visible-ie8 visible-ie9">Email Address</label>
                            <div class="input-icon">
                                <i class="fa fa-envelope"></i>
                                <input class="form-control form-control-solid placeholder-no-fix custom_input_field"
                                       type="text" autocomplete="off"
                                       name="email"
                                       id="email"
                                       placeholder="{{__('generic.enter_email')}}">
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary btn-block submit_btn">{{__('generic.recover_password')}}
                        </button>
                        <div class="col-12 mt-2">
                            <a href="{{route('frontend.trainee.login-form')}}">{{__('generic.login')}}</a>
                        </div>
                        <div class="row pl-3">
                            <div class="col-md-6 col-sm-5 help-desk pt-4">
                                <h4>{{__('generic.help_desk')}}</h4>
                                <p><i class="fa fa-phone"></i>
                                    <a href="tel:{{!empty($currentInstitute->primary_phone)?$currentInstitute->primary_phone:'+88-02-8870680'}}">
                                        {{!empty($currentInstitute->primary_phone)?$currentInstitute->primary_phone:'+88-02-8870680'}}
                                    </a>
                                </p>
                                <p><i class="fa fa-envelope"></i>
                                    <a href="mailto:{{!empty($currentInstitute->email)?$currentInstitute->email:'support@audit.com'}}">
                                        {{!empty($currentInstitute->email)?$currentInstitute->email:'support@audit.com'}}
                                    </a>
                                </p>
                            </div>
                            <div class="col-md-6 col-sm-5 support pt-4">

                            </div>
                            <div class="col-md-12">
                                <h4 class="text-center pt-3"><a href="#" target="_blank" style="color: #693391;">{{__('generic.user_guide')}}</a></h4>
                            </div>
                        </div>
                    </form>


                </div>
            </div>
        </div>
    </div>

    @push('css')
        <style>
            /* Bitac login CSS*/
            .bitac-login-area {
                background: url(http://skills.gov.bd/bitac_cms/img/back_9.png);
                background-repeat: no-repeat;
                background-size: cover;
                background-position: center;
                min-height: 100vh;
                overflow: hidden;
            }

            .login-form {
                /*background: #ebebeb;*/
                padding: 44px;

                background: url(http://skills.gov.bd/bitac_cms/assets/admin/pages/img/bg-white-lock.png);
                background-repeat: no-repeat;
                background-size: cover;
                background-position: center;
            }

            .login-form h4 {
                color: #0000ff;
                font-size: 15px;
                font-weight: bold;
            }

            .help-desk, .support {
                text-align: start;
            }

            .help-desk p, .support p {
                margin: 0;
                font-size: 14px;
            }

            .help-desk p a, .support p a {
                color: #6c6c6c;
            }

            .help-desk p a:hover, .support p a:hover {
                color: #976666;
            }

            .input-icon {
                position: relative;
            }

            .input-icon > i {
                color: #ccc;
                display: block;
                position: absolute;
                margin: 11px 2px 4px 10px;
                z-index: 3;
                width: 16px;
                height: 16px;
                font-size: 16px;
                text-align: center;
            }

            .input-icon > .form-control {
                padding-left: 33px;
            }

            .visible-ie9 {
                /* display: none; */
            }

            .visible-ie8 {
                display: none;
            }

            .submit_btn {
                background: #3379B5;
            }

            .custom_input_field {
                border: 2px solid #e3e7f1;
            }

            .custom_input_field:focus {
                border-color: #e3e7f1;
            }

            .tech_support {
                color: #6b6666;
                font-size: 17px;
            }

            .tech_support:hover {
                color: #168fd3;
            }

            .error {
                display: block !important;
                text-align: justify !important;
            }

        </style>
    @endpush

    @push('js')
        <x-generic-validation-error-toastr/>
        <script>
            const loginForm = $('.login-form');
            loginForm.validate({
                rules: {
                    email: {
                        required: true,
                        pattern: /^([a-zA-Z0-9_\.\-\+])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/,
                    },
                },
                messages: {
                    email: {
                        required: "{{__('generic.enter_email')}}",
                        pattern: "{{__('generic.enter_email')}}",
                    },
                },
                submitHandler: function (htmlForm) {
                    $('.overlay').show();
                    htmlForm.submit();
                }
            });
        </script>
    @endpush


@endsection
