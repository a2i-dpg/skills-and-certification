@php
    $edit = !empty($header->id);
    $authUser = \App\Helpers\Classes\AuthHelper::getAuthUser();
@endphp

@extends('master::layouts.master')

@section('title')
    {{ ! $edit ? __('admin.header.add') : __('admin.header.update') }}
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card card-outline">
                    <div class="card-header text-primary custom-bg-gradient-info">
                        <h3 class="card-title font-weight-bold text-primary">{{ ! $edit ? __('admin.header.add') : __('admin.header.update') }}</h3>

                        <div class="card-tools">
                            @can('viewAny', \App\Models\Header::class)
                                <a href="{{route('admin.headers.index')}}"
                                   class="btn btn-sm btn-outline-primary btn-rounded">
                                    <i class="fas fa-backward"></i>{{__('admin.common.back')}}
                                </a>
                            @endcan
                        </div>
                    </div>

                    <div class="card-body">
                        <form class="row edit-add-form" method="post"
                              enctype="multipart/form-data"
                              action="{{ $edit ? route('admin.headers.update', [$header->id]) : route('admin.headers.store')}}">
                            @csrf
                            @if($edit)
                                @method('put')
                            @endif
                            @if($authUser->isUserBelongsToInstitute())
                                <input type="text" id="institute_id" name="institute_id" value="{{ $authUser->institute_id }}">
                            @else
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label for="institute_id">{{ __('admin.intro-video.institute_title') }}</label>
                                        <select class="form-control select2-ajax-wizard"
                                                name="institute_id"
                                                id="institute_id"
                                                data-model="{{base64_encode(App\Models\Institute::class)}}"
                                                data-label-fields="{title}"
                                                data-dependent-fields="#video_category_id"
                                                @if($edit && !empty($header->institute_id))
                                                data-preselected-option="{{json_encode(['text' =>  $header->institute->title, 'id' =>  $header->institute->id])}}"
                                                @endif
                                                data-placeholder="{{ __('generic.select_placeholder') }}"
                                        >
                                        </select>
                                    </div>
                                </div>
                            @endif

                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="title">{{ __('generic.title') }}<span
                                            style="color: red"> * </span></label>
                                    <input type="text" class="form-control" id="title"
                                           name="title"
                                           value="{{ $edit ? $header->title : old('title') }}"
                                           placeholder="{{ __('generic.title') }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="link_type">{{ __('generic.link_type') }}</label>
                                    <select id="link_type" class="form-control" name="type">
                                        <option value="url"
                                                selected="selected">{{ __('Static url') }}</option>
                                        <option value="route">{{ __('Dynamic route') }}</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="url">{{ __('generic.url') }}</label>
                                    <input type="text" class="form-control" id="url"
                                           name="url"
                                           data-code="{{ $edit ? $header->url : '' }}"
                                           value="{{ $edit ? $header->url : old('url') }}"
                                           placeholder="{{ __('generic.url') }}">
                                </div>
                            </div>

                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="route">{{ __('generic.route') }}</label>
                                    <input type="text" class="form-control" id="route"
                                           name="route"
                                           data-code="{{ $edit ? $header->route : '' }}"
                                           value="{{ $edit ? $header->route : old('route') }}"
                                           placeholder="{{ __('generic.route') }}">
                                </div>
                            </div>

                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="order">{{ __('generic.header_order') }}</label>
                                    <input type="number" class="form-control" id="order"
                                           name="order"
                                           data-code="{{ $edit ? $header->order : '' }}"
                                           value="{{ $edit ? $header->order : old('order') }}"
                                           placeholder="{{ __('generic.header_order') }}">
                                </div>
                            </div>

                            <div class="form-group col-md-6">
                                <label for="target">{{ __('generic.target') }}</label>
                                <select name="target"
                                        id="target"
                                        class="select2">
                                    @foreach(\App\Models\Header::getTargetOptions() as $key => $value)
                                        <option value=""></option>
                                        <option
                                            value="{{ $value }}" {{$edit && $header->target == $value ? 'selected' : ''}} {{ old('target') == $value ? 'selected' : '' }}>
                                            {{ $key  }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-sm-12 text-right">
                                <button type="submit"
                                        class="btn btn-success">{{ $edit ? __('admin.header.update') : __('admin.header.add') }}</button>
                            </div>
                        </form>
                    </div><!-- /.card-body -->
                    <div class="overlay" style="display: none">
                        <i class="fas fa-2x fa-sync-alt fa-spin"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('js')
    <x-generic-validation-error-toastr></x-generic-validation-error-toastr>
    <script>
        const EDIT = !!'{{$edit}}';
        const editAddForm = $('.edit-add-form');

        let linkType = $('#link_type'),
            url = $('#url'),
            route = $('#route');

        linkType.on('change', function (e) {
            console.log(linkType.val());
            if (linkType.val() === 'route') {
                url.parent().parent().hide();
                route.parent().parent().show();
            } else {
                url.parent().parent().show();
                route.parent().parent().hide();
            }
        })

        editAddForm.validate({
            rules: {
                title: {
                    required: true,
                },
                order: {
                    min: 1
                }
            },
            submitHandler: function (htmlForm) {
                $('.overlay').show();
                htmlForm.submit();
            }
        });

        $(document).ready(function () {
            route.parent().parent().hide();
        })
    </script>
@endpush


