@php
    /** @var \App\Models\User $authUser */
    $authUser = \App\Helpers\Classes\AuthHelper::getAuthUser();
@endphp
@extends('master::layouts.master')

@section('title')
    {{ __('Branch') }}
@endsection

@section('content')
    <div class="container-fluid">
        <div class="card">
            <div class="card-header text-primary custom-bg-gradient-info">
                <h3 class="card-title font-weight-bold">{{__('admin.branch.index')}}</h3>

                <div class="card-tools">
                    <div class="btn-group">
                        <a href="{{route('admin.branches.edit', [$branch->id])}}" class="btn btn-sm btn-outline-primary btn-rounded">
                            <i class="fas fa-plus-circle"></i> {{ __('Edit Branch') }}
                        </a>
                        <a href="{{route('admin.branches.index')}}" class="btn btn-sm btn-outline-primary btn-rounded">
                            <i class="fas fa-backward"></i> {{__('admin.common.back')}}
                        </a>
                    </div>
                </div>
            </div>

            <div class="row card-body">

                <div class="col-md-6 custom-view-box">
                    <p class="label-text">{{__('admin.branch.title')}} </p>
                    <div class="input-box">
                        {{ $branch->title }}
                    </div>
                </div>


                <div class="col-md-6 custom-view-box">
                    <p class="label-text">{{__('admin.branch.address')}} </p>
                    <div class="input-box" style="min-height: 100px;">
                        {{ $branch->address }}
                    </div>
                </div>

                <div class="col-md-6 custom-view-box">
                    <p class="label-text">{{__('admin.branch.google_map_source')}} </p>
                    <div class="input-box" style="min-height: 100px;">
                        {{ $branch->google_map_src }}
                    </div>
                </div>

                @if(!$authUser->isUserBelongsToInstitute())
                    <div class="col-md-6  custom-view-box">
                        <p class="label-text">{{__('admin.branch.institute_title')}} </p>
                        <div class="input-box">
                            {{$branch->institute->title}}
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
