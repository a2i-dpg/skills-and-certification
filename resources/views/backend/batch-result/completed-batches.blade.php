@extends('master::layouts.master')

@section('title')
    {{ __('completed batches') }}
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card-header d-flex justify-content-between custom-bg-gradient-info">
                    <h3 class="card-title font-weight-bold text-primary">{{__('generic.completed-batch.list')}}</h3>
                </div>
                <div class="card">
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
@endpush

@push('js')
    <script type="text/javascript" src="{{asset('/js/datatable-bundle.js')}}"></script>
    <script>
        $(function () {
            let params = serverSideDatatableFactory({
                url: '{{ route('admin.completed-batches.datatable') }}',
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
                        title: "{{__('admin.batch.title')}}",
                        data: "title",
                        name: "batches.title",
                    },
                    {
                        title: "{{__('admin.batch.institute_title')}}",
                        data: "institute_title",
                        name: "institutes.title",
                        visible: !'{{ \App\Helpers\Classes\AuthHelper::getAuthUser()->isInstituteLevelUser() }}'
                    },
                    {
                        title: "{{__('admin.batch.course_title')}}",
                        data: "course_title",
                        name: "courses.title"
                    },
                    {
                        title: "{{__('admin.batch.batch_status')}}",
                        data: "batch_status",
                        name: "batches.batch_status",
                        orderable: false,
                        searchable: false,
                        visible: true
                    },
                    {
                        title: "{{ __('generic.total_trainee') }}",
                        data: 'total_trainee',
                        name: 'total_trainee',
                        orderable: false,
                        searchable: false,
                        visible: true,
                        render: function (data) {
                            if (data == null) {
                                return 0;
                            }

                            return data;
                        }
                    },
                    {
                        title: "{{__('admin.common.action')}}",
                        data: "action",
                        name: "action",
                        orderable: false,
                        searchable: false,
                        visible: true
                    },
                ]
            });
            const datatable = $('#dataTable').DataTable(params);
            bindDatatableSearchOnPresEnterOnly(datatable);
        });
    </script>
@endpush
