@php
    $authUser = \App\Helpers\Classes\AuthHelper::getAuthUser();
@endphp

@extends('master::layouts.master')

@section('title')
    {{ __('admin.header.list') }}
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header text-primary custom-bg-gradient-info">
                        <h3 class="card-title font-weight-bold">{{ __('generic.headers.list') }}</h3>

                        <div class="card-tools">
                            @can('create', \App\Models\Institute::class)
                                <a href="{{route('admin.headers.create')}}"
                                   class="btn btn-sm btn-outline-primary btn-rounded">
                                    <i class="fas fa-plus-circle"></i> {{__('admin.common.add')}}
                                </a>
                            @endcan
                        </div>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <div class="datatable-container">
                            <table id="dataTable" class="table table-bordered table-striped dataTable compact">
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
                url: '{{ route('admin.headers.datatable') }}',
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
                        title: "{{ __('generic.header') }}",
                        data: "title",
                        name: "title"
                    },
                    {
                        title: "{{ __('generic.institute') }}",
                        data: "institute_title",
                        name: "institute_title",
                        visible: {{!$authUser->isInstituteLevelUser()}}
                    },
                    {
                        title: "{{ __('generic.target') }}",
                        data: "target",
                        name: "target"
                    },
                    {
                        title: "{{ __('generic.url') }}",
                        data: "url",
                        name: "url",
                        visible: false
                    },
                    {
                        title: "{{ __('generic.route') }}",
                        data: "route",
                        name: "route",
                        visible: false
                    },
                    {
                        title: "{{ __('generic.header_order') }}",
                        data: "order",
                        name: "order",
                        visible: false
                    },
                    {
                        title: "{{ __('generic.row_status') }}",
                        data: "row_status",
                        name: "row_status",
                        visible: false
                    },
                    {
                        title: "{{ __('admin.common.action') }}",
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
