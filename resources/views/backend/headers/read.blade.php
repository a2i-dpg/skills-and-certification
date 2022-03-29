@extends('master::layouts.master')

@section('title')
    {{ __('admin.institute.index') }}
@endsection

@section('content')
    <div class="container-fluid">
        <div class="card">
            <div class="card-header text-primary custom-bg-gradient-info">
                <h3 class="card-title font-weight-bold">{{ __('admin.headers.index') }}</h3>

                <div class="card-tools">
                    <div class="btn-group">
                        @can('update', $header)
                            <a href="{{route('admin.headers.edit', [$header->id])}}"
                               class="btn btn-sm btn-outline-primary btn-rounded">
                                <i class="fas fa-plus-circle"></i>{{ __('admin.header.edit') }}
                            </a>
                        @endcan
                        @can('viewAny', $header)
                            <a href="{{route('admin.headers.index')}}"
                               class="btn btn-sm btn-outline-primary btn-rounded">
                                <i class="fas fa-backward"></i> {{__('admin.common.back')}}
                            </a>
                        @endcan
                    </div>
                </div>

            </div>
            <div class="row card-body">
                <div class="col-md-6 custom-view-box">
                    <p class="label-text">{{ __('generic.institute') }}</p>
                    <div class="input-box">
                        {{ $header->institute_id ? $header->institute->title : '' }}
                    </div>
                </div>

                <div class="col-md-6 custom-view-box">
                    <p class="label-text">{{ __('admin.header.index') }}</p>
                    <div class="input-box">
                        {{ $header->title }}
                    </div>
                </div>

                <div class="col-md-6 custom-view-box">
                    <p class="label-text">{{ __('generic.url') }}</p>
                    <div class="input-box">
                        {{ $header->url }}
                    </div>
                </div>

                <div class="col-md-6 custom-view-box">
                    <p class="label-text">{{ __('generic.route') }}</p>
                    <div class="input-box">
                        {{ $header->route }}
                    </div>
                </div>

                <div class="col-md-6 custom-view-box">
                    <p class="label-text">{{ __('generic.header_order') }}</p>
                    <div class="input-box">
                        {{ $header->order }}
                    </div>
                </div>

                <div class="col-md-6 custom-view-box">
                    <p class="label-text">{{ __('generic.target') }}</p>
                    <div class="input-box">
                        {{ $header->target }}
                    </div>
                </div>

                <div class="col-md-6 mt-2 custom-view-box">
                    <p class="label-text">Active status</p>
                    <div class="input-box">
                        {!! $header->getCurrentRowStatus(true) !!}
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection
