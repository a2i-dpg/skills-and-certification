@extends('master::layouts.master')

@section('title')
    {{ __('admin.batch.certificate') }}
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between custom-bg-gradient-info">
                        <h3 class="card-title font-weight-bold text-primary"><b>{{ __('admin.certificate.batch_certificate')  }}</b>
                        </h3>
                    </div>
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
        let datatable = null;
        $(function () {
            let params = serverSideDatatableFactory({
                url: '{{ route('admin.batches.certificates.datatable') }}',
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
                   /* {
                        title: "{{ __('admin.trainee_batches.trainee_reg_no')  }}",
                        data: "trainee_registration_no",
                        name: "trainees.trainee_registration_no",
                    },*/
                    {
                        title: "{{ __('admin.certificate.course_title')  }}",
                        data: "course_title",
                        name: "courses.title"
                    },
                    {
                        title: "{{ __('admin.certificate.batch_title')  }}",
                        data: "batch_title",
                        name: "batches.title"
                    },

                    {
                        title: "{{ __('admin.certificate.trainee_amount')  }}",
                        data: "trainee",
                        name: "trainee"
                    },
                    {
                        title: "{{ __('admin.certificate.is_certificate')  }}",
                        data: "is_certificate",
                        name: "is_certificate",
                        orderable: false,
                        searchable: false,
                        visible: true
                    },

                    {
                        title: "{{ __('admin.common.action')  }}",
                        data: "action",
                        name: "action",
                        orderable: false,
                        searchable: false,
                        visible: true
                    },
                ]
            });
            datatable = $('#dataTable').DataTable(params);
            bindDatatableSearchOnPresEnterOnly(datatable);

        });

        const importtraineeForm = $('#import-trainee-form');
        importtraineeForm.validate({
            rules: {
                import_trainee_file: {
                    required: true,
                    extension: "xlsx|xls|xlsm|csv"
                }
            },
            messages: {
                import_trainee_file: {
                    required: "File is required",
                    extension: "File extension will be xlsx|xls|xlsm|csv"
                }
            }
        });
    </script>
@endpush
