@extends('master::layouts.master')

@section('title')
    {{ __('admin.slider.index') }}
@endsection

@section('content')
    <div class="container-fluid">
        <div class="card">
            <div class="card-header text-primary custom-bg-gradient-info">
                <h3 class="card-title">{{ __('admin.slider.index') }}</h3>

                <div class="card-tools">
                    <div class="btn-group">
                        <a href="{{route('admin.sliders.edit', $slider)}}" class="btn btn-sm btn-outline-primary btn-rounded">
                            <i class="fas fa-plus-circle"></i> {{ __('admin.slider.edit') }}
                        </a>
                        <a href="{{route('admin.sliders.index')}}" class="btn btn-sm btn-outline-primary btn-rounded">
                            <i class="fas fa-backward"></i> {{__('admin.common.back')}}
                        </a>
                    </div>
                </div>

            </div>
            <div class="row card-body">
                <div class="col-md-12">
                    <img src="{{ asset('storage/'.$slider->slider) }}" alt="slider image" height="200" width="100%">
                </div>
                <div class="col-md-6 custom-view-box">
                    <p class="label-text">{{ __('admin.slider.title') }}</p>
                    <div class="input-box">
                        {{ $slider->title }}
                    </div>
                </div>

                <div class="col-md-6 custom-view-box">
                    <p class="label-text">{{ __('admin.slider.sub_title') }}</p>
                    <div class="input-box">
                        {{ $slider->sub_title }}
                    </div>
                </div>

                <div class="col-md-6 custom-view-box">
                    <p class="label-text">{{ __('admin.slider.link') }}</p>
                    <div class="input-box">
                        {{ $slider->link ?? 'N/A'}}
                    </div>
                </div>

                <div class="col-md-6 custom-view-box">
                    <p class="label-text">{{ __('admin.slider.button_text') }}}</p>
                    <div class="input-box">
                        {{ $slider->button_text ?? "N/A" }}
                    </div>
                </div>


                <div class="col-md-6 custom-view-box">
                    <p class="label-text">{{ __('admin.common.status') }}</p>
                    <div class="input-box">
                        {!! $slider->getCurrentRowStatus(true) !!}
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection
