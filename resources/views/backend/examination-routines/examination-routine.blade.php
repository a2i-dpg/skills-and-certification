@php
    $edit = false;
    /** @var \App\Models\User $authUser */
    $authUser = \App\Helpers\Classes\AuthHelper::getAuthUser();
@endphp

@extends('master::layouts.master')

@section('title')
    {{__('admin.examination_routine.list')}}
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header text-primary custom-bg-gradient-info">
                        <h3 class="card-title font-weight-bold">{{__('admin.examination_routine.view_examination_routine')}}</h3>
                        <div class="card-tools">
                            <a href="{{route('admin.examination-routines.index')}}"
                               class="btn btn-sm btn-outline-primary btn-rounded">
                                <i class="fas fa-backward"></i> {{__('admin.common.back')}}
                            </a>
                        </div>

                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">

                        <div class="row">
                            <div class="col-lg-3">
                                <div class="form-group">
                                    <label for="training_center_id">
                                        {{__('admin.routine.training_center')}}
                                        <span class="required"></span>
                                    </label>
                                    <select class="form-control select2-ajax-wizard" required
                                            name="training_center_id"
                                            id="training_center_id"
                                            data-model="{{base64_encode(App\Models\TrainingCenter::class)}}"
                                            data-label-fields="{title}"
                                            data-dependent-fields="#batch_id"
                                            data-filters="{{json_encode(['institute_id' => $authUser->institute_id])}}"
                                            data-placeholder="{{ __('generic.select_placeholder') }}"
                                    >
                                    </select>

                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="form-group">
                                    <label for="batch_id">
                                        {{__('admin.routine.batch_title')}}
                                        <span class="required"></span>
                                    </label>

                                    <select class="form-control select2-ajax-wizard" required
                                            name="batch_id"
                                            id="batch_id"
                                            data-model="{{base64_encode(App\Models\Batch::class)}}"
                                            data-label-fields="{title}"
                                            data-depend-on-optional="training_center_id"
                                            data-filters="{{json_encode(['institute_id' => $authUser->institute_id])}}"
                                            data-placeholder="{{ __('generic.select_placeholder') }}"
                                    >
                                    </select>

                                </div>
                            </div>

                            <div class="col-lg-3">
                                <div class="form-group">
                                    <label for="user_id">
                                        {{__('admin.examination_routine.examination')}}
                                        <span class="required"></span>
                                    </label>

                                    <select class="form-control select2-ajax-wizard"
                                            name="examination_id"
                                            id="examination_id"
                                            data-model="{{base64_encode(App\Models\Examination::class)}}"
                                            data-label-fields="{code}"
                                            data-filters="{{json_encode(['institute_id' => $authUser->institute_id])}}"
                                            data-placeholder="{{ __('generic.select_placeholder') }}"
                                    >
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="form-group">
                                    <label for="trainer_id" style="visibility: hidden">
                                        xsd
                                        <span class="required"></span>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="col">
                            <div class="overlay" style="display: none">
                                <i class="fas fa-2x fa-sync-alt fa-spin"></i>
                            </div>
                        </div>
                        <div class="datatable-container">
                            <table id="dataTable" class="table table-bordered table-striped dataTable">
                                <thead>
                                <tr>
                                    <th class="text-center">{{__('admin.routine.date')}}</th>
                                    <th class="text-center">{{__('admin.examination.code')}}</th>
                                    <th class="text-center">{{__('admin.examination_routine.start_time')}}</th>
                                    <th class="text-center">{{__('admin.examination_routine.end_time')}}</th>
                                </tr>
                                </thead>
                                <tbody id="routine">
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('utils.delete-confirm-modal')

@endsection
@push('css')
    <link rel="stylesheet" href="{{asset('/css/datatable-bundle.css')}}">
    <style>
        .select20 { width: 100%}
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
        table.table-striped.dataTable tbody tr td {
            text-align: center;
        }
    </style>

@endpush

@push('js')
    <script type="text/javascript" src="{{asset('/js/datatable-bundle.js')}}"></script>
    <script>
        $(function () {
            $('.select20').select2();
        })

        function timeConvert (time) {
            // Check correct time format and split into components
            time = time.toString ().match (/^([01]\d|2[0-3])(:)([0-5]\d)(:[0-5]\d)?$/) || [time];

            if (time.length > 1) { // If time format correct
                time = time.slice (1);  // Remove full string match value
                time[5] = +time[0] < 12 ? 'AM' : 'PM'; // Set AM/PM
                time[0] = +time[0] % 12 || 12; // Adjust hours
            }
            return time.join (''); // return adjusted time or original string
        }
        const template = function (item) {

            let examDate = new Date(item.examinationRoutine_date);
            examDate = new Intl.DateTimeFormat('en-us', {
                day: '2-digit',
                month: 'long',
                year: 'numeric',
            }).format(examDate)

            let html ='<tr><td>'+ examDate +'</td><td>'+item.examination_code+'</td><td>'+timeConvert(item.start_time)+'</td><td>'+timeConvert(item.end_time)+'</td></tr>';
            return html;
        };
        const searchForm = $('.edit-add-form');
        searchForm.validate({
            rules: {
                batch_id: {
                    required: true,
                },
                training_center_id: {
                    required: true,
                },
                submitHandler: function (htmlForm) {
                    $('.overlay').show();
                    htmlForm.submit();
                }
            }
        });

        const searchAPI = function ({model, columns}) {
            return function (url, filters = {}) {
                return $.ajax({
                    url: url,
                    type: "POST",
                    data: {
                        _token: '{{csrf_token()}}',
                        resource: {
                            model: model,
                            columns: columns,
                            paginate: true,
                            page: 1,
                            per_page: 16,
                            filters,
                        }
                    }
                }).done(function (response) {
                    return response;
                });
            };
        };

        let baseUrl = '{{route('web-api.model-resources')}}';
        const skillVideoFetch = searchAPI({
            model: "{{base64_encode(\App\Models\ExaminationRoutineDetail::class)}}",
            columns: 'id|institute_id|start_time|end_time|examination.code|examinationRoutine.batch_id|examinationRoutine.training_center_id|examinationRoutine.date|examination_id'
        });

        function routineSearch(url = baseUrl) {
            $('.overlay').show();
            let training_center = $('#training_center_id').val();
            let batch = $('#batch_id').val();
            let examination_id = $('#examination_id').val();
            const filters = {};
            if (training_center?.toString()?.length) {
                filters['examinationRoutine.training_center_id'] = training_center;
            }
            if (batch?.toString()?.length) {
                filters['examinationRoutine.batch_id'] = batch;
            }
            if (examination_id?.toString()?.length) {
                filters['examination_id'] = examination_id;
            }
            skillVideoFetch(url, filters)?.then(function (response) {
                $('.overlay').hide();
                window.scrollTo(0, 0);
                let html = '';
                if (response?.data?.data.length <= 0) {
                    html += '<div class="text-center mt-5"></i><div class="text-danger h3">No data found!</div>';
                }
                $.each(response.data?.data, function (i, item) {
                    html += template(item);

                });
                $('#routine').html(html);
            });
        }

        $(document).ready(function(){
            routineSearch();
            $('#training_center_id').on('keyup change',function (){
                routineSearch();
            });
            $('#batch_id').on('keyup change',function (){
                routineSearch();
            });

            $('#examination_id').on('keyup change',function (){
                routineSearch();
            });

        });
    </script>

    <script type="text/javascript">
        function PrintDiv() {
            var divToPrint = document.getElementsByClassName('datatable-container')[0];
            var popupWin = window.open('invoice', '_blank', 'width=100%,height=auto,location=no,left=200px');
            popupWin.document.open();
            popupWin.document.write('<html><head><link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet" type="text/css"><link href="Assets/css/bootstrap.min.css" rel="stylesheet" type="text/css"></head><body onload="window.print()">' + divToPrint.innerHTML + '</body></html>');
            popupWin.document.close();
            window.close();
        }
    </script>
@endpush



