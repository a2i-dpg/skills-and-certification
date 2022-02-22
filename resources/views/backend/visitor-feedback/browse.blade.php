@extends('master::layouts.master')

@section('title')
    {{ __('admin.visitor_feedback.index')  }}
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header text-primary custom-bg-gradient-info">
                        <h3 class="card-title font-weight-bold">{{ __('admin.visitor_feedback.list')  }}</h3>

                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <div class="datatable-container">
                            <div class="filter-area">
                                <div class="">
                                    <div class="filter-item filter-label-header">
                                        <labe>
                                            <i class="fas fa-sort-amount-up-alt filter-icon"></i>
                                            <b>{{ __('admin.visitor_feedback.filter')  }} : </b>
                                        </labe>
                                    </div>

                                    <div class="filter-item">
                                        <input type="radio"id="all_filter" name="form_type" class="form_type" value="">
                                        <label for="all_filter">{{ __('admin.visitor_feedback.all')  }}</label>
                                    </div>

                                    <div class="filter-item">
                                        <input type="radio" id="feedbacl_filter" name="form_type" class="form_type"
                                               value="{{\App\Models\VisitorFeedback::FORM_TYPE_FEEDBACK}}">
                                        <label for="feedbacl_filter">{{ __('admin.visitor_feedback.feedback')  }}</label>
                                    </div>

                                    <div class="filter-item">
                                        <input type="radio" id="contact_filter" name="form_type" class="form_type"
                                               value="{{\App\Models\VisitorFeedback::FORM_TYPE_CONTACT}}">
                                        <label for="contact_filter">{{ __('admin.visitor_feedback.contact')  }}</label>
                                    </div>

                                </div>
                            </div>

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
    <style>
        @media only screen and (min-width: 825px) {
            .filter-area{
                position: absolute;
                padding: 0 60px;
            }
        }
        .filter-area{
            /*position: absolute;
            padding: 0 60px;*/

        }
        .filter-item{
            display: inline-block;
            padding: 0 10px;
        }
        .filter-icon{
            color: #007bff;
            font-size: 26px;
        }
        .filter-label-header{
            padding: 0 !important;
        }
    </style>
@endpush

@push('js')
    <script type="text/javascript" src="{{asset('/js/datatable-bundle.js')}}"></script>

    <script>
        $(function () {
            let params = serverSideDatatableFactory({
                url: '{{route('admin.visitor-feedback.datatable')}}',
                //order: [[0, "desc"]],
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
                        title: "{{ __('admin.visitor_feedback.institute_title')  }}",
                        data: "institute_title",
                        name: "institutes.title",
                        visible: false,
                    },
                    {
                        title: "{{ __('admin.visitor_feedback.name')  }}",
                        data: "name",
                        name: "visitor_feedback.name"
                    },
                    {
                        title: "{{ __('admin.visitor_feedback.mobile')  }}",
                        data: "mobile",
                        name: "visitor_feedback.mobile"
                    },
                    {
                        title: "{{ __('admin.visitor_feedback.email')  }}",
                        data: "email",
                        name: "visitor_feedback.email"
                    },
                    {
                        title: "{{ __('admin.visitor_feedback.type')  }}",
                        data: "form_type",
                        name: "visitor_feedback.form_type",
                    },
                    {
                        title: "{{ __('admin.common.status')  }}",
                        data: "read_at",
                        name: "visitor_feedback.read_at"
                    },
                    {
                        title: "{{ __('admin.visitor_feedback.date')  }}",
                        data: "created_at",
                        name: "visitor_feedback.created_at",
                        visible: true
                    },
                    {
                        title: "{{ __('admin.common.action')  }}",
                        data: "action",
                        orderable: false,
                        searchable: false,
                        visible: true
                    }
                ]
            });

            params.ajax.data = d => {
                d.form_type = $('.form_type:checked').val();
            };

            const datatable = $('#dataTable').DataTable(params);
            bindDatatableSearchOnPresEnterOnly(datatable);

            $(document, 'td').on('click', '.delete', function (e) {
                $('#delete_form')[0].action = $(this).data('action');
                $('#delete_modal').modal('show');
            });

            $('.form_type').on('change', function () {
                datatable.draw();
            });

            //Filter by all option selected
            $('#all_filter').prop("checked", true);

        });
    </script>
@endpush
