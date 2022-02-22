@extends('master::layouts.master')

@section('title')
    {{ __('batches') }}
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card-header d-flex justify-content-between custom-bg-gradient-info">
                    <h3 class="card-title font-weight-bold text-primary">{{__('generic.final_result')}} of {{ $batch->title }}</h3>
                    <div>
                        <a href="{{route('admin.completed-batches')}}" class="btn btn-sm btn-rounded btn-outline-primary">
                            <i class="fas fa-backward"></i>{{__('admin.common.back')}}
                        </a>
                    </div>
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
            const batchID = "{{ $batch->id }}";

            let params = serverSideDatatableFactory({
                url: '{{ route('admin.trainee-final-result.datatable') }}',
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
                        title: "{{__('generic.trainee.title')}}",
                        data: "trainee_name",
                        name: "trainees.name",
                    },
                    {
                        title: "{{__('generic.batch.title')}}",
                        data: "batch_title",
                        name: "batches.title",
                    },
                    {
                        title: "{{__('generic.result')}}",
                        data: "result",
                        name: "result",
                        searchable: false,
                        orderable: false,
                        visible: true,
                        render: function (result) {
                            return result + ' %';
                        }
                    },

                ]
            });

            params.ajax.data = d => {
                d.batch_id = batchID
            };

            const datatable = $('#dataTable').DataTable(params);
            bindDatatableSearchOnPresEnterOnly(datatable);
        });
    </script>
@endpush
