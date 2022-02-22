@extends('master::layouts.master')

@section('content')
    <div class="container-fluid">
        <div class="card">
            <div class="card-header d-flex justify-content-between custom-bg-gradient-info">
                <h3 class="card-title font-weight-bold text-primary">Batch</h3>
                <div>
                    <a href="{{route('admin.batches.edit', [$batch->id])}}" class="btn btn-sm btn-rounded btn-primary">
                        <i class="fas fa-plus-circle"></i> {{ __('admin.batch.edit')  }}
                    </a>
                </div>
            </div>
            <div class="row card-body">

                <div class="col-md-6 custom-view-box">
                    <p class="label-text">{{ __('admin.batch.title')  }}</p>
                    <div class="input-box">
                        {{ $batch->title }}
                    </div>
                </div>

                <div class="col-md-6 custom-view-box">
                    <p class="label-text">{{ __('admin.batch.institute_title')  }}</p>
                    <div class="input-box">
                        {{ $batch->institute->title }}
                    </div>
                </div>

                <div class="col-md-6 custom-view-box">
                    <p class="label-text">{{ __('admin.batch.course_title')  }}</p>
                    <div class="input-box">
                        {{ $batch->course->title }}
                    </div>
                </div>

                <div class="col-md-6 custom-view-box">
                    <p class="label-text">{{ __('admin.batch.code')  }}</p>
                    <div class="input-box">
                        {{ $batch->code }}
                    </div>
                </div>

                <div class="col-md-6 custom-view-box">
                    <p class="label-text">{{ __('admin.batch.max_student_enrollment')  }}</p>
                    <div class="input-box">
                        {{ $batch->max_student_enrollment }}
                    </div>
                </div>

                <div class="col-md-6 custom-view-box">
                    <p class="label-text">{{ __('admin.batch.start_date')  }}</p>
                    <div class="input-box">
                        {{ date("d M, Y", strtotime($batch->start_date)) }}
                    </div>
                </div>

                <div class="col-md-6 custom-view-box">
                    <p class="label-text">{{ __('admin.batch.end_date')  }}</p>
                    <div class="input-box">
                        {{ date("d M, Y", strtotime($batch->end_date)) }}
                    </div>
                </div>

                <div class="col-md-6 custom-view-box">
                    <p class="label-text">{{ __('admin.batch.start_time')  }}</p>
                    <div class="input-box">
                        {{ date("g:i A", strtotime($batch->start_time)) }}
                    </div>
                </div>

                <div class="col-md-6 custom-view-box">
                    <p class="label-text">{{ __('admin.batch.end_time')  }}</p>
                    <div class="input-box">
                        {{ date("g:i A", strtotime($batch->end_time)) }}
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection
