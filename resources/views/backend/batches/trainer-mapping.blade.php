@php
    $edit = false;
    $authUser = \App\Helpers\Classes\AuthHelper::getAuthUser();
@endphp

@extends('master::layouts.master')

@section('title')
    {{ ! $edit ? 'Add Batch' : 'Update Batch' }}
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card card-outline">
                    <div class="card-header d-flex justify-content-between custom-bg-gradient-info">
                        <h3 class="card-title font-weight-bold text-primary">{{ ! $edit ? 'Trainer Mapping With Batch' : 'Update Batch' }}</h3>
                        <div>
                            <a href="{{route('admin.batches.index')}}"
                               class="btn btn-sm btn-rounded btn-outline-primary">
                                <i class="fas fa-backward"></i> Back to list
                            </a>
                        </div>
                    </div>

                    <div class="card-body">
                        <form class="row edit-add-form" method="post"
                              action="{{ route('admin.batches.trainer-mapping-update') }}"
                              enctype="multipart/form-data">
                            @csrf

                            <input type="hidden" class="batch" name="batch_id" value="{{$batch_id}}">
                            <input type="hidden" class="delete" name="delete[]" value="0">
                            <input type="hidden" class="update" name="update[]" value="0">
                            <input type="hidden" class="user" name="user_id[]" value="0">

                            <div class="col-sm-6">
                                <div class="form-group">
                                    <div id='AddMultiPhone' class="mb-2">
                                        <?php $sl = 0; $sl_div = 0; $user_id = 0; ?>
                                        @if(count($trainerBatches) > 0)

                                            @foreach($trainerBatches as $trainerBatch)
                                                <br>
                                                <div id="AddPhoneDiv{{ ++$sl_div }}">
                                                    <input type="hidden" class="update" name="update[]"
                                                           value="{{$trainerBatch->user->id}}">

                                                    <label for="user_id">{{ __('admin.batch.trainer') }} <span
                                                            style="color: red">*</span></label>
                                                    <select class="form-control select2 userid" name="user_id[]"
                                                            id="user_id{{ ++$user_id }}" required>

                                                        <option value="">{{ __('generic.select_trainer') }}</option>
                                                        @foreach($trainers as $trainer)
                                                            <option
                                                                value="{{ $trainer->id}}" {{($trainer->id ==$trainerBatch->user->id) ? 'selected':''}} >
                                                                {{ $trainer->name }}
                                                            </option>

                                                        @endforeach
                                                    </select>
                                                </div>
                                            @endforeach
                                        @else
                                            <div id="AddPhoneDiv{{ ++$sl_div }}">
                                                <label for="user_id">{{ __('admin.batch.trainer') }} <span
                                                        style="color: red">*</span></label>
                                                <select class="form-control select2 userid" name="user_id[]"
                                                        id="user_id{{ ++$user_id }}" required>
                                                    <option value="">{{ __('generic.select_trainer') }}</option>
                                                    @foreach($trainers as $trainer)
                                                        <option value="{{ $trainer->id}}">
                                                            {{ $trainer->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="float-right  btn-group">
                                        <a class="btn btn-sm btn-outline-primary btn-rounded" id='addPhone'>
                                            <i class="fa fa-plus-circle"></i> Add More
                                        </a>

                                        <a class="btn btn-sm btn-outline-primary btn-rounded" id='removePhone'>
                                            <i class="fa fa-minus-circle"></i> Remove One
                                        </a>
                                    </div>
                                </div>
                            </div>


                            <div class="col-sm-12 text-right">
                                <button type="submit"
                                        class="btn btn-success">{{ $edit ? __('Update') : __('Add') }}</button>
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
@push('css')
    <style>
        .flat-date-custom-bg, .flat-time-custom-bg {
            background-color: #fafdff !important;
        }

        .has-error {
            position: relative;
            padding: 0 0 12px 0;
        }

        #institute_id-error, #application_form_type_id-error, #course_id-error, #start_date-error, #end_date-error, #start_time-error, #end_time-error {
            position: absolute;
            left: 6px;
            bottom: -9px;
        }

        #publish_course_id-error, #user_id-error {
            position: absolute;
            left: 0;
            bottom: -12px;
        }

        .select2-container--default .select2-selection--single {
            background-color: #fafdff;
            border: 2px solid #ddf1ff;
            border-radius: 4px;
        }

        .select2-container .select2-selection--single {
            box-sizing: border-box;
            cursor: pointer;
            display: block;
            height: 38px;
            -moz-user-select: none;
            -ms-user-select: none;
            user-select: none;
            -webkit-user-select: none;
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 26px;
            position: absolute;
            top: 7px;
            right: 1px;
            width: 20px;
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
            },
            submitHandler: function (htmlForm) {
                $('.overlay').show();
                htmlForm.submit();
            }
        });


        $(document).ready(function () {
            let counter = 0;
            @if(count($trainerBatches) > 0)
                counter = {{count($trainerBatches)}} + 1;
            @endif

            $("#addPhone").click(function () {
                if (counter > 10) {
                    alert("Only 10 phone allow");
                    return false;
                }
                let newAddPhone = $(document.createElement('div'))
                    .attr("id", 'AddPhoneDiv' + counter);
                newAddPhone.after().html('<br /> <label for="user_id">{{ __('admin.batch.trainer') }}  <span style="color: red">*</span></label>' +
                    '<select class="form-control select2" name="user_id[]" id="user_id' + counter + '" required>' +
                    '<option value="">{{__('generic.select_trainer')}}</option>' +
                    @foreach($trainers as $trainer)
                        '<option value="{{ $trainer->id}}"> {{ $trainer->name }} </option>' +
                    @endforeach
                        '</select>'
                );
                newAddPhone.appendTo("#AddMultiPhone");
                counter++;
                $('.select2').select2();
            });

            $("#removePhone").click(function () {
                counter--;
                let userid = $("#AddPhoneDiv" + counter).find('.userid').val();
                console.log(userid);
                if (userid != undefined) {
                    $("#AddMultiPhone").append('<input type="hidden" class="delete" name="delete[]" value="' + userid + '">');
                }
                $("#AddPhoneDiv" + counter).remove();
            });
        });
    </script>
@endpush


