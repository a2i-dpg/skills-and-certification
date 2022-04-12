@php
    /** @var \App\Models\Institute $currentInstitute */
    $currentInstitute = app('currentInstitute');
    $layout = 'master::layouts.front-end';

@endphp

@extends($layout)

@section('title')
{{$siteSettingInfo->site_title}} :: {{__('generic.courses')}}
@endsection

@section('content')
    <x-modal id="trainee-course-enroll-warning-modal" type="warning" xl></x-modal>

    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card mb-2">
                    <div class="card-header">
                        <h2 class="card-header-title text-center text-dark font-weight-bold">{{__('generic.courses')}}</h2>
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

                                    @if(!empty($currentInstitute))
                                        <input type="hidden" name="institute_id" id="institute_id"
                                               value="{{ $currentInstitute->id }}">
                                    @else
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <select class="form-control select2-ajax-wizard"
                                                        name="institute_id"
                                                        id="institute_id"
                                                        data-model="{{base64_encode(\App\Models\Institute::class)}}"
                                                        data-label-fields="{title}"
                                                        data-dependent-fields="#course_id|#programme_id"
                                                        data-placeholder="{{__('generic.select_ssp')}}"
                                                >
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-md-2">
                                            <button class="btn btn-success button-bg mb-2"
                                                    id="skill-course-search-btn">{{ __('generic.filter') }}</button>
                                        </div>
                                    @endif

                                    <div class="col-md-3 mb-3">
                                        <div class="input-group">
                                            <input type="search" name="search" id="search" class="form-control"
                                                   placeholder="{{__('generic.search_dot')}}"
                                                   style="border: 1px solid #e5e5e5;">
                                            <div class="input-group-append">
                                                <button class="btn button-bg text-white text-search-button"
                                                        type="button">
                                                    <i class="fa fa-search"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col">
                                        <div class="overlay bg-none" style="display: none;">
                                            <i class="fas fa-2x fa-sync-alt fa-spin"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row justify-content-center pt-4" id="course-card-container"></div>
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

                @include('utils.trainee-loggedin-confirm-modal')

            </div>

            <div class="modal modal-danger fade" tabindex="-1" id="course_details_modal" role="dialog">
                <div class="row">
                    <div class="col-sm-10 mx-auto">
                        <div class="modal-dialog" style="max-width: 100%">
                            <div class="modal-content modal-xlg" style="background-color: #e6eaeb">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @endsection

        @push('css')
            <style>
                .card {
                    min-height: 320px;
                    background-color: #FFFFFF;
                    padding: 0;
                    -webkit-border-radius: 4px;
                    -moz-border-radius: 4px;
                    border-radius: 4px;
                    box-shadow: 0 4px 5px 0 rgba(0, 0, 0, 0.14), 0 1px 10px 0 rgba(0, 0, 0, 0.12), 0 2px 4px -1px rgba(0, 0, 0, 0.3);
                }

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

                .course-card {
                    position: relative;
                }

                .course-date-card {
                    position: absolute;
                    height: 50px;
                    width: 50px;
                    top: 7px;
                    right: 15px;
                    border: 1px solid #fff;
                    border-radius: 5px;
                }
            </style>
        @endpush
        @push('js')
            <script>

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                const monthNames = ["January", "February", "March", "April", "May", "June",
                    "July", "August", "September", "October", "November", "December"
                ];

                function getMonthSmallName(month) {
                    if (!month) {
                        return '';
                    }

                    return monthNames[month].substr(0, 3);
                }

                function checkAuth(courseId) {
                    const isTraineeAuthenticated = !!'{{\App\Helpers\Classes\AuthHelper::isAuthTrainee()}}';

                    if (isTraineeAuthenticated) {
                        window.location = "{!! route('frontend.course-apply', ['course_id' => '__']) !!}".replace('__', courseId);
                    } else {
                        $('#loggedIn_confirm__modal').modal('show');
                    }
                }

                async function isAnyRunningBatch(data) {

                    return await $.ajax({
                        type: "POST",
                        url: '{{ route('frontend.course-running-batches') }}',
                        data: {data},
                    });
                }


                const template = function (key, course, isRunningBatch, totalEnrolledTrainees) {
                    let html = '';
                    html += '<div class="col-md-3 course-card">';
                    html += '<a href="{{ route('frontend.course-details', ['course_id' => '__']) }}">'.replace('__', course.id);
                    html += '<div class="card card-main mb-2">';
                    html += '<div class="card-bar-home-course">';
                    html += '<div class="pb-3">';
                    html += '<img class="slider-img border-top-radius"';
                    html += course?.cover_image ? 'src="{{asset('/storage/'. '__')}}"'.replace('__', course?.cover_image) + '" width="100%"' : 'src = "http://via.placeholder.com/640x360" width="100%"';
                    html += 'alt="' + course?.title + '">';
                    html += '</div>';
                    html += '<div class="text-left pl-4 pr-4 pt-1 pb-1">';
                    html += '<p class="font-weight-light"';
                    //html += 'style="color: #9c36c6">' + getLocaleCurrency('{{ config('settings.locale') }}', '{{ config('settings.local_currency') }}', course.course_fee) ?? "Free" + '</p>';
                    html += 'style="color: #9c36c6">' + getLocaleCurrency('{{ $siteSettingInfo->locale }}', '{{ $siteSettingInfo->local_currency }}', course.course_fee) ?? "Free" + '</p>';
                    html += '<p class="font-weight-bold course-heading-wrap">' + course.title + '</p>';

                    html += ' <p class="font-weight-light mb-1"><i';
                    html += ' class="fas fa-clock gray-color mr-2"></i>';
                    html += course?.duration ? '<span class="course-p">Duration: ' + course.duration + ' Hour</span>' : 'Duration: {{ __('generic.duration_not_specified') }}';
                    html += '</p>';

                    html += '<div class="row">';
                    html += '<p class=" col-md-12 font-weight-light mb-1"><i';
                    html += 'class="fa fa-user gray-color mr-2"></i>';
                    html += '<i class="fa fa-user gray-color mr-2"> </i><span class="course-p">Student(' + totalEnrolledTrainees + ')</span>';
                    html += '</p>';

                    if (isRunningBatch) {
                        html += '<div class = "col-md-12">';
                        html += '<a href="#" onclick="checkAuth(__)" class="btn btn-success btn-sm float-right"> {{__('generic.apply')}}</a>'.replaceAll('__', course.id);
                        html += '</div>';
                    } else {
                        html += '<div class = "col-md-12">';
                        html += '<a href="#" onclick="checkAuth(__)" class="btn btn-success btn-sm float-right" style="visibility: hidden"> {{__('generic.apply')}}</a>'.replaceAll('__', course.id);
                        html += '</div>';
                    }

                    html += '</div>';

                    html += '</div></div></div>';

                    html += '<div class="bg-white course-date-card">';
                    html += '<div class="text-primary text-center">';
                    html += '<p><span class="font-weight-bold">' + course?.created_at != null ? new Date(course?.created_at).toString().split(' ')[2] : " " + '</span>';
                    html += '<br>';
                    html += course?.created_at != null ? new Date(course?.created_at).toString().split(' ')[1] : " ";
                    html += '  </p></div></div></div>';

                    return html;
                }

                const paginatorLinks = function (link) {
                    //console.log(link)
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
                const courseFetch = searchAPI({
                    model: "{{base64_encode(\App\Models\Course::class)}}",
                    columns: 'id|title|cover_image|course_fee|duration|institute_id|branch_id|training_center_id|programme_id|created_at'
                });

                function courseSearch(url = baseUrl) {
                    $('.overlay').show();
                    let searchQuery = $('#search').val();
                    let institute = $('#institute_id').val();

                    const filters = {};
                    filters['row_status'] = '{{ \App\Models\Course::ROW_STATUS_ACTIVE }}';

                    if (searchQuery?.toString()?.length) {
                        filters['title'] = {
                            type: 'contain',
                            value: searchQuery
                        };
                    }
                    if (institute?.toString()?.length) {
                        filters['institute_id'] = institute;
                    }

                    courseFetch(url, filters)?.then(async function (response) {
                        let data = response?.data?.data;

                        $('.overlay').hide();
                        window.scrollTo(0, 0);
                        let html = '';
                        if (response?.data?.data.length <= 0) {
                            html += '<div class="text-center mt-5" "><i class="fa fa-sad-tear fa-2x text-warning mb-3"></i><div class="text-center text-danger h3">{{__('generic.no_courses_found')}}</div>';
                        }

                        let coursesRunningBatch = false;

                        if (data.length) {
                            coursesRunningBatch = await isAnyRunningBatch(data);
                        }

                        $.each(response.data?.data, function (i, item) {
                            const isRunningBatch = coursesRunningBatch[i]['is_any_running_batch'];
                            const total_enrolled_trainees = coursesRunningBatch[i]['total_trainees'];
                            html += template(item.id, item, isRunningBatch, total_enrolled_trainees);
                        });

                        $('#course-card-container').html(html);

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
                    courseSearch();
                    $(document).on('click', '.pagination .page-link', function (e) {
                        e.preventDefault();
                        let url = $(this).attr('href');
                        if (url) {
                            courseSearch(url);
                        }
                    });

                    $('#skill-course-search-btn').on('click', function () {
                        courseSearch();
                    });

                    $('.text-search-button').on('click', function () {
                        courseSearch();
                    });

                    $('#search').on('keyup change', function (e) {
                        if (e.keyCode === 13) {
                            courseSearch();
                        }
                    });
                });
            </script>
    @endpush
