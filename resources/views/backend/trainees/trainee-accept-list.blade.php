@php
    /** @var \App\Models\User $authUser */
    $authUser = \App\Helpers\Classes\AuthHelper::getAuthUser();
@endphp
@extends('master::layouts.master')

@section('title')
    {{ __('generic.accepted_trainee_list') }}
@endsection

@push('css')
    <style>
        .select2 {
            border-right: transparent !important;
        }

        .select2 .select2-selection {
            background: linear-gradient(180deg, #fafdff 0%, #ddf1ff 35%);
        }

        .select2 .select2-selection__arrow {
            background-image: -moz-linear-gradient(top, #1B67A8, #1B67A8);
            background-image: -ms-linear-gradient(top, #1B67A8, #1B67A8);
            background-image: -webkit-gradient(linear, left top, left bottom, color-stop(0%, #1B67A8), color-stop(100%, #1B67A8));
            background-image: -webkit-linear-gradient(top, #1B67A8, #1B67A8);
            background-image: -o-linear-gradient(top, #1B67A8, #1B67A8);
            background-image: linear-gradient(#1B67A8, #1B67A8);
            width: 40px;
            font-size: 1.3em;
            margin-top: -19px;
            padding: 19px;
            border-radius: 0 10px 10px 0;
            margin-right: -6px;
        }

        .select2 .select2-selection__placeholder {
            color: steelblue !important;
            font-weight: bolder;
        }

        .select2-selection__clear {
            margin-right: 34px !important;
        }

        .select2-container--bootstrap4 .select2-selection--single .select2-selection__arrow b {
            border-color: #fff transparent transparent;
            border-style: solid;
            border-width: 5px 4px 0;
        }
    </style>
@endpush

@push('js')
    <script>
        $('b[role="presentation"]').hide();
        $('.select2-selection__arrow').append('<i class="fa fa-times" style="color: #000000; font-size: 10px"></i>');
    </script>
@endpush

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between custom-bg-gradient-info">
                        <h3 class="card-title font-weight-bold text-primary">{{ __('Accepted Trainees')  }}</h3>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12">
                                <button id="add-to-batch-area" style="visibility: hidden" type="button"
                                        class="mb-3 btn btn-sm btn-rounded btn-primary"
                                        data-toggle="modal" data-target="#addToBatchModal">
                                    <i class="fas fa-plus-circle"></i> {{ __('admin.trainee_batches.add')  }}
                                </button>
                            </div>

                            <div class="col-md-12">
                                <div class="datatable-container">
                                    <table id="dataTable" class="table table-bordered table-striped dataTable">
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
@push('css')
    <link rel="stylesheet" href="{{asset('/css/datatable-bundle.css')}}">
@endpush

@push('js')
    <script type="text/javascript" src="{{asset('/js/datatable-bundle.js')}}"></script>
    <script>
        $(document).ready(function () {

            if (($('#institute_id').attr('type') == "hidden")) {
                $('#programme_id').parent().removeClass('col-md-3').addClass('col-md-2');
                $('#course_id').parent().removeClass('col-md-3').addClass('col-md-2');
                $('.date_filter').parent().removeClass('col-md-3').addClass('col-md-2');
            }
            let params = serverSideDatatableFactory({
                url: '{{ route('admin.trainee.acceptList.datatable') }}',
                // dom: 'Bfrtip',
                download_buttons: [
                    { extend: 'csv', text: 'Download Trainee List' }
                ],
                order: [[4, "DESC"]],
                serialNumberColumn: 0,
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
                        title: "Name",
                        data: "name",
                        name: "trainees.name"
                    },
                    {
                        title: "Application Date",
                        data: "application_date",
                        name: "trainee_course_enrolls.created_at"
                    },
                    {
                        title: "Institute",
                        data: "institute_name",
                        name: "institutes.title",
                        visible: false
                    },
                    {
                        title: "Batch",
                        data: "batch_title",
                        name: "batches.title",
                        defaultContent: '',
                        visible: true
                    },
                    {
                        title: "Branch Name",
                        data: "branches.title",
                        name: "branches.title",
                        defaultContent: '',
                        visible: false
                    },
                    {
                        title: "Training Center",
                        data: "training_center_title",
                        name: "training_centers.title",
                        defaultContent: '',
                        visible: false
                    },
                    {
                        title: "Course Name",
                        data: "courses.title",
                        name: "courses.title",
                        defaultContent: '',
                    },
                    {
                        title: "Action",
                        data: "action",
                        name: "action",
                        orderable: false,
                        searchable: false,
                        visible: true
                    },
                ],
            });

            params.ajax.data = d => {
                d.institute_id = $('#institute_id').val();
                d.branch_id = $('#branch_id').val();
                d.training_center_id = $('#training_center_id').val();
                d.course_id = $('#course_id').val();
                d.application_date = $('#date-filter').val();
            };

            let datatable = $('#dataTable').DataTable(params);

            $(document).on('change', '.select2-ajax-wizard', function () {
                datatable.draw();
            });
            $(document).on('change', '.flat-date', function () {
                datatable.draw();
            });

            $('#reset-btn').on('click', function () {
                $('#institute_id').val(null).trigger('change');
                $('#programme_id').val(null).trigger('change');
                $('#training_center_id').val(null).trigger('change');
                $('#course_id').val(null).trigger('change');
                $('#branch_id').val(null).trigger('change');
                $('.flat-date').val(null).change();
            })
            bindDatatableSearchOnPresEnterOnly(datatable);
        });

    </script>
@endpush
