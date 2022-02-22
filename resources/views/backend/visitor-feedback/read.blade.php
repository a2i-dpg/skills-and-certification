@php
    /** @var \App\Models\User $authUser */
    $authUser = \App\Helpers\Classes\AuthHelper::getAuthUser();
@endphp
@extends('master::layouts.master')

@section('title')
    {{ __('admin.visitor_feedback.visitor_feedback')  }}
@endsection

@section('content')
    <div class="container-fluid">
        <div class="card">
            <div class="card-header text-primary custom-bg-gradient-info">
                <h3 class="card-title font-weight-bold">{{ __('admin.visitor_feedback.visitor_feedback')  }}</h3>

                <div class="card-tools">
                    <div class="btn-group">
                        <a href="{{route('admin.visitor-feedback.index')}}" class="btn btn-sm btn-outline-primary btn-rounded">
                            <i class="fas fa-backward"></i> {{__('admin.common.back')}}
                        </a>
                    </div>
                </div>
            </div>

            <div class="row card-body">

                <div class="col-md-6 custom-view-box">
                    <p class="label-text">    {{ __('admin.visitor_feedback.name')  }}</p>
                    <div class="input-box">
                        {{ $visitorFeedback->name }}
                    </div>
                </div>

                <div class="col-md-6 custom-view-box">
                    <p class="label-text">    {{ __('admin.visitor_feedback.mobile')  }}</p>
                    <div class="input-box">
                        {{ $visitorFeedback->mobile }}
                    </div>
                </div>

                <div class="col-md-6 custom-view-box">
                    <p class="label-text">    {{ __('admin.visitor_feedback.email')  }}</p>
                    <div class="input-box">
                        {{ $visitorFeedback->email }}
                    </div>
                </div>

                @if($visitorFeedback->address)
                <div class="col-md-6 custom-view-box">
                    <p class="label-text">    {{ __('admin.visitor_feedback.address')  }}</p>
                    <div class="input-box">
                        {{ $visitorFeedback->address }}
                    </div>
                </div>
                @endif

                @if(!$authUser->isUserBelongsToInstitute())
                    <div class="col-md-6  custom-view-box">
                        <p class="label-text">    {{ __('admin.visitor_feedback.institute_title')  }}</p>
                        <div class="input-box">
                            {{$visitorFeedback->institute->title}}
                        </div>
                    </div>
                @endif

                <div class="col-md-6 custom-view-box">
                    <p class="label-text">    {{ __('admin.visitor_feedback.type')  }}</p>
                    <div class="input-box">
                        {{ $visitorFeedback->form_type==\App\Models\VisitorFeedback::FORM_TYPE_FEEDBACK?'Feedback':'Contact' }}
                    </div>
                </div>

                <div class="col-md-6 custom-view-box">
                    <p class="label-text">    {{ __('admin.visitor_feedback.comment')  }}</p>
                    <div class="input-box" style="min-height: 130px">
                        {{ $visitorFeedback->comment }}
                    </div>
                </div>


            </div>
        </div>
    </div>
@endsection
