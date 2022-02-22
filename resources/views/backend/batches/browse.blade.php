@extends('master::layouts.master')

@section('title')
    {{ __('Batches List') }}
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between custom-bg-gradient-info">
                        <h3 class="card-title font-weight-bold text-primary">{{__('admin.batch.list')}}</h3>
                        <div class="card-tools">
                            @can('create', \App\Models\Batch::class)
                                <a href="{{route('admin.batches.create')}}" class="btn btn-sm btn-rounded btn-primary">
                                    <i class="fas fa-plus-circle"></i> {{__('admin.common.add')}}
                                </a>
                            @endcan
                        </div>
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
    @include('utils.delete-confirm-modal')

@endsection
@push('css')
    <link rel="stylesheet" href="{{asset('/css/datatable-bundle.css')}}">
@endpush

@push('js')
    <script type="text/javascript" src="{{asset('/js/datatable-bundle.js')}}"></script>
    <script>
        $(function () {
            let params = serverSideDatatableFactory({
                url: '{{ route('admin.batches.datatable') }}',
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
                        title: "{{__('generic.institute')}}",
                        data: "institute_title",
                        name: "institutes.title",
                        visible: !'{{ \App\Helpers\Classes\AuthHelper::getAuthUser()->isUserBelongsToInstitute() }}'
                    },
                    {
                        title: "{{__('admin.batch.title')}}",
                        data: "title",
                        name: "batches.title",
                    },
                    {
                        title: "{{__('admin.batch.course_title')}}",
                        data: "courses.title",
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
                        title: "{{__('generic.change_batch_status')}}",
                        data: "change_batch_status",
                        name: "change_batch_status",
                        orderable: false,
                        searchable: false,
                        visible: true
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

            $(document, 'td').on('click', '.delete', function (e) {
                $('#delete_form')[0].action = $(this).data('action');
                $('#delete_modal').modal('show');
            });
        });
    </script>
@endpush
