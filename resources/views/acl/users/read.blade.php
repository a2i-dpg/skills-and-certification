@php
    /** @var \App\Models\User $authUser */
    $authUser = \App\Helpers\Classes\AuthHelper::getAuthUser();
    //dd($authUser->user_type_id);
@endphp


@extends('master::layouts.master')

@section('title')
    {{ __('User') }}
@endsection

@section('content')
    <div class="container-fluid">
        <div class="card">
            <div class="card-header d-flex justify-content-between custom-bg-gradient-info">
                <h3 class="card-title font-weight-bold text-primary">{{__('admin.users.index')}}</h3>
                <div class="card-tools">
                    <div class="btn-group">
                        <a href="{{route('admin.users.edit', $user->id)}}"
                           class="btn btn-sm btn-outline-primary btn-rounded">
                            <i class="fas fa-plus-circle"></i> {{__('admin.users.edit')}}
                        </a>
                        <a href="{{route('admin.users.index')}}"
                           class="btn btn-sm btn-outline-primary btn-rounded">
                            <i class="fas fa-backward"></i> {{__('admin.common.back')}}
                        </a>
                    </div>
                </div>
            </div>

            <div class="row user-profile">
                <div class="col-md-4">
                    <div class="user-details card mb-3">
                        <div class="card-body">
                            <div class="user-image text-center">
                                <img
                                    src="{{ !empty($user->profile_pic)? asset("storage/{$user->profile_pic}"):'https://www.kindpng.com/picc/m/451-4517876_default-profile-hd-png-download.png' }}"
                                    height="100" width="100" class="rounded-circle" alt="Cinque Terre">
                            </div>
                            <div class="d-flex justify-content-center user-info normal-line-height mt-3">
                                <p class="header text-center">{{ $user->name ?? "" }}</p>
                                <p class="text-center ml-2">({{ $user->name ?? ""}})</p>
                            </div>
                            <p class="designation text-center">{{ $user->userType->title ?? "" }}</p>
                        </div>

                        <div class="btn-group" role="group" aria-label="user action buttons">
                            @can('update', $user)
                                <a href="{{ route('admin.users.edit', $user->id) }}"
                                   class="btn btn-sm btn-outline-warning rounded-0 border-left-0 dt-edit button-from-view"><i
                                        class="fas fa-edit"></i> {{ __('generic.edit_button_label') }}</a>
                            @endcan
                            @can('viewUserPermission', $user)
                                <a href="{{route('admin.users.permissions', $user)}}"
                                   class="btn btn-sm btn-outline-info rounded-0 border-right-0">
                                    <i class="fas fa-users-cog"></i>
                                    {{ __('permission') }}
                                </a>
                            @endcan
                        </div>
                    </div>

                    <div class="user-contact card bg-white mb-3">
                        <div class="card-header">
                            <div class="row">
                                <div class="text-center">
                                    <i class="fa fa-envelope text-primary"></i>
                                </div>
                                <p class="medium-text ml-2 text-primary">{{ __('generic.email') }}</p>
                            </div>
                            <div class="email">
                                <p class="medium-text">{{ $user->email ?? ""}}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-8">
                    <div class="card bg-white">
                        <div class="card-header custom-bg-gradient-info text-primary">
                            <h3 class="card-title font-weight-bold">{{ __('User Info') }}</h3>
                        </div>

                        <div class="card-body row">
                            <div class="col-md-6 custom-view-box">
                                <p class="label-text">{{ __('Name(EN)') }}</p>
                                <div class="input-box">
                                    {{ $user->name ?? "" }}
                                </div>
                            </div>
                            <div class="col-md-6 custom-view-box">
                                <p class="label-text">{{ __('Email') }}</p>
                                <div class="input-box">
                                    {{ $user->email ?? ""}}
                                </div>
                            </div>

                            <div class="col-md-6 custom-view-box">
                                <p class="label-text">{{ __('User Type') }}</p>
                                <div class="input-box">
                                    {{ $user->UserType->title ?? "" }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
