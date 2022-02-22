@extends('master::layouts.master')

@section('title')
    {{ __('admin.video_categories.index') }}
@endsection

@section('content')
    <div class="container-fluid">
        <div class="card">
            <div class="card-header  custom-bg-gradient-info">
                <h3 class="card-title text-primary font-weight-bold">{{ __('admin.video_categories.index') }}</h3>

                <div class="card-tools">
                    <div class="btn-group">
                        <a href="{{route('admin.video-categories.edit', $videoCategory)}}"
                           class="btn btn-sm btn-outline-primary btn-rounded">
                            <i class="fas fa-plus-circle"></i> {{ __('admin.video_categories.edit') }}
                        </a>
                        <a href="{{route('admin.video-categories.index')}}"
                           class="btn btn-sm btn-outline-primary btn-rounded">
                            <i class="fas fa-backward"></i> {{__('admin.common.back')}}
                        </a>
                    </div>
                </div>

            </div>
            <div class="row card-body">
                <div class="col-md-6 custom-view-box">
                    <p class="label-text">{{ __('admin.video_categories.title') }}</p>
                    <div class="input-box">
                        {{ $videoCategory->title }}
                    </div>
                </div>
                <div class="col-md-6 custom-view-box">
                    <p class="label-text">{{ __('admin.video_categories.institute_title') }}</p>
                    <div class="input-box">
                        {{ $videoCategory->institute->title }}
                    </div>
                </div>
                <div class="col-md-6 custom-view-box">
                    <p class="label-text">{{ __('admin.video_categories.parent_category') }}</p>
                    <div class="input-box">
                        {{ optional(App\Models\VideoCategory::find($videoCategory->parent_id))->title }}
                    </div>
                </div>

                <div class="col-md-6 custom-view-box">
                    <p class="label-text">{{ __('admin.video_categories.status') }}</p>
                    <div class="input-box">
                        {!! $videoCategory->getCurrentRowStatus(true) !!}
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection
