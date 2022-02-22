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
                        <h3 class="card-title font-weight-bold">{{__('admin.examination_routine.list')}}</h3>

                        <div class="card-tools">

                            @can('viewAny', \App\Models\ExaminationRoutine::class)
                                <a href="{{route('admin.examination-routine')}}"
                                   class="btn btn-sm btn-outline-primary btn-rounded">
                                    <i class="fas fa-eye"></i> {{__('admin.examination_routine.view_examination_routine')}}
                                </a>
                            @endcan

                            @can('create', \App\Models\ExaminationRoutine::class)
                                <a href="{{route('admin.examination-routines.create')}}"
                                   class="btn btn-sm btn-outline-primary btn-rounded">
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
                url: '{{route('admin.examination-routines.datatable')}}',
                order: [[2, "desc"]],
                columns: [
                    {
                        title: '{{__('admin.examination_routine.sl')}}',
                        data: null,
                        defaultContent: "SL#",
                        searchable: false,
                        orderable: false,
                        visible: true,
                    },
                    {
                        title: "{{__('admin.examination_routine.training_center')}}",
                        data: "training_center.title",
                        name: "examination_routines.training_center_id"
                    },
                    {
                        title: "{{__('admin.examination_routine.batch_title')}}",
                        data: "batch.title",
                        name: "examination_routines.batch_id"
                    },
                    /*{
                        title: "{{__('admin.examination_routine.day')}}",
                        data: "day",
                        name: "examination_routines.day"
                    },*/
                    {
                        title: "{{__('admin.examination_routine.date')}}",
                        data: "date",
                        name: "examination_routines.date"
                    },
                    {
                        title: "{{__('admin.examination_routine.action')}}",
                        data: "action",
                        orderable: false,
                        searchable: false,
                        visible: true
                    }
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


