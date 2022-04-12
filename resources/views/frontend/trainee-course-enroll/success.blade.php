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
                            <p> Your enrollment request for <strong>{{optional($enrolledCourse->course)->title }}</strong> successfully sent. Please wait for approval.
                            </p>
                        </div>
                    </div>
                    <div class="card-body text-center">
                        <a href="{{ route('frontend.trainee-enrolled-courses') }}">Go to my course</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
