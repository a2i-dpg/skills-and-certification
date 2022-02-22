@extends('master::layouts.master')

@section('title')
    {{ __('admin.institute.index') }}
@endsection

@section('content')
    <div class="container-fluid">
        <div class="card">
            <div class="card-header text-primary custom-bg-gradient-info">
                <h3 class="card-title font-weight-bold">{{ __('admin.institute.index') }}</h3>

                <div class="card-tools">
                    <div class="btn-group">
                        @can('update', $institute)
                            <a href="{{route('admin.institutes.edit', [$institute->id])}}"
                               class="btn btn-sm btn-outline-primary btn-rounded">
                                <i class="fas fa-plus-circle"></i>{{ __('admin.institute.edit') }}
                            </a>
                        @endcan
                        @can('viewAny', $institute)
                            <a href="{{route('admin.institutes.index')}}"
                               class="btn btn-sm btn-outline-primary btn-rounded">
                                <i class="fas fa-backward"></i> {{__('admin.common.back')}}
                            </a>
                        @endcan
                    </div>
                </div>

            </div>
            <div class="row card-body">
                <div class="col-md-6 custom-view-box">
                    <p class="label-text">{{ __('admin.institute.index') }}</p>
                    <div class="input-box">
                        {{ $institute->title }}
                    </div>
                </div>

                <div class="col-md-6 custom-view-box">
                    <p class="label-text">{{ __('generic.email') }}</p>
                    <div class="input-box">
                        {{ $institute->email }}
                    </div>
                </div>

                <div class="col-md-6 custom-view-box">
                    <p class="label-text">{{ __('generic.mobile') }}</p>
                    <div class="input-box">
                        {{ $institute->mobile }}
                    </div>
                </div>

                <div class="col-md-6 custom-view-box">
                    <p class="label-text">{{ __('generic.address') }}</p>
                    <div class="input-box">
                        {{ $institute->address }}
                    </div>
                </div>

                <div class="col-md-6 custom-view-box">
                    <p class="label-text">{{ __('generic.office_head_name') }}</p>
                    <div class="input-box">
                        {{ $institute->office_head_name }}
                    </div>
                </div>


                <div class="col-md-6 custom-view-box">
                    <p class="label-text">{{ __('generic.office_head_post') }}</p>
                    <div class="input-box">
                        {{ $institute->office_head_post }}
                    </div>
                </div>

                <div class="col-md-6 custom-view-box">
                    <p class="label-text">{{ __('generic.contact_person_name') }}</p>
                    <div class="input-box">
                        {{ $institute->contact_person_name }}
                    </div>
                </div>

                <div class="col-md-6 custom-view-box">
                    <p class="label-text">{{ __('generic.contact_person_post') }}</p>
                    <div class="input-box">
                        {{ $institute->contact_person_post }}
                    </div>
                </div>

                <div class="col-md-6 custom-view-box">
                    <p class="label-text">{{ __('generic.contact_person_email') }}</p>
                    <div class="input-box">
                        {{ $institute->contact_person_email }}
                    </div>
                </div>

                <div class="col-md-6 custom-view-box">
                    <p class="label-text">{{  __('admin.institute.google_map_src') }}</p>
                    <div class="input-box">
                        {{ $institute->google_map_src }}
                    </div>
                </div>

                <div class="col-md-6 custom-view-box">
                    <p class="label-text">{{ __('admin.institute.description') }}</p>
                    <div class="input-box">
                        {{ !empty($institute->description)? $institute->description: '' }}
                    </div>
                </div>

                <div class="col-md-6 mt-2 custom-view-box">
                    <p class="label-text">{{ __('admin.institute.logo') }}</p>
                    <div class="input-box">
                        <img src="{{ $institute->logo ?  asset("storage/{$institute->logo}") : "http://via.placeholder.com/640x360"}}" alt="" title="" height="50px"/>
                    </div>
                </div>

                <div class="col-md-6 mt-2 custom-view-box">
                    <p class="label-text">Active status</p>
                    <div class="input-box">
                        {!! $institute->getCurrentRowStatus(true) !!}
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection
