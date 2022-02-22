@php
    /** @var \App\Models\User $authUser */
    $authUser = \App\Helpers\Classes\AuthHelper::getAuthUser();
@endphp
@extends('master::layouts.master')

@section('title')
    {{ __('admin.trainee.list')  }}
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between custom-bg-gradient-info">
                        <h3 class="card-title font-weight-bold text-primary">  {{ __('admin.trainee.list')  }}</h3>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12">
                                <button id="add-to-organization-area" style="visibility: hidden; " type="button"
                                        class="mb-3 btn btn-sm btn-rounded btn-primary float-right"
                                        data-toggle="modal" data-target="#addToOrganizationModal">
                                    <i class="fas fa-plus-circle d-inline-block"></i>   {{ __('admin.trainee.add_to_organization')  }}
                                </button>
                            </div>

                            <div class="col-md-12">
                                <div class="datatable-container">
                                    <table id="dataTable" class="table table-bordered table-striped dataTable">
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('css')
    <style>
        .select2 {
            border-right: transparent !important;
        }

        .select2 .select2-selection {
            background: linear-gradient(180deg, #fafdff 0%, #ddf1ff 35%);
        }

        .select2 .select2-selection__arrow {
            background-image: -moz-linear-gradient(top, #1B67A8, #1B67A8);
            background-image: -ms-linear-gradient(top, #1B67A8, #1B67A8);
            background-image: -webkit-gradient(linear, left top, left bottom, color-stop(0%, #1B67A8), color-stop(100%, #1B67A8));
            background-image: -webkit-linear-gradient(top, #1B67A8, #1B67A8);
            background-image: -o-linear-gradient(top, #1B67A8, #1B67A8);
            background-image: linear-gradient(#1B67A8, #1B67A8);
            width: 40px;
            font-size: 1.3em;
            margin-top: -19px;
            padding: 19px;
            border-radius: 0 10px 10px 0;
            margin-right: -6px;
        }

        .select2 .select2-selection__placeholder {
            color: steelblue !important;
            font-weight: bolder;
        }

        .select2-selection__clear {
            margin-right: 34px !important;
        }

        .select2-container--bootstrap4 .select2-selection--single .select2-selection__arrow b {
            border-color: #fff transparent transparent;
            border-style: solid;
            border-width: 5px 4px 0;
        }

        #select_all_rows {
            display: block;
            margin: 0 auto;
        }

        #add-to-organization-form {
            z-index: 9999;
        }

        #add-to-organization-area {
            position: absolute;
            right: 10px;
            top: 0;
            padding: 8px;
            z-index: 999;
        }
    </style>
    <link rel="stylesheet" href="{{asset('/css/datatable-bundle.css')}}">
@endpush

@push('js')
    <script>
        $('b[role="presentation"]').hide();
        $('.select2-selection__arrow').append('<i class="fa fa-times" style="color: #000000; font-size: 10px"></i>');
    </script>
    <script type="text/javascript" src="{{asset('/js/datatable-bundle.js')}}"></script>
    <script>
        $(document).ready(function () {

            if (!($('#institute_id').attr('type') == "hidden")) {
                $('#programme_id').parent().addClass(' mt-2 offset-md-1');
            } else {
                $('#course_id').parent().addClass(' offset-md-1');
            }
            let params = serverSideDatatableFactory({
                url: '{{ route('admin.trainees.datatable') }}',
                order: [[1, "ASC"]],
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
                        title: "Name",
                        data: "name",
                        name: "name",
                    },
                    {
                        title: "Institute",
                        data: "institute_title",
                        name: "institutes.title",
                        visible: false
                    },
                    {
                        title: "Action",
                        data: "action",
                        name: "action",
                        orderable: false,
                        searchable: false,
                        visible: true
                    },
                ],
            });

            params.ajax.data = d => {
                d.organization_id = $('#organization_id').val();
                d.trainee_name = $('#trainee_name').val();
                d.reg_no = $('#reg_no').val();
            };

            $('#reset-btn').on('click', function () {
                $('#organization_id').val(null).trigger('change');
                $('#trainee_name').val(null).trigger('change');
                $('#reg_no').val(null).trigger('change');
            })

            let datatable = $('#dataTable').DataTable(params);


            $("#select_all_rows").click(function () {
                if ($(this).is(":checked")) {
                    datatable.rows(':has(.select-checkbox)').select();
                } else {
                    datatable.rows().deselect();
                }
            });

            $(document).on('change', '.select2-ajax-wizard', function () {
                datatable.draw();
            });

            $(document).on('change', '.search-text-fields', function () {
                datatable.draw();
            });


            datatable.on('select deselect', function (e, dt, type, indexes) {
                if (type === 'row') {
                    let selectedRows = datatable.rows({selected: true}).count();
                    if (selectedRows) {
                        $('#add-to-organization-area').css({visibility: 'visible'});
                    } else {
                        $('#add-to-organization-area').css({visibility: 'hidden'});
                    }

                    let totalRows = datatable.rows().count();
                    $("#select_all_rows").prop('checked', totalRows === selectedRows)

                }
            });
            bindDatatableSearchOnPresEnterOnly(datatable);

            $(document, 'td').on('click', '.delete', function (e) {
                $('#delete_form')[0].action = $(this).data('action');
                $('#delete_modal').modal('show');
            });


            $("#add-to-organization-area").click(function () {
                addToOrganizationForm.find('.trainee_ids').remove();
                let selectedRows = Array.from(datatable.rows({selected: true}).data());
                (selectedRows || []).forEach(function (row) {
                    addToOrganizationForm.append('<input name="trainee_ids[]" class="trainee_ids" value="' + row.id + '" type="hidden"/>');
                });
            });


            let addToOrganizationForm = $("#add-to-organization-form");
            addToOrganizationForm.validate({
                rules: {
                    organization_id: {
                        required: true,
                    }
                },
                messages: {
                    organization_id: {
                        required: "Please select Organization",
                    }
                },
                submitHandler: function (htmlForm) {
                    htmlForm.submit();
                }
            });

        });

    </script>
@endpush
