@extends('master::layouts.master')

@section('title')
    {{ __('admin.institute.list') }}
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header text-primary custom-bg-gradient-info">
                        <h3 class="card-title font-weight-bold">{{ __('admin.institute.list') }}</h3>

                        <div class="card-tools">
                            @can('create', \App\Models\Institute::class)
                                <a href="{{route('admin.institutes.create')}}"
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
                url: '{{ route('admin.institutes.datatable') }}',
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
                        title: "{{ __('admin.institute.title') }}",
                        data: "title",
                        name: "title"
                    },
                    {
                        title: "{{ __('generic.email') }}",
                        data: "email",
                        name: "email"
                    },
                    {
                        title: "{{ __('generic.mobile') }}",
                        data: "mobile",
                        name: "mobile"
                    },
                    {
                        title: "{{ __('admin.institute.address') }}",
                        data: "address",
                        name: "address",
                        visible: false
                    },
                    {
                        title: "{{ __('generic.office_head_name') }}",
                        data: "office_head_name",
                        name: "office_head_name",
                        visible: false
                    },
                    {
                        title: "{{ __('generic.office_head_post') }}",
                        data: "office_head_post",
                        name: "office_head_post",
                        visible: false
                    },
                    {
                        title: "{{ __('generic.contact_person_name') }}",
                        data: "contact_person_name",
                        name: "contact_person_name",
                        visible: false
                    },
                    {
                        title: "{{ __('generic.contact_person_post') }}",
                        data: "contact_person_post",
                        name: "contact_person_post",
                        visible: false
                    },
                    {
                        title: "{{ __('generic.contact_person_email') }}",
                        data: "contact_person_email",
                        name: "contact_person_email",
                        visible: false
                    },
                    {
                        title: "{{ __('generic.contact_person_mobile') }}",
                        data: "contact_person_mobile",
                        name: "contact_person_mobile",
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
