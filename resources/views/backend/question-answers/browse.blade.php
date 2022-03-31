@php

    $authUser = \App\Helpers\Classes\AuthHelper::getAuthUser();


@endphp

@extends('master::layouts.master')

@section('title')
    {{ __('admin.question_answer.list') }}
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header text-primary custom-bg-gradient-info">
                        <h3 class="card-title font-weight-bold">{{ __('admin.question_answer.list') }}</h3>
                        <div class="card-tools">
                            <a href="{{route('admin.question-answers.create')}}"
                               class="btn btn-sm btn-outline-primary btn-rounded">
                                <i class="fas fa-plus-circle"></i> {{__('admin.common.add')}}
                            </a>
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
                url: '{{ route('admin.question-answers.datatable') }}',
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
                        title: "{{ __('admin.question_answer.question') }}",
                        data: "question",
                        name: "question_answers.question",
                    },
                    {
                        title: "{{ __('admin.question_answer.answer') }}",
                        data: "answer",
                        name: "question_answers.answer",
                        visible: false,
                    },
                    {
                        title: "{{ __('admin.question_answer.institute_title') }}",
                        data: "institute_title",
                        name: "institutes.title",
                        visible: true,
                    },
                    {
                        title: "{{ __('admin.common.status') }}",
                        data: "row_status",
                        name: "question_answers.row_status",
                    },

                    {
                        title: "Action",
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
