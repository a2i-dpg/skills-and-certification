@php
    $edit = !empty($examinationType->id);
    /** @var \App\Models\User $authUser */
    $authUser = \App\Helpers\Classes\AuthHelper::getAuthUser();
@endphp

@extends('master::layouts.master')

@section('title')
    {{ $edit? __('admin.examination_type.list')  .' :: '. __('admin.common.edit') : __('admin.examination_type.list')  .' :: '.  __('admin.common.add')  }}
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header text-primary custom-bg-gradient-info">
                        <h3 class="card-title font-weight-bold">{{ $edit? __('admin.examination_type.edit'):__('admin.examination_type.add') }}</h3>
                        <div class="card-tools">
                            <a href="{{route('admin.examination-types.index')}}"
                               class="btn btn-sm btn-outline-primary btn-rounded">
                                <i class="fas fa-backward"></i> {{__('admin.common.back')}}
                            </a>
                        </div>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <form
                            action="{{$edit ? route('admin.examination-types.update', $examinationType->id) : route('admin.examination-types.store')}}"
                            method="POST" class="row edit-add-form">
                            @csrf
                            @if($edit)
                                @method('put')
                            @endif

                            @if($authUser->isUserBelongsToInstitute())
                                <input type="hidden" id="institute_id" name="institute_id"
                                       value="{{$authUser->institute_id}}">
                            @else
                                <div class="form-group col-md-6">
                                    <label for="institute_id">{{ __('admin.examination.institute_title') }} <span
                                            style="color: red"> * </span></label>
                                    <select class="form-control select2-ajax-wizard"
                                            name="institute_id"
                                            id="institute_id"
                                            data-model="{{base64_encode(\App\Models\Institute::class)}}"
                                            data-label-fields="{title}"
                                            @if($edit)
                                            data-preselected-option="{{json_encode(['text' => $examinationType->institute->title, 'id' => $examinationType->institute_id])}}"
                                            @endif
                                            data-placeholder="{{ __('admin.examination.institute_title') }}"
                                    >
                                    </select>
                                </div>
                            @endif
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="name">{{__('admin.examination_type.title')}} <span
                                            style="color: red">*</span></label>
                                    <input type="text" class="form-control custom-input-box" id="title"
                                           name="title"
                                           value="{{$edit ? $examinationType->title : old('title')}}"
                                           placeholder="{{__('admin.examination_type.title')}}">
                                </div>
                            </div>


                            <div class="col-sm-12 text-right">
                                <button type="submit"
                                        class="btn btn-success">{{ $edit ? __('admin.common.update') : __('admin.common.add') }}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('utils.delete-confirm-modal')

@endsection

@push('css')
    <style>
        .has-error{
            position: relative;
            padding: 0px 0 12px 0;
        }
        #institute_id-error {
            position: absolute;
            right: 0;
        }
        #title-error {
            position: absolute;
            top: 0;
            right: 0;
        }
    </style>
@endpush

@push('js')
    <x-generic-validation-error-toastr></x-generic-validation-error-toastr>

    <script>
        const EDIT = !!'{{$edit}}';

        const editAddForm = $('.edit-add-form');
        editAddForm.validate({
            rules: {
                title: {
                    required: true,
                },
                institute_id: {
                    required: true,
                }
            },
            messages: {
                title: {
                        pattern: "This field is required in English.",
                },
            },
            submitHandler: function (htmlForm) {
                $('.overlay').show();
                htmlForm.submit();
            }
        });
    </script>
@endpush


