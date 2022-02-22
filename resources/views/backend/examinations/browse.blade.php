@extends('master::layouts.master')

@section('title')
    {{ __('admin.examination.list')}}
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header text-primary custom-bg-gradient-info">
                        <h3 class="card-title font-weight-bold">{{__('admin.examination.list')}}</h3>

                        <div class="card-tools">
                            @can('create', \App\Models\Examination::class)
                                <a href="{{route('admin.examinations.create')}}"
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
    <!-- Start Modal For All Selection-->
    <div class="modal fade" id="examStatus" tabindex="-1" role="dialog"
         aria-labelledby="addToBatchModalTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                    <form method="post" action="{{route('admin.examinations.status')}}">
                        <input type="hidden" name="id" id="id" value="">
                        @csrf
                        <div class="modal-header custom-bg-gradient-info">
                            <h4 class="modal-title" id="exampleModalLongTitle"><strong>Examination Status</strong></h4>
                            <button type="button" class="close" data-dismiss="modal"
                                    aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <h5 class="p-3"> Are you sure you want change examination status?</h5>
                        </div>
                        <div class="modal-footer text-center">
                            <button type="submit" class="btn btn-success ">Yes</button>
                            <button type="button" class="btn btn-danger" data-dismiss="modal">
                                No
                            </button>
                        </div>
                    </form>
            </div>
        </div>
    </div>
    <!-- End Modal For All Selection -->
    @include('utils.delete-confirm-modal')

@endsection
@push('css')
    <link rel="stylesheet" href="{{asset('/css/datatable-bundle.css')}}">
@endpush

@push('js')
    <script type="text/javascript" src="{{asset('/js/datatable-bundle.js')}}"></script>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        $(function () {
            let params = serverSideDatatableFactory({
                url: '{{route('admin.examinations.datatable')}}',
                order: [[2, "desc"]],
                columns: [
                    {
                        title: '{{__('admin.examination.sl')}}',
                        data: null,
                        defaultContent: "SL#",
                        searchable: false,
                        orderable: false,
                        visible: true,
                    },
                    {
                        title: "{{__('admin.examination.code')}}",
                        data: "code",
                        name: "examinations.code",
                        searchable: false,
                        orderable: false,
                        visible: true,
                    },
                    {
                        title: "{{__('admin.examination.examination_type')}}",
                        data: "examination_type.title",
                        name: "examinations.examination_type_id",
                        searchable: false,
                        orderable: false,
                        visible: true,
                    },
                    {
                        title: "{{__('admin.examination.training_center')}}",
                        data: "training_center.title",
                        name: "examinations.training_center_id",
                        searchable: false,
                        orderable: false,
                        visible: true,
                    },
                    {
                        title: "{{__('admin.examination.batch_title')}}",
                        data: "batch.title",
                        name: "examinations.batch_id",
                        searchable: false,
                        orderable: false,
                        visible: true,
                    },

                    {
                        title: "{{__('admin.examination.exam_details')}}",
                        data: "exam_details",
                        name: "examinations.exam_details",
                        searchable: false,
                        orderable: false,
                        visible: false,
                    },
                    {
                        title: "{{__('admin.examination.pass_mark')}}",
                        data: "pass_mark",
                        name: "examinations.pass_mark",
                        searchable: false,
                        orderable: false,
                        visible: false,
                    },
                    {
                        title: "{{__('admin.examination.total_mark')}}",
                        data: "total_mark",
                        name: "examinations.total_mark",
                        searchable: false,
                        orderable: false,
                        visible: false,
                    },
                    {
                        title: "{{__('admin.examination.status')}}",
                        data: "status",
                        name: "examinations.status"
                    },
                    {
                        title: "{{__('admin.examination.action')}}",
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

        $(document).ready(function () {
            $(document, 'td').on('click', '.examination_status', function (e) {
                e.preventDefault();
                console.log($(this).attr('data-action'))
                let href = $(this).attr('data-action');
                $('#id').val(href);
            });
        });
    </script>


@endpush


