@php
    $currentInstitute = app('currentInstitute');
    $layout = 'master::layouts.front-end';

@endphp
@extends($layout)

@section('title')
{{$siteSettingInfo->site_title}} :: {{__('generic.routines')}}
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row trainee-profile" id="trainee-profile">
            <div class="col-md-3 mt-2">
                <div class="user-details card mb-3">
                    <div
                        class="card-header custom-bg-gradient-info">
                        <div class="card-title float-left font-weight-bold text-primary">{{__('generic.details')}}</div>
                    </div>
                    <div class="card-body">
                        <div class="user-image text-center">
                            <img
                                src="{{ $trainee->profile_pic ? asset('storage/'. $trainee->profile_pic ) : "http://via.placeholder.com/640x360" }}"
                                height="100" width="100" class="rounded-circle" alt="Trainee profile picture">
                        </div>
                        <div class="d-flex justify-content-center user-info normal-line-height mt-3">
                            <p class="text-center ml-2">{{ optional($trainee)->name }}</p>
                        </div>
                    </div>
                </div>

                <div class="user-contact card bg-white mb-3">
                    <div class="card-header">
                        <div class="row">
                            <div class="text-center">
                                <i class="fa fa-phone"></i>
                            </div>
                            <p class="medium-text ml-2 text-primary">{{ __('generic.mobile')  }}</p>
                        </div>
                        <div class="phone">
                            <p class="medium-text">{{ $trainee->mobile ? $trainee->mobile : "N/A" }}</p>
                        </div>
                    </div>
                    <div class="card-header">
                        <div class="row">
                            <div class="text-center">
                                <i class="fa fa-envelope"></i>
                            </div>
                            <p class="medium-text ml-2 text-primary">{{ __('generic.email') }}</p>
                        </div>
                        <div class="email">
                            <p class="medium-text">{{ $trainee->email ?? "N/A"}}</p>
                        </div>
                    </div>
                    <div class="card-header">
                        <div class="row">
                            <div class="text-center">
                                <i class="fas fa-edit"></i>
                            </div>
                            <p class="medium-text ml-2 text-primary">{{__('generic.signature')}}</p>
                        </div>
                        <div class="email">
                            <img
                                src="{{ $trainee->student_signature_pic ? asset('storage/'. $trainee->student_signature_pic ) : "http://via.placeholder.com/640x360" }}"
                                height="40" alt="Trainee profile picture">
                        </div>
                    </div>
                </div>

            </div>

            <div class="col-md-9 mt-2">
                <div class="card bg-white">
                    <div
                        class="card-header custom-bg-gradient-info">
                        <div class="card-title float-left font-weight-bold text-primary">{{$courseEnrollInfo->course->title. ' -> ' . $courseEnrollInfo->batch->title}}</div>
                        <div class="trainee-access-key float-right d-inline-flex">
                            <p class="label-text font-weight-bold">&nbsp;</p>
                            <div class="font-weight-bold">
                                &nbsp;
                            </div>
                        </div>
                    </div>

                    <div class="col">
                        <div class="overlay" style="display: none">
                            <i class="fas fa-2x fa-sync-alt fa-spin"></i>
                        </div>
                    </div>

                    <div class="card-body">
                        <div class="datatable-container">
                            <table id="dataTable" class="table table-bordered table-striped dataTable">
                            </table>
                        </div>
                    </div>
                </div>

                <div class="card bg-white">
                    <div
                        class="card-header custom-bg-gradient-info">
                        <div class="card-title float-left font-weight-bold text-primary">{{__('generic.exam_routine')}}</div>
                    </div>

                    <div class="col">
                        <div class="overlay" style="display: none">
                            <i class="fas fa-2x fa-sync-alt fa-spin"></i>
                        </div>
                    </div>

                    <div class="card-body">
                        <div class="datatable-container">
                            <table id="exam-routine-dataTable" class="table table-bordered table-striped dataTable">
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <!-----------modal Start----------->
    <div class="modal modal-danger fade" tabindex="-1" id="pay-now-modal" role="dialog" data-backdrop="static">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header custom-bg-gradient-info">
                    <h4 class="modal-title">
                        <i class="fas fa-exclamation-triangle"></i></i> {{ __('Do you want to pay now?') }}
                    </h4>
                    <button type="button" class="close" data-dismiss="modal"
                            aria-label="{{ __('voyager::generic.close') }}">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-right"
                            data-dismiss="modal">{{ __('Cancel') }}</button>
                    <form action="#" id="pay-now-form" method="POST">
                        {{--{{ method_field("DELETE") }}--}}
                        {{ csrf_field() }}
                        <input type="submit" class="btn btn-danger pull-right"
                               value="{{ __('Confirm') }}">
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-----------modal End------------->

@endsection

@push('css')
    <link rel="stylesheet" href="{{asset('/css/datatable-bundle.css')}}">
@endpush

@push('js')
    <script type="text/javascript" src="{{asset('/js/datatable-bundle.js')}}"></script>
    <script>

        $(function () {
            let params = serverSideDatatableFactory({
                url: '{{ route('frontend.class-routine-datatable',$course_enroll_id) }}',
                order: [[2, "asc"]],
                columns: [
                    {
                        title: "SL#",
                        data: null,
                        defaultContent: "SL#",
                        searchable: false,
                        orderable: false,
                        visible: true,
                    },
                    {
                        title: "Class Date",
                        data: "class_date",
                        name: "routines.date",
                    },
                    {
                        title: "Class Name",
                        data: "class_name",
                        name: "routine_slots.class",
                    },
                    {
                        title: "Start Time",
                        data: "start_time",
                        name: "routine_slots.start_time",
                    },
                    {
                        title: "End Time",
                        data: "end_time",
                        name: "routine_slots.end_time",
                    },
                    {
                        title: "Trainer",
                        data: "trainer_name"
                    },

                ]
            });
            const datatable = $('#dataTable').DataTable(params);
            bindDatatableSearchOnPresEnterOnly(datatable);

            let examRoutineDataTableParams = serverSideDatatableFactory({
                url: '{{ route('frontend.exam-routine-datatable',$course_enroll_id) }}',
                order: [[2, "asc"]],
                columns: [
                    {
                        title: "SL#",
                        data: null,
                        defaultContent: "SL#",
                        searchable: false,
                        orderable: false,
                        visible: true,
                    },
                    {
                        title: "Institute",
                        data: "institute_title",
                        name: "institutes.title",
                        visible: false,
                    },
                    {
                        title: "Training Center",
                        data: "training_center_title",
                        name: "training_centers.title",
                        visible: false,
                    },
                    {
                        title: "Course",
                        data: "course_title",
                        name: "courses.title",
                        visible: false,
                    },
                    {
                        title: "Batch",
                        data: "batch_title",
                        name: "batches.title",
                        visible: false,
                    },
                    {
                        title: "Exam Type",
                        data: "exam_type",
                        name: "examination_types.title",
                    },
                    {
                        title: "Date",
                        data: "date",
                        name: "examination_routines.date",
                    },
                    {
                        title: "Start Time",
                        data: "start_time",
                        name: "examination_routine_details.start_time",
                    },
                    {
                        title: "End Time",
                        data: "end_time",
                        name: "examination_routine_details.end_time",
                    },
                    {
                        title: "Trainer",
                        data: "trainer_name"
                    },
                    {
                        title: "Total Mark",
                        data: "total_mark",
                        name: 'examinations.total_mark'
                    },
                    {
                        title: "Pass Mark",
                        data: "pass_mark",
                        name: 'examinations.pass_mark'
                    },
                    {
                        title: "Achieved Mark",
                        data: "achieved_marks",
                        name: 'examination_results.achieved_marks'
                    },
                    {
                        title: "Status",
                        data: "exam_status",
                        name: 'examinations.status'
                    },
                ]
            });
            const examRoutineDatatable = $('#exam-routine-dataTable').DataTable(examRoutineDataTableParams);
            bindDatatableSearchOnPresEnterOnly(examRoutineDatatable);

            $(document, 'td').on('click', '.pay-now', function (e) {
                $('#pay-now-form')[0].action = $(this).data('action');
                $('#pay-now-modal').modal('show');
            });

          $(document," td ").on('click','.trainee-certificate-generation',function (e){
                //e.preventDefault();
                $('.overlay').show();
            });


        });

    </script>
@endpush


