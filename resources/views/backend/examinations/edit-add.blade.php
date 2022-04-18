@php
    $edit = !empty($examination->id);
    /** @var \App\Models\User $authUser */
    $authUser = \App\Helpers\Classes\AuthHelper::getAuthUser();
@endphp

@extends('master::layouts.master')

@section('title')
    {{ $edit? __('admin.examination.list')  .' :: '. __('admin.examination.edit') : __('admin.examination.list')  .' :: '.  __('admin.common.add')  }}
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header text-primary custom-bg-gradient-info">
                        <h3 class="card-title font-weight-bold">{{ $edit? __('admin.examination.edit'):__('admin.examination.add') }}</h3>
                        <div class="card-tools">
                            <a href="{{route('admin.examinations.index')}}"
                               class="btn btn-sm btn-outline-primary btn-rounded">
                                <i class="fas fa-backward"></i> {{__('admin.common.back')}}
                            </a>
                        </div>
        </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <form
                            action="{{$edit ? route('admin.examinations.update', $examination->id) : route('admin.examinations.store')}}"
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
                                            data-preselected-option="{{json_encode(['text' => $examination->institute->title, 'id' => $examination->institute_id])}}"
                                            @endif
                                            data-placeholder="{{  __('generic.select_placeholder')  }}"
                                    >
                                    </select>
                                </div>
                            @endif
                            <input type="hidden" id="user_id" name="user_id"
                                   value="{{$authUser->id}}">

                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="training_center_id">
                                        {{__('admin.examination.training_center')}}
                                        <span style="color: red">*</span></label>
                                    </label>

                                    <select class="form-control select2-ajax-wizard"
                                            name="training_center_id"
                                            id="training_center_id"
                                            data-model="{{base64_encode(App\Models\TrainingCenter::class)}}"
                                            data-label-fields="{title}"
                                            data-depend-on ="institute_id"
                                            data-dependent-fields="#batch_id"
                                            data-filters="{{json_encode(['institute_id' => $authUser->institute_id])}}"
                                            @if($edit)
                                            data-preselected-option="{{json_encode(['text' =>  $examination->trainingCenter->title, 'id' =>  $examination->training_center_id])}}"
                                            @endif
                                            data-placeholder="{{ __('generic.select_placeholder') }}"
                                    >
                                    </select>

                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="batch_id">
                                        {{__('admin.examination.batch_title')}}
                                        <span style="color: red">*</span></label>
                                    </label>

                                    <select class="form-control select2-ajax-wizard"
                                            name="batch_id"
                                            id="batch_id"
                                            data-model="{{base64_encode(App\Models\Batch::class)}}"
                                            data-label-fields="{title}"
                                            data-depend-on="training_center_id"
                                            data-filters="{{json_encode(['institute_id' => $authUser->institute_id, 'batch_status' => [App\Models\Batch::BATCH_STATUS_COMPLETE, 'type' => 'not-equal'] ])}}"
                                            @if($edit)
                                            data-preselected-option="{{json_encode(['text' =>  $examination->batch->title, 'id' =>  $examination->batch_id])}}"
                                            @endif
                                            data-placeholder="{{ __('generic.select_placeholder') }}"
                                    >
                                    </select>

                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="examination_type_id">
                                        {{__('admin.examination.examination_type')}}
                                        <span style="color: red">*</span></label>
                                    </label>

                                    <select class="form-control select2-ajax-wizard"
                                            name="examination_type_id"
                                            id="examination_type_id"
                                            data-model="{{base64_encode(App\Models\ExaminationType::class)}}"
                                            data-label-fields="{title}"
                                            data-depend-on="institute_id"
                                            @if($edit)
                                            data-preselected-option="{{json_encode(['text' =>  $examination->examinationType->title, 'id' =>  $examination->examination_type_id ])}}"
                                            @endif
                                            data-filters="{{json_encode(['institute_id' => $authUser->institute_id])}}"
                                            data-placeholder="{{ __('generic.select_placeholder') }}"
                                          >
                                    </select>

                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="pass_mark">{{__('admin.examination.code')}} <span
                                            style="color: red">*</span></label>
                                    <input type="number" class="form-control" id="code"
                                           name="code"
                                           data-code="{{ $edit ? $examination->code : '' }}"
                                           value="{{ $edit ? $examination->code : old('code') }}"
                                           placeholder="{{__('admin.examination.code')}}">
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="total_mark">{{__('admin.examination.total_mark')}} <span
                                            style="color: red">*</span></label>
                                    <input type="number" class="form-control" id="total_mark"
                                           name="total_mark"
                                           value="{{ $edit ? $examination->total_mark : old('total_mark') }}"
                                           placeholder="{{__('admin.examination.total_mark')}}">
                                </div>
                            </div>

                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="pass_mark">{{__('admin.examination.pass_mark')}} <span
                                            style="color: red">*</span></label>
                                    <input type="number" class="form-control" id="pass_mark"
                                           name="pass_mark"
                                           value="{{ $edit ? $examination->pass_mark : old('pass_mark') }}"
                                           placeholder="{{__('admin.examination.pass_mark')}}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="exam_details">
                                        {{__('admin.examination.exam_details')}}
                                        <span style="color: red">*</span></label>
                                    </label>

                                    <textarea class="form-control" placeholder="{{__('admin.examination.exam_details')}}"
                                              name="exam_details" id=""  rows="1">{{ $edit ? $examination->exam_details:old('exam_details') }}</textarea>
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

        #institute_id-error, #examination_type_id-error, #training_center_id-error, #batch_id-error {
            position: absolute;
            right: 0;
        }
        #total_mark-error, #code-error, #pass_mark-error, #exam_details-error {
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
                total_mark: {
                    required: true,
                },

                pass_mark: {
                    required: true,
                },
                exam_details: {
                    required: true,
                },

                examination_type_id: {
                    required: true
                },
                batch_id: {
                    required: true
                },
                training_center_id: {
                    required: true
                },
                institute_id: {
                    required: true
                },
                code: {
                    required: true,
                    remote: {
                        param: {
                            type: "post",
                            url: "{{ route('admin.examinations.examinationCodeCheck') }}",
                        },
                        depends: function (element) {
                            return $(element).val() !== $('#code').attr('data-code');
                        }
                    },
                },
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


