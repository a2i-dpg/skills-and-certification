@php

    $authUser = \App\Helpers\Classes\AuthHelper::getAuthUser();


@endphp

@extends('master::layouts.master')

@section('title')
    {{ __('admin.intro-video.list') }}
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header text-primary custom-bg-gradient-info">
                        <h3 class="card-title font-weight-bold">{{ !$authUser->institute_id? __('admin.intro-video.list') : __('admin.intro-video.index') }}</h3>
                        <div class="card-tools">
                            @can('create', App\Models\Video::class)

                                @if(!$authUser->institute_id)
                                    <a href="{{route('admin.intro-videos.create')}}"
                                       class="btn btn-sm btn-outline-primary btn-rounded">
                                        <i class="fas fa-plus-circle"></i> {{__('admin.common.add')}}
                                    </a>
                                @endif

                                @if($videosCount == 0 && $authUser->institute_id)
                                    <a href="{{route('admin.intro-videos.create')}}"
                                       class="btn btn-sm btn-outline-primary btn-rounded">
                                        <i class="fas fa-plus-circle"></i> {{ __('admin.intro-video.add') }}
                                    </a>
                                @endif
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
                url: '{{ route('admin.intro-videos.datatable') }}',
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
                        title: "{{ __('admin.intro-video.youtube_video_url') }}",
                        data: "youtube_video_url",
                        name: "intro_videos.youtube_video_url",
                    },
                    {
                        title: "{{ __('admin.intro-video.institute_title') }}",
                        data: "institute_title",
                        name: "institutes.title",
                        visible: false,
                    },
                    {
                        title: "{{ __('admin.common.status') }}",
                        data: "row_status",
                        name: "intro_videos.row_status",
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
