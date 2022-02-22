@extends('master::layouts.master')

@section('title')
    View Permission
@endsection

@section('content')
    <div class="container-fluid">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Permission</h3>

                <div class="card-tools">
                    <div class="btn-group">
                        <a href="{{route('admin.permissions.edit', [$permission->id])}}" class="btn btn-sm btn-outline-primary btn-rounded">
                            <i class="fas fa-plus-circle"></i> {{ __('Edit Permission') }}
                        </a>
                        <a href="{{route('admin.permissions.index')}}" class="btn btn-sm btn-outline-primary btn-rounded">
                            <i class="fas fa-backward"></i> {{__('generic.back')}}
                        </a>
                    </div>
                </div>
            </div>
            <div class="row card-body">
                <div class="col-md-6">
                    <div class="custom-view-box">
                        <p class="label-text">{{ __('Key') }}</p>
                        <div class="input-box">
                            {{ $permission->key }}
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="custom-view-box">
                        <p class="label-text">{{ __('Table/Group') }}</p>
                        <div class="input-box">
                            {{ $permission->table_name }}
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="custom-view-box">
                        <p class="label-text">{{ __('Sub group') }}</p>
                        <div class="input-box">
                            {{ $permission->sub_group }}
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="custom-view-box">
                        <p class="label-text">{{ __('Sub group order') }}</p>
                        <div class="input-box">
                            {{ $permission->sub_group_order }}
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="custom-view-box">
                        <p class="label-text">{{ __('Is user defined?') }}</p>
                        <div class="input-box">
                            {{ $permission->is_user_defined ? 'Yes' : 'No' }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
