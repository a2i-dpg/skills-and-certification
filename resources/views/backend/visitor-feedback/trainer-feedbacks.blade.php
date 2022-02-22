@extends('master::layouts.master')

@section('title')
    {{ __('admin.trainee_batches.trainer_feedbacks')  }}
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header text-primary custom-bg-gradient-info">
                        <h3 class="card-title font-weight-bold">{{ __('admin.trainee_batches.trainer_feedbacks')  }}</h3>

                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <div class="datatable-container">
                            <table id="dataTable" class="table table-bordered table-striped dataTable">
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('css')
    <link rel="stylesheet" href="{{asset('/css/datatable-bundle.css')}}">
    <style>

    </style>
@endpush

@push('js')
    <script type="text/javascript" src="{{asset('/js/datatable-bundle.js')}}"></script>

    <script>
        $(function () {
            let params = serverSideDatatableFactory({
                url: '{{route('admin.trainer-feedbacks.datatable')}}',
                //order: [[0, "desc"]],
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
                        title: "{{ __('generic.institute')  }}",
                        data: "institute_title",
                        name: "institutes.title",
                        visible: false
                    },
                    {
                        title: "{{ __('generic.course')  }}",
                        data: "course_title",
                        name: "courses.title",
                        visible: false
                    },
                    {
                        title: "{{ __('generic.training_center')  }}",
                        data: "training_center_title",
                        name: "training_centers.title",
                        visible: false
                    },
                    {
                        title: "{{ __('generic.batch')  }}",
                        data: "batch_title",
                        name: "batches.title",
                    },
                    {
                        title: "{{ __('admin.trainee_batches.name')  }}",
                        data: "trainee_name",
                        name: "trainees.name"
                    },
                    {
                        title: "{{ __('admin.trainee_batches.feedback')  }}",
                        data: "feedback",
                        name: "trainee_feedbacks.feedback"
                    },
                    {
                        title: "{{ __('generic.trainer')  }}",
                        data: "trainer_name",
                        name: "users.name"
                    },
                ]
            });

            const datatable = $('#dataTable').DataTable(params);
            bindDatatableSearchOnPresEnterOnly(datatable);
        });
    </script>
@endpush


