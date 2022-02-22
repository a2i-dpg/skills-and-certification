@php
    /** @var \App\Models\User $authUser */
    $authUser = \App\Helpers\Classes\AuthHelper::getAuthUser();
@endphp

@extends('master::layouts.master')

@section('title')
    {{ __('admin.programme.index') }}
@endsection

@section('content')
    <div class="container-fluid">
        <div class="card">
            <div class="card-header text-primary custom-bg-gradient-info">
                <h3 class="card-title font-weight-bold"> {{ __('admin.programme.index') }}</h3>

                <div class="card-tools">
                    <div class="btn-group">
                        <a href="{{route('admin.programmes.edit', [$programme->id])}}" class="btn btn-sm btn-outline-primary btn-rounded">
                            <i class="fas fa-edit"></i>  {{ __('admin.programme.edit') }}
                        </a>
                        <a href="{{route('admin.programmes.index')}}" class="btn btn-sm btn-outline-primary btn-rounded">
                            <i class="fas fa-backward"></i> {{__('admin.common.back')}}
                        </a>
                    </div>

                </div>

            </div>
            <div class="row card-body">
                <div class="col-md-6 custom-view-box">
                    <p class="label-text"> {{ __('admin.programme.title') }}</p>
                    <div class="input-box">
                        {{ $programme->title }}
                    </div>
                </div>

                @if(!$authUser->isUserBelongsToInstitute())
                    <div class="col-md-6 mt-2 custom-view-box">
                        <p class="label-text"> {{ __('admin.programme.institute_title') }}</p>
                        <div class="input-box">
                            {{ $programme->institute->title }}
                        </div>
                    </div>
                @endif



                <div class="col-md-6 mt-2 custom-view-box">
                    <p class="label-text"> {{ __('admin.programme.code') }}</p>
                    <div class="input-box">
                        {{ $programme->code }}
                    </div>
                </div>

                <div class="col-md-6 mt-2 custom-view-box">
                    <p class="label-text"> {{ __('admin.programme.logo') }}</p>
                    <div class="input-box">
                        <img src="{{ asset("storage/{$programme->logo}") }}" alt="" title="" height="50px"  />
                    </div>
                </div>

                <div class="col-md-6 mt-2 custom-view-box">
                    <p class="label-text"> {{ __('admin.programme.description') }}</p>
                    <div class="input-box">
                        {{ $programme->description }}
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection
