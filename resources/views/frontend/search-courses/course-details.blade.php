@php
    $currentInstitute = app('currentInstitute');
    $layout = 'master::layouts.front-end';
    $authTrainee = \App\Helpers\Classes\AuthHelper::isAuthTrainee();

@endphp

@extends($layout)

@section('title')
{{$siteSettingInfo->site_title}} :: {{ __('generic.course') }}
@endsection

@section('content')
    <div class="container-fluid" id="fixed-scrollbar">
        <div class="row justify-content-center">
            <div class="col-md-10 col-sm-10">
                <div class="card mt-3">
                    <div class="card-header d-flex justify-content-between custom-bg-gradient-info"
                         style="background: url('{{asset('storage/'. optional($course)->cover_image)}}') no-repeat center center;
                             background-size: cover; min-height: 40vh;"
                    >
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-8">
                                <div class="card">
                                    <label style="font-weight:400!important">
                                        <div class="card-body">
                                            <div class="custom-view-box">
                                                <p class="label-text">{{__('admin.course.title')}} </p>
                                                <div class="input-box-custom" id="course_title">
                                                    {{optional($course)->title}}
                                                </div>
                                            </div>
                
                                            <div class="custom-view-box">
                                                <p class="label-text">{{__('admin.course.course_fee')}}</p>
                                                <div class="input-box-custom" id="course_fee">
                                                    {{-- {{\App\Helpers\Classes\Helper::getLocaleCurrency(optional($course)->course_fee)}} --}}
                                                    {{ \App\Helpers\Classes\Helper::getLocaleCustomCurrency($course->course_fee, $siteSettingInfo->locale, $siteSettingInfo->local_currency) ?? 'Free' }}
                                                </div>
                                            </div>
                
                                            <div class="custom-view-box">
                                                <p class="label-text">{{__('admin.course.duration')}}</p>
                                                <div class="input-box-custom" id="course_duration">
                                                    {{($course->duration) ? $course->duration . ' Hours' : 'N/A' }} 
                                                </div>
                                            </div>
                
                                            <div class="custom-view-box">
                                                <p class="label-text">{{__('admin.course.target_group')}}</p>
                                                <div class="input-box-custom" id="target_group">
                                                    {{optional($course)->target_group}}
                                                </div>
                                            </div>
                
                                            <div class="custom-view-box">
                                                <p class="label-text">{{__('admin.course.object')}}</p>
                                                <div class="input-box-custom" id="objects">
                                                    {{optional($course)->objects}}
                                                </div>
                                            </div>
                
                                            <div class="custom-view-box">
                                                <p class="label-text">{{__('admin.course.content')}}</p>
                                                <div class="input-box-custom" id="contents">
                                                    {{optional($course)->contents}}
                                                </div>
                                            </div>
                
                                            <div class="custom-view-box">
                                                <p class="label-text">{{__('admin.course.training_methodology')}}</p>
                                                <div class="input-box-custom" id="training_methodology">
                                                    {{optional($course)->training_methodology}}
                                                </div>
                                            </div>
                
                                            <div class="custom-view-box">
                                                <p class="label-text">{{__('admin.course.evaluation_system')}}</p>
                                                <div class="input-box-custom" id="evaluation_system">
                                                    {{optional($course)->evaluation_system}}
                                                </div>
                                            </div>
                
                                            <div class="col-md-12 custom-view-box">
                                                <p class="label-text">{{__('admin.course.description')}}</p>
                                                <div class="input-box-custom" id="description">
                                                    <p>{{optional($course)->description}}</p>
                                                </div>
                                            </div>
                                            <div class="custom-view-box">
                                                <p class="label-text">{{__('admin.course.course_prerequisite')}} </p>
                                                <div class="input-box-custom" id="prerequisite">
                                                    {{optional($course)->prerequisite}}
                                                </div>
                                            </div>
                                            <div class="custom-view-box">
                                                <p class="label-text">{{__('admin.course.eligibility')}}</p>
                                                <div class="input-box-custom" id="eligibility">
                                                    {{optional($course)->eligibility}}
                                                </div>
                                            </div>
                
                                            <div class="custom-view-box">
                                                <p class="label-text">{{__('admin.course.institute')}} </p>
                                                <div class="input-box-custom" id="institute_name_field">
                                                    {{optional($course->institute)->title}}
                                                </div>
                                            </div>
                                            <div class="custom-view-box">
                                                <p class="label-text">{{__('admin.course.institute_address')}}</p>
                                                <div class="input-box-custom" id="institute_address">
                                                    {{optional($course->institute)->address}}
                                                </div>
                                            </div>


                                            @if($course->runningBatches->isNotEmpty())
                                                <div class="custom-view-box">
                                                    <div class="input-box-custom">
                                                        <a href="#"
                                                        onclick="checkAuthTrainee({{ $course->id }})"
                                                        class="btn btn-success btn-lg float-left">{{ __('generic.apply') }}</a>
                                                    </div>
                                                </div>
                                            @else
                                            <div class="custom-view-box">
                                                <div class="input-box-custom">
                                                    <p class="text-info">{{ __('generic.course.no_running_batch') }}</p>
                                                    <p class="text-warning"><a href="{{ route('frontend.course_search') }}" class="text-warning">{{ __('generic.try_another') }}</a></p>
                                                    <a href="#" class="btn btn-primary btn-md disabled float-left" role="button" aria-disabled="true">{{ __('generic.apply') }}</a>
                                                </div>
                                            </div>
                                                
                                            @endif  
                                        </div>
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-4">
                                @foreach($runningBatches as $batch)
                                <div class="card">
                                    <label style="font-weight:400!important">
                                        <div class="card-body">
                                            <div>
                                               <span style="font-weight: 600;color: #212529ad!important;"> Batch: </span> <span>{{ $batch->title }}</span>
                                            </div>
                                            <div>
                                                <span style="font-weight: 600;color: #212529ad!important;"> Training Center: </span>
                                                
                                                <span>{{ optional($batch->trainingCenter)->title }}</span>
                                            </div>
                                            <div>
                                                <span style="font-weight: 600;color: #e5101aad!important"> Application End Date: </span>
                                                <span>{{ optional($batch->application_end_date)->format('d-m-Y') }}</span>
                                            </div>
                                            <div>
                                                <span style="font-weight: 600;color: #4bbf10!important;"> Batch Start Date: </span>
                                                <span>{{ optional($batch->batch_start_date)->format('d-m-Y') }}</span>
                                            </div>
                                        </div>
                                    </label>
                                </div>
                            @endforeach
                            </div>
                            
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('utils.trainee-loggedin-confirm-modal');
@endsection
@push('css')
    <style>
        .card-background-white {
            background: #faf8fb;
        }

        .form-control {
            border: 1px solid #671688;
            color: #671688;
        }

        .form-control:focus {
            border-color: #671688;
        }

        .button-bg {
            background: #671688;
            border-color: #671688;
        }

        .button-bg:hover {
            color: #ffffff;
            background-color: #671688 !important;;
            border-color: #671688 !important;;
        }

        .button-bg:active {
            color: #ffffff;
            background-color: #671688 !important;
            border-color: #671688 !important;;
        }

        .button-bg:focus {
            color: #ffffff;
            background-color: #671688 !important;;
            border-color: #671688 !important;;
        }

        .button-bg:visited {
            color: #ffffff;
            background-color: #671688 !important;;
            border-color: #671688 !important;;
        }

        .card-header-title {
            min-height: 48px;
        }

        .card-bar-home-course img {
            height: 14vw;
        }

        .gray-color {
            color: #73727f;
        }

        .course-heading-wrap {
            text-overflow: ellipsis;
            white-space: nowrap;
            overflow: hidden;
        }

        .course-heading-wrap:hover {
            overflow: visible;
            white-space: normal;
            cursor: pointer;
        }

        .course-p {
            font-size: 14px;
            font-weight: 400;
            color: #671688;
        }

        .header-bg {
            background: #671688;
            color: white;
        }

        .modal-header .close, .modal-header .mailbox-attachment-close {
            padding: 1rem;
            margin: -1rem -1rem -1rem auto;
            color: white;
            outline: none;
        }

        .card-p1 {
            color: #671688;
        }


        .custom-view-box1 .input-box-custom {
            width: 100%;
            min-height: calc(2.25rem + 2px);
            font-size: 1rem;
            font-weight: 400;
            line-height: 1.5;
            color: #495057;
        }

        .label-text {
            font-weight: bolder;
            color: #212529ad!important;
            line-height: 1.5;
            margin-bottom: 0;
            font-size: 23px;
        }
    </style>
@endpush
@push('js')
    <script>
        function checkAuthTrainee(courseId) {
            const isTraineeAuthenticated = !!'{{$authTrainee}}';

            if (isTraineeAuthenticated) {
                window.location = "{!! route('frontend.course-apply', ['course_id' => '__']) !!}".replace('__', courseId);
            } else {
                $('#loggedIn_confirm__modal').modal('show');
            }
        }
    </script>
@endpush
