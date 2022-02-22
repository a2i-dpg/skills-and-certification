@php
    /** @var \App\Models\User $authUser */
    $authUser = \App\Helpers\Classes\AuthHelper::getAuthUser();

@endphp

@extends('master::layouts.master')

@section('title')
    {{ __('admin.event.index')}}
@endsection

@section('content')
    <div class="container-fluid">
        <div class="card">
            <div class="card-header text-primary custom-bg-gradient-info">
                <h3 class="card-title font-weight-bold">{{ __('admin.event.index')}}</h3>

                <div class="card-tools">
                    <div class="btn-group">
                        <a href="{{route('admin.events.edit', [!empty($event)?$event->id:''])}}"
                           class="btn btn-sm btn-outline-primary btn-rounded">
                            <i class="fas fa-plus-circle"></i> {{ __('admin.event.edit')}}
                        </a>
                        <a href="{{route('admin.events.index')}}"
                           class="btn btn-sm btn-outline-primary btn-rounded">
                            <i class="fas fa-backward"></i>{{__('admin.common.back')}}
                        </a>
                    </div>
                </div>

            </div>
            <div class="row card-body">

                @if(!empty($event))
                    @if(!$authUser->isUserBelongsToInstitute())
                        <div class="col-md-6 custom-view-box">
                            <p class="label-text">{{ __('admin.event.institute_title')}}</p>
                            <div class="input-box">
                                {{ $event->institute->title }}
                            </div>
                        </div>
                        <div class="col-md-6 custom-view-box"></div>
                    @endif
                    <div class="col-md-6 custom-view-box">
                        <p class="label-text">{{ __('admin.event.caption')}}</p>
                        <div class="input-box">
                            {{ !empty($event)?$event->caption:'' }}
                        </div>
                    </div>
                    <div class="col-md-6 custom-view-box">
                        <p class="label-text">{{ __('admin.event.date')}}</p>
                        <div class="input-box">
                            {{ Date('d M, Y h:i A', strtotime($event->date)) }}
                        </div>
                    </div>
                    @if(!empty($event->image))
                        <div class="col-md-6 custom-view-box">
                            <p class="label-text">{{ __('admin.event.image')}}</p>
                            <div class="input-box" style="height: 150px">
                                <img src="{{ asset("storage/{$event->image}") }}" height="100%">
                            </div>
                        </div>
                    @endif
                    <div class="col-md-6 custom-view-box">
                        <p class="label-text">{{ __('admin.event.detail')}}</p>
                        <div class="input-box" style="min-height: 150px;">
                            {{ $event->details }}
                        </div>
                    </div>
                @endif

            </div>
        </div>
    </div>
@endsection
