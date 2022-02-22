@php
    $edit = !empty($feedback->feedback);
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
                        <h3 class="card-title font-weight-bold">{{ $edit? __('admin.trainee_batches.view_feedback'):__('admin.trainee_batches.add_feedback') }}</h3>
                        <div class="card-tools">
                            <a href="{{route('admin.batches.trainees', [$feedback->batch_id])}}"
                               class="btn btn-sm btn-outline-primary btn-rounded">
                                <i class="fas fa-backward"></i> {{__('admin.common.back')}}
                            </a>
                        </div>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <form
                            action="{{$edit ? '': route('admin.batches.feedback.store')}}"
                            method="POST" class="row feedback-form">
                            @csrf
                            <input type="hidden" name="id" value="{{$feedback->id}}">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="exam_details">
                                        {{__('admin.trainee_batches.feedback')}}
                                        <span style="color: red">*</span></label>
                                    </label>

                                    <textarea class="form-control" placeholder="{{__('admin.trainee_batches.feedback')}}"
                                              name="feedback" id=""  rows="3" {{ $edit ? 'readonly' : '' }}>{{ $edit ? $feedback->feedback:old('feedback') }}</textarea>
                                </div>
                            </div>

                            @if(!$edit)
                                <div class="col-sm-12 text-right">
                                    <button type="submit"
                                            class="btn btn-success">{{__('admin.common.add') }}</button>
                                </div>
                            @endif
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

        const editAddForm = $('.feedback-form');
        editAddForm.validate({
            rules: {
                feedback: {
                    required: true
                }
            },
            messages: {
                feedback: {
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


