@php
    $layout = 'master::layouts.front-end';
    $authTrainee = \App\Helpers\Classes\AuthHelper::getAuthUser('trainee');

@endphp

@extends($layout)

@section('title')
{{$siteSettingInfo->site_title}} :: {{__('generic.course_enroll')}}
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card mt-3">
                    <div class="card-header bg-success">
                        <div class="row justify-content-center">
                            <p> {{__('frontend.trainee.enrollment_request')}} <strong>{{optional($enrolledCourse->course)->title }}</strong> {{__('frontend.trainee.successfully_sent')}}
                            </p>
                        </div>
                    </div>
                    <div class="card-body text-center">
                        <a href="{{ route('frontend.trainee-enrolled-courses') }}">{{__('frontend.trainee.go_to_course')}}</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
