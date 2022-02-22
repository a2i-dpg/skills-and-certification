@extends('master::layouts.master')

@section('title')
    {{ __('admin.routine.list') }}
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header text-primary custom-bg-gradient-info">
                        <h3 class="card-title font-weight-bold">{{__('admin.routine.list')}}</h3>

                        <div class="card-tools">
                            @can('viewDailyRoutine', \App\Models\Routine::class)
                                <a href="{{route('admin.daily-routine')}}"
                                   class="btn btn-sm btn-outline-primary btn-rounded">
                                    <i class="fas fa-eye"></i> {{__('admin.daily_routine.view_daily_routine')}}
                                </a>
                            @endcan

                            @can('create', \App\Models\Routine::class)
                                <a href="{{route('admin.routines.create')}}"
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
                url: '{{route('admin.routines.datatable')}}',
                order: [[2, "desc"]],
                columns: [
                    {
                        title: '{{__('admin.routine.sl')}}',
                        data: null,
                        defaultContent: "SL#",
                        searchable: false,
                        orderable: false,
                        visible: true,
                    },

                    {
                        title: "{{__('admin.routine.training_center')}}",
                        data: "training_center.title",
                        name: "routines.training_center_id"
                    },
                    {
                        title: "{{__('admin.routine.batch_title')}}",
                        data: "batch.title",
                        name: "routines.batch_id"
                    },
                    {
                        title: "{{__('admin.routine.date')}}",
                        data: "date",
                        name: "routines.date"
                    },
                    {
                        title: "{{__('admin.routine.action')}}",
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


