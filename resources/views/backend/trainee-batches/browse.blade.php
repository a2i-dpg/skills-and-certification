@extends('master::layouts.master')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between custom-bg-gradient-info">
                        <h3 class="card-title font-weight-bold text-primary"><b>{{$batch->title}}</b> - {{ __('admin.trainee_batches.index')  }}
                        </h3>
                        <div class="card-tools">
                            <a href="{{route('admin.batches.index')}}"
                               class="btn btn-sm btn-rounded btn-outline-primary">
                                <i class="fas fa-backward"></i> {{__('admin.common.back')}}
                            </a>
                        </div>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
{{--
                        @if($batch->batch_status == \App\Models\Batch::BATCH_STATUS_COMPLETE)
                            <form class="row bulk-import-trainee" id="import-trainee-form" method="post"
                                  enctype="multipart/form-data">
                                @csrf
                                <div class="col-md-6 py-2 mb-2">
                                    <div class="form-group">
                                        <label for="import_trainee_file" class="form-label">{{ __('admin.trainee_batches.import_trainee')  }}</label>
                                        <input class="form-control form-control-lg" id="import_trainee" type="file"
                                               name="import_trainee_file"/>
                                    </div>
                                </div>
                                <div class="col-md-3 py-2 mb-2">
                                    <div class="form-group row">
                                        <label for="import_trainee" class="form-label">&nbsp;</label>
                                        <button class="form-control form-control-lg bg-blue" id="import_trainee_btn">
                                            {{ __('admin.trainee_batches.import_now')  }}
                                        </button>
                                    </div>
                                </div>
                                <div class="col-md-3 py-2 mb-2 text-center">
                                    <div class="form-group row">
                                        <label for="Download_demo" class="form-label">&nbsp;</label>
                                        <a href="{{asset('/assets/demoExcelFormat/backlog-demo.xlsx')}} "
                                           class="form-control form-control-lg bg-info"> {{ __('admin.trainee_batches.download_demo')  }}</a>
                                    </div>
                                </div>

                            </form>

                            <div class="col-md-6 py-2 mb-2" id="validation-error-div" style="display: none">
                                <p>Validation Error:</p>
                                <ul id="validation-error-list"></ul>
                            </div>
                        @endif
--}}


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
                url: '{{ route('admin.batches.trainees.datatable', $batch->id) }}',
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
                        title: "{{ __('admin.trainee_batches.name')  }}",
                        data: "trainee_name",
                        name: "trainees.title"
                    },
                    {
                        title: "{{ __('admin.trainee_batches.enrollment_date')  }}",
                        data: "enrollment_date",
                        name: "trainee_course_enrolls.created_at",
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
        $(document).ready(function () {
            $("#import-trainee-form").on("submit", function (event) {
                event.preventDefault();
                let formData = new FormData($(this)[0]);
                $.ajax({
                    url: "{{route('admin.batches.trainees-import',$batch->id)}}",
                    type: "POST",
                    data: formData,
                    async: false,
                    cache: false,
                    contentType: false,
                    processData: false,
                    success: function (res) {
                        if (res.code === 422) {
                            let li = "";
                            $.each(res.errors, function (i, error) {
                                li += "<li class='text-danger'>" + error + "</li>";
                            });
                            $("#validation-error-list").html(li);
                            $("#validation-error-div").css("display", "block");
                        } else if (res.code === 200) {
                            window.location.reload();
                        }
                    }

                })
            })
        })
    </script>
@endpush
