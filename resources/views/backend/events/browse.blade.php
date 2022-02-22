@extends('master::layouts.master')

@section('title')
    {{ __('admin.event.list') }}
@endsection


@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header text-primary custom-bg-gradient-info">
                        <h3 class="card-title font-weight-bold">{{ __('admin.event.list') }}</h3>

                        <div class="card-tools">
                            @can('create', \App\Models\TrainingCenter::class)
                                <a href="{{route('admin.events.create')}}"
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
                url: '{{ route('admin.events.datatable') }}',
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
                        title: "{{ __('admin.event.caption') }}",
                        data: "caption",
                        name: "events.caption"
                    },
                    {
                        title: "{{ __('admin.event.date') }}",
                        data: "event_date",
                        name: "events.date"
                    },

                    {
                        title: "{{ __('admin.event.institute_title') }}",
                        data: "institute_title",
                        name: "institutes.title",
                        visible: false,
                    },

                    {
                        title: "{{ __('admin.event.image') }}",
                        data: "event_image",
                        name: "events.image",
                        visible: true,
                    },
                    {
                        title: "{{ __('admin.event.created_by') }}",
                        data: "user_created_by",
                        name: "events.created_by",
                        visible: false,
                    },
                    {
                        title: "{{ __('admin.event.created_date') }}",
                        data: "event_created_at",
                        name: "events.created_at",
                        visible: false,
                        searchable: false,
                    },

                    {
                        title: "{{ __('admin.event.update_date') }}",
                        data: "event_updated_at",
                        name: "events.updated_at",
                        visible: false,
                        searchable: false,
                    },
                    {
                        title: "{{ __('admin.common.action') }}",
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


