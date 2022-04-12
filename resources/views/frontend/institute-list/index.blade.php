@php
    $layout = 'master::layouts.front-end';
@endphp

@extends($layout)

@section('title')
{{$siteSettingInfo->site_title}} :: {{ __('generic.ssps') }}
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card mb-2">
                    <div class="card-header p-5">
                        <h2 class="card-header-title text-center text-dark font-weight-bold">{{ __('generic.ssps') }}</h2>
                    </div>
                    <div class="card-background-white px-5 py-4">
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <div class="row">
                                    <div class="col-md-1">
                                        <label
                                            style="color: #757575; line-height: calc(1.5em + .75rem); font-size: 1rem; font-weight: 400;">
                                            &nbsp;&nbsp;<i class="fa fa-filter mr-2"></i> {{__('generic.filter')}}
                                        </label>
                                    </div>

                                    <div class="col-md-3 mb-3">
                                        <div class="input-group">
                                            <input type="search" name="search" id="search" class="form-control"
                                                   placeholder="{{__('generic.search_dot')}}" style="border: 1px solid #e5e5e5;">
                                            <div class="input-group-append">
                                                <button class="btn button-bg text-white" type="button">
                                                    <i class="fa fa-search"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col">
                                        <div class="overlay" style="display: none; background: inherit; color: inherit">
                                            <i class="fas fa-2x fa-sync-alt fa-spin"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row justify-content-center pt-4" id="container-publish-courses"></div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="prev-next-button float-right">

                                </div>
                                <div class="overlay" style="display: none">
                                    <i class="fas fa-2x fa-sync-alt fa-spin"></i>
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
                .card-background-white {
                    background: #faf8fb;
                }

                .form-control {
                    border: 1px solid #671688;
                    color: #671688;
                }

                .form-control:focus {
                    border-color: #671688;
                }

                .button-bg {
                    background: #671688;
                    border-color: #671688;
                }

                .button-bg:hover {
                    color: #ffffff;
                    background-color: #671688 !important;;
                    border-color: #671688 !important;;
                }

                .button-bg:active {
                    color: #ffffff;
                    background-color: #671688 !important;
                    border-color: #671688 !important;;
                }

                .button-bg:focus {
                    color: #ffffff;
                    background-color: #671688 !important;;
                    border-color: #671688 !important;;
                }

                .button-bg:visited {
                    color: #ffffff;
                    background-color: #671688 !important;;
                    border-color: #671688 !important;;
                }

                .card-header-title {
                    min-height: 48px;
                }

                .card-bar-home-course img {
                    height: 14vw;
                }

                .gray-color {
                    color: #73727f;
                }

                .course-heading-wrap {
                    text-overflow: ellipsis;
                    white-space: nowrap;
                    overflow: hidden;
                }

                .course-heading-wrap:hover {
                    overflow: visible;
                    white-space: normal;
                    cursor: pointer;
                }

                .course-p {
                    font-size: 14px;
                    font-weight: 400;
                    color: #671688;
                }

                .header-bg {
                    background: #671688;
                    color: white;
                }

                .modal-header .close, .modal-header .mailbox-attachment-close {
                    padding: 1rem;
                    margin: -1rem -1rem -1rem auto;
                    color: white;
                    outline: none;
                }

                .card-p1 {
                    color: #671688;
                }
                .card {
                    box-shadow: 0px 5px 5px #e5e5e5 !important;
                }
                .course-heading-wrap {
                    margin-bottom: 5px;
                    font-size: 15px;
                }
                hr {
                    margin-top: 0.75rem;
                    margin-bottom: 0.75rem;
                }
            </style>
        @endpush
        @push('js')

            <script>
                const template = function (item) {
                    let html = '';
                    html += '<div class="col-md-3">';
                    html += '<div class="card card-main mb-3">';
                    html += '<div class="card-bar-institute-list">';
                    html += '<a href="{{ url('__')}}"'.replace('__', item.slug);
                    html += '<div class="">';
                    html += '<img class="slider-img border-top-radius"';
                    html += item.logo ? 'src="{{asset('/storage/'. '__')}}"'.replace('__', item.logo) + '" width="100%" height="150px">' : 'src = "http://via.placeholder.com/640x360" width="100%" height="150px"' + '>';
                    html += '</div>';
                    html += '<div class="text-left p-4">';
                    html += '<h5 class="font-weight-bold">SSP Information</h5>';
                    html += '<hr/>';
                    html += '<p class=" course-heading-wrap">SSP Name: ' + item?.title + '</p>';
                    html += '<p class="course-heading-wrap"><span>Office Head: </span> ' + item?.office_head_post + ' ' + item?.office_head_name + '</p>';
                    html += '<hr/>';
                    html += '<h5 class="font-weight-bold">Contact Information</h5>';
                    html += '<hr/>';
                    html += '<p class=" course-heading-wrap">Name: ' + item?.contact_person_name + '</p>';
                    html += '<p class=" course-heading-wrap">Mobile: ' + item?.contact_person_mobile + '</p>';
                    html += '<p class="course-heading-wrap">Address: ' + item?.address ?? " " + '</p>';
                    html += '</div>';
                    html += '</a>';

                    html += '</div>';
                    html += '</div> ';
                    html += '</div>';

                    return html;
                }

                const paginatorLinks = function (link) {
                    if (link.label == 'pagination.previous') {
                        link.label = 'Previous'
                    }
                    if (link.label == 'pagination.next') {
                        link.label = 'Next'
                    }
                    let html = '';
                    if (link.active) {
                        html += '<li class="page-item active">' +
                            '<a class="page-link">' + link.label + '</a>' +
                            '</li>';
                    } else if (!link.url) {
                        html += '<li class="page-item">' +
                            '<a class="page-link">' + link.label + '</a>' +
                            '</li>';
                    } else {
                        html += '<li class="page-item"><a class="page-link" href="' + link.url + '">' + link.label + '</a></li>';
                    }
                    return html;
                }


                const searchAPI = function ({model, columns}) {
                    return function (url, filters = {}) {
                        return $.ajax({
                            url: url,
                            type: "POST",
                            data: {
                                _token: '{{csrf_token()}}',
                                resource: {
                                    model: model,
                                    columns: columns,
                                    paginate: true,
                                    page: 1,
                                    per_page: 16,
                                    filters,
                                }
                            }
                        }).done(function (response) {
                            return response;
                        });
                    };
                };

                let baseUrl = '{{route('web-api.model-resources')}}';
                const instituteFetch = searchAPI({
                    model: "{{base64_encode(\App\Models\Institute::class)}}",
                    columns: 'id|title|contact_person_name|contact_person_email|contact_person_mobile|address|office_head_name|office_head_post|logo|slug'
                });

                function instituteSearch(url = baseUrl) {
                    $('.overlay').show();
                    let searchQuery = $('#search').val();

                    const filters = {};
                    if (searchQuery?.toString()?.length) {
                        filters['title'] = {
                            type: 'contain',
                            value: searchQuery
                        };
                    }

                    instituteFetch(url, filters)?.then(function (response) {
                        $('.overlay').hide();
                        window.scrollTo(0, 0);
                        let html = '';
                        if (response?.data?.data.length <= 0) {
                            html += '<div class="text-center mt-5" "><i class="fa fa-sad-tear fa-2x text-warning mb-3"></i><div class="text-center text-danger h3">{{__('generic.no_ssp_found')}}</div>';
                        }

                        $.each(response.data?.data, function (i, item) {
                            html += template(item);
                        });

                        $('#container-publish-courses').html(html);

                        let link_html = '<nav> <ul class="pagination">';
                        let links = response?.data?.links;
                        if (links.length > 3) {
                            $.each(links, function (i, link) {
                                link_html += paginatorLinks(link);
                            });
                        }
                        link_html += '</ul></nav>';
                        $('.prev-next-button').html(link_html);
                    });
                }

                $(document).ready(function () {
                    instituteSearch();
                    $(document).on('click', '.pagination .page-link', function (e) {
                        e.preventDefault();
                        let url = $(this).attr('href');
                        if (url) {
                            instituteSearch(url);
                        }
                    });

                    $("#search").on("keyup change", function (e) {
                        instituteSearch();
                    })
                });
            </script>
    @endpush

