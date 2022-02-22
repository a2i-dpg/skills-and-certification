@php
    $edit = !empty($examinationResult->id);
    /** @var \App\Models\User $authUser */
    $authUser = \App\Helpers\Classes\AuthHelper::getAuthUser();
@endphp

@extends('master::layouts.master')

@section('title')
    {{ $edit? __('admin.examination_result.list')  .' :: '. __('admin.common.edit') : __('admin.examination_result.list')  .' :: '.  __('admin.common.add')  }}
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header text-primary custom-bg-gradient-info">
                        <h3 class="card-title font-weight-bold">{{ $edit? __('admin.common.edit'):__('admin.common.add') }}</h3>
                        <div class="card-tools">
                            <a href="{{route('admin.examination-results.index')}}"
                               class="btn btn-sm btn-outline-primary btn-rounded">
                                <i class="fas fa-backward"></i> {{__('admin.common.back')}}
                            </a>
                        </div>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <form
                            action="{{$edit ? route('admin.examination-results.update', $examinationResult->id) : route('admin.examination-results.store')}}"
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
                                                @if(!empty($examination))
                                                data-preselected-option="{{json_encode(['text' => $examination->institute->title, 'id' => $examination->institute_id])}}"
                                                @endif
                                            @endif
                                            data-placeholder="{{ __('admin.examination.institute_title') }}"
                                    >
                                    </select>
                                </div>
                            @endif

                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="examination_id">
                                        {{__('admin.examination_result.batch_title')}}
                                        <span class="required"></span>
                                    </label>

                                    <select class="form-control select2-ajax-wizard"
                                            name="batch_id"
                                            id="batch_id"
                                            data-model="{{base64_encode(App\Models\Batch::class)}}"
                                            data-label-fields="{title}"
                                            data-depend-on="institute_id"
                                            data-filters="{{json_encode(['institute_id' => $authUser->institute_id, 'batch_status'=> \App\Models\Batch::BATCH_STATUS_ON_GOING ])}}"
                                            @if($edit)
                                            @if(!empty($examination))
                                            data-preselected-option="{{json_encode(['text' =>  $examination->batch->title, 'id' =>  $examination->batch_id])}}"
                                            @endif
                                            @endif
                                            data-placeholder="{{ __('generic.select_placeholder') }}"
                                    >
                                    </select>

                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="examination_id">
                                        {{__('admin.examination_result.examination')}}
                                        <span class="required"></span>
                                    </label>
                                    <select class="form-control select2-ajax-wizard"
                                            name="examination_id"
                                            id="examination_id"
                                            data-model="{{base64_encode(App\Models\Examination::class)}}"
                                            data-label-fields="{code}"
                                            data-depend-on="batch_id"
                                            data-filters="{{json_encode(['institute_id' => $authUser->institute_id, 'status'=> \App\Models\Examination::EXAMINATION_STATUS_COMPLETE])}}"
                                            @if($edit)
                                                @if(!empty($examination))
                                                data-preselected-option="{{json_encode(['text' =>  $examination->code, 'id' =>  $examination->id])}}"
                                                @endif
                                            @endif
                                            data-placeholder="{{ __('generic.select_placeholder') }}"
                                    >
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="trainee_id">
                                        {{__('admin.examination_result.trainee')}}
                                        <span class="required"></span>
                                    </label>
                                    <select class="form-control select2-ajax-wizard"
                                            name="trainee_id"
                                            id="trainee_id"
                                            {{--@if($edit)
                                                data-model="{{base64_encode(App\Models\Examination::class)}}"
                                                data-label-fields="{name}"
                                                data-depend-on="examination_id"
                                                data-filters="{{json_encode(['institute_id' => $authUser->institute_id, 'status'=> \App\Models\Examination::EXAMINATION_STATUS_COMPLETE])}}"
                                                @if($edit)
                                                    @if(!empty($examination))
                                                    data-preselected-option="{{json_encode(['text' =>  $examination->code, 'id' =>  $examination->id])}}"
                                                    @endif
                                                @endif
                                                data-placeholder="{{ __('generic.select_placeholder') }}"
                                            @endif--}}
                                    >
                                        @if($edit)
                                            <option selected value="{{$examinationResult->trainee->id}}">{{$examinationResult->trainee->name}}</option>
                                        @endif
                                    </select>
                                </div>
                            </div>

                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="achieved_marks">{{__('admin.examination_result.achieved_marks')}} <span
                                            style="color: red">*</span></label>
                                    <input type="number" class="form-control" id="achieved_marks"
                                           name="achieved_marks"
                                           value="{{ $edit ? $examinationResult->achieved_marks : old('achieved_marks') }}"
                                           placeholder="{{__('admin.examination_result.achieved_marks')}}">
                                </div>
                            </div>

                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="name">{{__('admin.examination_result.feedback')}} <span
                                            style="color: red">*</span></label>
                                    <input type="text" class="form-control custom-input-box" id="feedback"
                                           name="feedback"
                                           value="{{$edit ? $examinationResult->feedback : old('feedback')}}"
                                           placeholder="{{__('admin.examination_result.feedback')}}">
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
        #institute_id-error{
            position: absolute;
            left: 6px;
            bottom: -9px;
        }
    </style>
@endpush

@push('js')
    <x-generic-validation-error-toastr></x-generic-validation-error-toastr>

    <script>
        const EDIT = !!'{{$edit}}';
        const trainee_id = '{{$examinationResult->trainee_id}}';

        console.log(trainee_id)


        const editAddForm = $('.edit-add-form');
        editAddForm.validate({
            rules: {
                feedback: {
                    required: true,
                },
                institute_id: {
                    required: true,
                },
                batch_id: {
                    required: true,
                },
                examination_id: {
                    required: true,
                },
                achieved_marks: {
                    required: true,
                },
                trainee_id: {
                    required: true,
                }
            },
            messages: {
                feedback: {
                        pattern: "This field is required",
                },
            },
            submitHandler: function (htmlForm) {
                $('.overlay').show();
                htmlForm.submit();
            }
        });


        /*$(document).ready(function() {
            $('.batch_id').on('change', function(e){
                let batch_id = e.target.value;
                if (!batch_id) {
                    batch_id = 0;
                }
                var route = "{{route('admin.examination-results.get-trainees')}}/"+batch_id;
                $.get(route, function(data) {
                    console.log(data);
                    $('#trainee_id').empty();
                    $('#trainee_id').append('<option value="'+'">{{__('admin.common.select')}}</option>');
                    $.each(data, function(index,data){
                        console.log(data);
                        $('#trainee_id').append('<option value="' + data.id + '">' + data.trainee_name + '</option>');
                    });
                });
            });

            // For presetting feedback value
            $('#feedback').val('Good');
        });*/


        $(document).ready(function() {
            $('#examination_id').on('change', function(e){
                let examination_id = e.target.value;
                console.log(examination_id);
                if (!examination_id) {
                    examination_id = 0;
                }
                var route = "{{route('admin.examination-results.get-trainees')}}/"+examination_id;
                $.get(route, function(data) {
                    console.log(data);
                    $('#trainee_id').empty();
                    $('#trainee_id').append('<option value="'+'">{{__('admin.common.select')}}</option>');
                    $.each(data, function(index,data){
                        $('#trainee_id').append('<option value="' + data.id + '">' + data.trainee_name + '</option>');
                    });
                });

            });

            // For presetting feedback value
            $('#feedback').val('Good');
        });


    </script>
@endpush


