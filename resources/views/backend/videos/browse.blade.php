@extends('master::layouts.master')

@section('title')
    {{ __('admin.videos.list') }}
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header text-primary custom-bg-gradient-info">
                        <h3 class="card-title font-weight-bold"> {{ __('admin.videos.list') }}</h3>
                        <div class="card-tools">
                            @can('create', App\Models\Video::class)
                                <a href="{{route('admin.videos.create')}}"
                                   class="btn btn-sm btn-outline-primary btn-rounded">
                                    <i class="fas fa-plus-circle"></i>{{__('admin.common.add')}}
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
                url: '{{ route('admin.videos.datatable') }}',
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
                        title: " {{ __('admin.videos.title') }}",
                        data: "title",
                        name: "title"
                    },
                    {
                        title: " {{ __('admin.videos.institute_title') }}",
                        data: "institute_title",
                        name: "institutes.title",
                        visible: false,
                    },
                    {
                        title: " {{ __('admin.videos.video_category') }}",
                        data: "video_category_title",
                        name: "video_categories.title",
                        visible: false,
                    },
                    {
                        title: " {{ __('admin.common.status') }}",
                        data: "row_status",
                        name: "videos.row_status",
                        visible: false,
                    },
                    {
                        title: "{{ __('admin.videos.description') }}",
                        data: "description",
                        name: "videos.description",
                        visible: false,
                    },

                    {
                        title: " {{ __('admin.common.action') }}",
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
