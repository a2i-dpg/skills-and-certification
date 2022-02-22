@php
    /** @var \App\Models\User $authUser */
    $authUser = \App\Helpers\Classes\AuthHelper::getAuthUser();
@endphp


@extends('master::layouts.master')

@section('title')
    {{ __('admin.question_answer.index') }}
@endsection

@section('content')
    <div class="container-fluid">
        <div class="card">
            <div class="card-header  custom-bg-gradient-info">
                <h3 class="card-title text-primary font-weight-bold"> {{ __('admin.question_answer.index') }}</h3>

                <div class="card-tools">
                    <div class="btn-group">
                        <a href="{{route('admin.question-answers.edit', [$questionAnswer->id])}}"
                           class="btn btn-sm btn-outline-primary btn-rounded">
                            <i class="fas fa-plus-circle"></i>  {{ __('admin.question_answer.edit') }}
                        </a>
                        <a href="{{route('admin.question-answers.index')}}"
                           class="btn btn-sm btn-outline-primary btn-rounded">
                            <i class="fas fa-backward"></i> {{__('admin.common.back')}}
                        </a>
                    </div>
                </div>

            </div>
            <div class="row card-body">

                @if(!$authUser->isUserBelongsToInstitute())
                    <div class="col-md-6 custom-view-box">
                        <p class="label-text"> {{ __('admin.question_answer.institute_title') }}</p>
                        <div class="input-box">
                            {{ $questionAnswer->institute->title ?? 'N/A' }}
                        </div>
                    </div>
                @endif


                    <div class="col-md-12 custom-view-box">
                        <p class="label-text">{{ __('admin.question_answer.question') }}</p>
                        <div class="input-box">
                            {{ $questionAnswer->question ?? 'N/A' }}
                        </div>
                    </div>

                    <div class="col-md-12 custom-view-box">
                        <p class="label-text">{{ __('admin.question_answer.answer') }}</p>
                        <div class="input-box" STYLE="min-height: 150px">
                            {!! $questionAnswer->answer ?? 'N/A' !!}
                        </div>
                    </div>

            </div>
        </div>
    </div>
@endsection
