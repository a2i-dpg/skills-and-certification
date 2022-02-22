@php
    $currentInstitute = app('currentInstitute');
    $layout = 'master::layouts.front-end';
@endphp
@extends($layout)

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-sm-6 mx-auto mb-5">
                <div class="card text-center mt-5 mb-5">
                    <h5 class="card-header bg-success">{{__('generic.successfully_registered')}}</h5>
                    <div class="card-body pt-5 pb-5">
                        <h5 class="display-5">{{__('generic.application_successfully_submitted')}}</h5><br>
                        <p class="text-muted">{{__('generic.your_access_key')}}:<strong> {{$accessKey}}</strong></p>
                        <p class="text-warning">
                            <em>{{__('generic.save_your_access_key')}}</em>
                        </p>
                        <p class="card-text">{{__('generic.you_can_access_your_profile_using_this_access_key')}}<a
                                href="{{route('frontend.trainee.login-form')}}">{{__('generic.click')}}</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('js')
@endpush
