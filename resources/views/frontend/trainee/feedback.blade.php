@php
    $edit = !empty($trainer->feedback);
    $currentInstitute = app('currentInstitute');
     /** @var \App\Models\User $authUser */
    $authUser = \App\Helpers\Classes\AuthHelper::getAuthUser();
    $layout = 'master::layouts.front-end';

@endphp
@extends($layout)

@section('title')
{{$siteSettingInfo->site_title}} :: {{__('generic.feedback')}}
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row trainee-profile" id="trainee-profile">
            <div class="col-md-3 mt-2">
                <div class="user-details card mb-3">
                    <div
                        class="card-header custom-bg-gradient-info">
                        <div class="card-title float-left font-weight-bold text-primary">{{__('generic.details')}}</div>
                    </div>
                    <div class="card-body">
                        <div class="user-image text-center">
                            <img
                                src="{{ $trainee->profile_pic ? asset('storage/'. $trainee->profile_pic ) : "http://via.placeholder.com/640x360"}}"
                                height="100" width="100" class="rounded-circle" alt="Trainee profile picture">
                        </div>
                        <div class="d-flex justify-content-center user-info normal-line-height mt-3">
                            <div class="text-bold">
                                {{ optional($trainee)->name }}
                            </div>
                        </div>
                    </div>
                </div>

                <div class="user-contact card bg-white mb-3">
                    <div class="card-header">
                        <div class="row">
                            <div class="text-center">
                                <i class="fa fa-phone"></i>
                            </div>
                            <p class="medium-text ml-2 text-primary">{{ __('generic.mobile')  }}</p>
                        </div>
                        <div class="phone">
                            <p class="medium-text">{{ $trainee->mobile ? $trainee->mobile : "N/A" }}</p>
                        </div>
                    </div>
                    <div class="card-header">
                        <div class="row">
                            <div class="text-center">
                                <i class="fa fa-envelope"></i>
                            </div>
                            <p class="medium-text ml-2 text-primary">{{ __('generic.email') }}</p>
                        </div>
                        <div class="email">
                            <p class="medium-text">{{ $trainee->email ?? "N/A"}}</p>
                        </div>
                    </div>
                    <div class="card-header">
                        <div class="row">
                            <div class="text-center">
                                <i class="fas fa-edit"></i>
                            </div>
                            <p class="medium-text ml-2 text-primary">{{__('generic.signature')}}</p>
                        </div>
                        <div class="email">
                            <img
                                src="{{ $trainee->student_signature_pic ? asset('storage/'. $trainee->student_signature_pic ) : "http://via.placeholder.com/640x360" }}"
                                height="40" alt="Trainee profile picture">
                        </div>
                    </div>
                </div>

            </div>

            <div class="col-md-9 mt-2">
                <div class="card bg-white">
                    <div class="card-header custom-bg-gradient-info">
                        <div class="card-title float-left font-weight-bold text-primary">{{__('generic.feedback')}}</div>
                        <div class="card-tools">
                            <a href="{{route('frontend.trainee-batch-trainer', $trainer->batch_id)}}"
                               class="btn btn-sm btn-outline-primary btn-rounded">
                                <i class="fas fa-backward"></i> {{__('admin.common.back')}}
                            </a>
                        </div>
                    </div>

                    <div class="card-body">
                        <form
                            action="{{$edit ? '' : route('frontend.trainee-feedback-store')}}"
                            method="POST" class="row feedback-form">
                            @csrf
                                <input type="hidden" name="id" value="{{ $trainer->id }}">
                                <input type="hidden" name="trainee_id" value="{{ $trainee->id }}">
                                <input type="hidden" name="batch_id" value="{{ $trainer->batch_id }}">
                                <input type="hidden" name="user_id" value="{{ $trainer->user_id}}">

                                <div class="form-group col-sm-6">
                                        <label for="exam_details">
                                            {{__('admin.trainee_batches.feedback')}}
                                            <span style="color: red">*</span></label>
                                        </label>

                                        <textarea class="form-control" placeholder="{{__('admin.trainee_batches.feedback')}}"
                                                  name="feedback" id=""  rows="3" {{$edit ? 'readonly' : ''}}>{{ $edit ? $trainer->feedback : old('feedback') }}</textarea>
                                </div>
                            @if(!$edit )
                                <div class="col-sm-12 text-right">
                                    <button type="submit"
                                            class="btn btn-success">{{__('admin.common.add') }}</button>
                                </div>
                            @endif
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>

@endsection

@push('css')
    <link rel="stylesheet" href="{{asset('/css/datatable-bundle.css')}}">
@endpush

@push('js')
    <script type="text/javascript" src="{{asset('/js/datatable-bundle.js')}}"></script>
    <script>
        const feedbackForm = $('.feedback-form');
        feedbackForm.validate({
            rules: {
                feedback: {
                    required: true
                },
            },
            submitHandler: function (htmlForm) {
                $('.overlay').show();
                htmlForm.submit();
            }
        });

    </script>
@endpush


