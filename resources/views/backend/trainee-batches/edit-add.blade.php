@php
    $edit = !empty($batch->id);
    /** @var \App\Models\User $authUser */
    $authUser = \App\Helpers\Classes\AuthHelper::getAuthUser();
@endphp

@extends('master::layouts.master')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card card-outline">
                    <div class="card-header d-flex justify-content-between custom-bg-gradient-info">
                        <h3 class="card-title font-weight-bold text-primary">{{ ! $edit ?  __('admin.batches.add')  : __('admin.batches.update') }}</h3>
                        <div>
                            <a href="{{route('admin.batches.index')}}" class="btn btn-sm btn-rounded btn-outline-primary">
                                <i class="fas fa-backward"></i>{{__('admin.common.back')}}
                            </a>
                        </div>
                    </div>

                    <div class="card-body">
                        <form class="row edit-add-form" method="post"
                              action="{{ $edit ? route('admin.batches.update', [$batch->id]) : route('admin.batches.store')}}">
                            @csrf
                            @if($edit)
                                @method('put')
                            @endif

                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="title">{{ __('admin.batches.title')}}</label>
                                    <input type="text" class="form-control" id="title"
                                           name="title"
                                           value="{{ $edit ? $batch->title: old('title') }}">
                                </div>
                            </div>

                            @if($authUser->isUserBelongsToInstitute())
                                <input type="hidden" id="institute_id" name="institute_id" value="{{$authUser->institute_id}}">
                            @else
                                <div class="form-group col-md-6">
                                    <label for="institute_id">{{ __('admin.batches.institute_title')}}</label>
                                    <select class="form-control custom-input-box select2" name="institute_id"
                                            id="institute_id" required>
                                        <option value="" selected>{{ __('admin.batches.please_select')}}</option>
                                        @foreach($institutes as $institute)
                                            <option
                                                value="{{ $institute->id}}" {{ $edit && $batch->institute_id == $institute->id ? 'selected':''}}>{{ $institute->title}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            @endif


                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="course_id">{{ __('admin.batches.course_title')}}</label>

                                        <select name="course_id" id="course_id" class="form-control select2">
                                            @if($edit && !empty($batch->course->id))
                                                @foreach($courses as $course)
                                                    <option value="{{ $course->id }}" {{ $course->id == $batch->course->id ? 'selected':''}}>{{ $course->title }}</option>
                                                @endforeach
                                            @else
                                                <select name="course_id" id="course_id" class="form-control select2">
                                                </select>
                                            @endif
                                        </select>

                                </div>
                            </div>

                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="code">{{ __('admin.batches.code')}}</label>
                                    <input type="number" class="form-control" id="code"
                                           name="code"
                                           value="{{ $edit ? $batch->code : old('code') }}"
                                           placeholder="{{ __('admin.batches.code')}}">
                                </div>
                            </div>

                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="max_student_enrollment">{{ __('admin.batches.max_student_enrollment')}}</label>
                                    <input type="number" class="form-control" id="max_student_enrollment"
                                           name="max_student_enrollment"
                                           value="{{ $edit ? $batch->max_student_enrollment : old('max_student_enrollment') }}"
                                           placeholder="{{ __('admin.batches.max_student_enrollment')}}">
                                </div>
                            </div>

                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="start_date">{{ __('admin.batches.start_date')}}</label>
                                    <input type="text" class="flat-date flat-date-custom-bg" id="start_date"
                                           name="start_date"
                                           value="{{ $edit ? $batch->start_date : old('start_date') }}"
                                    >
                                </div>
                            </div>

                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="end_date">{{ __('admin.batches.end_date')}}</label>
                                    <input type="date" class="flat-date flat-date-custom-bg" id="end_date"
                                           name="end_date"
                                           value="{{ $edit ? $batch->end_date : old('end_date') }}"
                                    >
                                </div>
                            </div>

                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="start_time">{{ __('admin.batches.start_time')}}</label>
                                    <input type="time" class="flat-time flat-time-custom-bg" id="start_time"
                                           name="start_time"
                                           value="{{ $edit ? $batch->start_time : old('start_time') }}"
                                    >
                                </div>
                            </div>

                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="end_time">{{ __('admin.batches.end_time')}}</label>
                                    <input type="time" class="flat-time flat-time-custom-bg" id="end_time"
                                           name="end_time"
                                           value="{{ $edit ? $batch->end_time : old('end_time') }}">
                                </div>
                            </div>


                            <div class="col-sm-12 text-right">
                                <button type="submit"
                                        class="btn btn-success">{{ $edit ? __('admin.common.update') : __('admin.common.add')}}</button>
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
        .flat-date-custom-bg, .flat-time-custom-bg{
            background-color: #fafdff !important;
        }
    </style>
@endpush

@push('js')
    <script>
        const EDIT = !!'{{$edit}}';

        const editAddForm = $('.edit-add-form');
        editAddForm.validate({
            rules: {
                title: {
                    required: true,
                },
                institute_id: {
                    required: true
                },
                course_id: {
                    required: true
                },
                code: {
                    required: true,
                    number: true,
                    min: 1,
                },
                max_student_enrollment: {
                    required: true,
                    number: true,
                    min: 1,
                },
                start_date: {
                    required: true,
                },
                end_date: {
                    required: true,
                },
                start_time: {
                    required: true,
                },
                end_time: {
                    required: true,
                },

            },
            messages: {
                start_date: {
                  min: "Not valid",
                },
                end_date: {
                    min: "batch end date should be after batch start date",
                }
            },
            submitHandler: function (htmlForm) {
                $('.overlay').show();
                htmlForm.submit();
            }
        });


        $('#institute_id').on('change', function () {
            let id = $(this).val();
            $('#course_id').empty();
            $('#course_id').append(`<option value="0" disabled selected>Processing...</option>`);
            $.ajax({
                type: 'GET',
                url: '/getCourses/' + id,
                success: function (response) {
                    $('#course_id').empty();
                    $('#course_id').append(`<option value=""></option>`);
                    response.forEach(element => {
                        $('#course_id').append(`<option value="${element['id']}">${element['title']}</option>`);
                    });
                }
            });
        });


    </script>
@endpush


