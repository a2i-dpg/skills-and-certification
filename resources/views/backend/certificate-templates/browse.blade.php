@extends('master::layouts.master')

@section('title')
    {{ __('admin.certificate-template.list')}}
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header text-primary custom-bg-gradient-info">
                        <h3 class="card-title font-weight-bold">{{ __('admin.certificate-template.list')}}</h3>
                        <div class="card-tools">
                            {{-- @can('create', \App\Models\CertificateTemplate::class)
                                <a href="{{route('admin.certificate-templates.create')}}"
                                   class="btn btn-sm btn-outline-primary btn-rounded">
                                    <i class="fas fa-plus-circle"></i> {{ __('admin.common.add')}}
                                </a>
                            @endcan --}}

                            <a href="{{route('admin.certificate-templates.create')}}"
                                   class="btn btn-sm btn-outline-primary btn-rounded">
                                    <i class="fas fa-plus-circle"></i> {{ __('admin.common.add')}}
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
                url: '{{ route('admin.certificate-templates.datatable') }}',
                order: [[3, "asc"]],
                serialNumberColumn: 0,
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
                        title: "{{__('generic.institute')}}",
                        data: "institute_title",
                        name: "institutes.title",
                        visible: {{ \App\Helpers\Classes\AuthHelper::getAuthUser()->isSuperUser() ? "true" : "false" }},
                    },

                    {
                        title: "{{__('admin.certificate-template.title')}}",
                        data: "title",
                        name: "certificate_templates.title",
                    },

                    {
                        title: "{{__('admin.certificate-template.template')}}",
                        data: "image",
                        name: "certificate_templates.image",
                    },


                    {
                        title: "{{__('admin.common.action')}}",
                        data: "action",
                        name: "action",
                        orderable: false,
                        searchable: false,
                        visible: true
                    },
                ],
            });
            const datatable = $('#dataTable').DataTable(params);

            bindDatatableSearchOnPresEnterOnly(datatable);

            $(document, 'td').on('click', '.delete', function (e) {
                $('#delete_form')[0].action = $(this).data('action');
                $('#delete_modal').modal('show');
            });

            const maxFeaturedGallery = 4;

            function checkMaxFeaturedGallery() {
                let nFeaturedGalleries = $('input[type="checkbox"]:checked').length;
                return nFeaturedGalleries <= maxFeaturedGallery;
            }

            function showToasterAlert(response) {
                let alertType = response.alertType;
                let alertMessage = response.message;
                let alerter = toastr[alertType];
                alerter ? alerter(alertMessage) : toastr.error("toastr alert-type " + alertType + " is unknown");
            }
        })

    </script>
    <script>
        $(function() {
            $('#toggle-one').bootstrapToggle();
        })
    </script>
@endpush
