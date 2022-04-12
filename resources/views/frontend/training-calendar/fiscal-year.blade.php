@php
    $currentInstitute = app('currentInstitute');
    $layout = 'master::layouts.front-end';
@endphp

@extends($layout)

@section('title')
{{$siteSettingInfo->site_title}} :: {{__('generic.training_implementation_schedule')}}
@endsection

@section('content')
    <div class="container-fluid" id="fixed-scrollbar">
        <div class="row">
            <div class="col-md-12">
                <div class="card mb-2">
                    <div class="card-header p-5 border-0">
                        @php
                            $year = ( date('m') > 6) ? date('Y') + 1 : date('Y');
                        @endphp
                        <h2 class="text-center text-dark font-weight-bold">
                        {{__('generic.training_implementation_schedule')}}
                            {{ (date('m') > 6) ? (date('Y').'-'.(date('Y')+1)) : ((date('Y')-1) .'-'.date('Y')) }}
                        </h2>
                    </div>
                    <div class="card-body">
                        <div class="fc-toolbar-chunk float-right pb-3 calender-custom-btn">
                            <div class="fc-button-group">
                                <a href="{{ route('frontend.yearly-training-calendar.index') }}"
                                   class="fc-timeGridDay-button fc-button fc-button-primary">{{__('generic.day')}}</a>
                                <a href="{{ route('frontend.yearly-training-calendar.index') }}"
                                   class="fc-timeGridDay-button fc-button fc-button-primary">{{__('generic.week')}}</a>
                                <a href="{{ route('frontend.yearly-training-calendar.index') }}"
                                   class="fc-timeGridDay-button fc-button fc-button-primary">{{__('generic.month')}}</a>
                                <a href="#"
                                   class="fc-myCustomButton-button fc-button fc-button-primary fc-button-active">{{__('generic.year')}}</a>
                            </div>
                        </div>
                        <div>
                            <table
                                class="table table-responsive table-bordered table-hover floatThead-table table-fixed  fixed-scrollbar">
                                <thead class="" style="background: #f4f6f9">
                                <tr>
                                    <th rowspan="2" style="vertical-align: middle">{{__('generic.serial_no')}}</th>
                                    <th rowspan="2" style="vertical-align: middle">{{__('generic.trade_name')}}</th>
                                    <th colspan="12" rowspan="1"><p class="text-center">{{__('generic.month')}}</p></th>
                                    <th rowspan="2" style="vertical-align: middle">{{__('generic.annual_training_goals')}}</th>
                                    <th rowspan="2" style="vertical-align: middle">{{__('generic.training_types')}}</th>
                                    <th rowspan="2" style="vertical-align: middle">{{__('generic.training_center')}}</th>
                                    <th rowspan="2" style="vertical-align: middle">{{__('generic.training_details')}}</th>
                                </tr>
                                <tr>
                                    <th>{{__('generic.july')}}<br>{{($year-1) }}
                                    </th>
                                    <th>{{__('generic.august')}}<br>{{($year-1) }}
                                    </th>
                                    <th>{{__('generic.september')}}<br>{{($year-1) }}
                                    </th>
                                    <th>{{__('generic.october')}}<br>{{($year-1)}}
                                    </th>
                                    <th>{{__('generic.november')}}<br>{{($year-1)}}
                                    </th>
                                    <th>{{__('generic.december')}}<br>{{($year-1)}}
                                    </th>
                                    <th>{{__('generic.january')}}<br>{{($year) }}
                                    </th>
                                    <th>{{__('generic.february')}}<br>{{($year)}}</th>
                                    <th>{{__('generic.march')}}<br>{{($year) }}
                                    </th>
                                    <th>{{__('generic.april')}}<br>{{($year) }}
                                    </th>
                                    <th>{{__('generic.may')}}<br>{{($year) }}
                                    </th>
                                    <th>{{__('generic.june')}}<br>{{ ($year) }}
                                    </th>
                                </tr>
                                </thead>
                                <tbody>
                                @php
                                    $sl = 0;
                                @endphp

                                @if($totalCourseVenue)
                                    @foreach($courses as $key => $course)
                                        <tr>
                                            <th class="text-center"
                                                rowspan="{{ count($course)+1 }}">{{(++$sl) }}</th>
                                            <th colspan="5">{{ optional($totalCourseVenue[$key])->course_name}}</th>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                            <th class="align-middle text-center"
                                                rowspan="{{ count($course)+1 }}">
                                                {{ ($totalCourseVenue? $totalAnnualTrainingTarget[$totalCourseVenue[$key]->course_id] :'0') }}
                                            </th>
                                            <th class="align-middle text-center"
                                                rowspan="{{ count($course)+1 }}"> {{ $totalCourseVenue? ($totalCourseVenue[$key]->course_fee? __('generic.residential')  : __('generic.non_residential')):'' }}</th>
                                            <th class="align-middle text-center"
                                                rowspan="{{ count($course)+1 }}">{{ ($totalCourseVenue? ($totalCourseVenue[$key]? $totalCourseVenue[$key]->total_venue :'0'):'') }}
                                                {{__('generic.center')}}
                                            </th>
                                            <th class="align-middle text-center"
                                                rowspan="{{ count($course)+1 }}">
                                                <a href="{{ route('frontend.venue-list', $totalCourseVenue?$totalCourseVenue[$key]->course_id:'' ) }}">{{__('generic.details')}}</a>
                                            </th>
                                        </tr>

                                        @foreach($course as $courseSession)
                                            <tr>
                                                <th style="font-size: 12px">
                                                    {{  $courseSession->session_name? $courseSession->session_name:'' }}
                                                </th>
                                                <th>{{ \App\Helpers\Classes\NumberToBanglaWord::engToBn(date('m', strtotime($courseSession->application_start_date))==7 && date('Y', strtotime($courseSession->application_start_date))==date('Y')? date('d', strtotime($courseSession->application_start_date)) :'') }}</th>
                                                <th>{{ \App\Helpers\Classes\NumberToBanglaWord::engToBn(date('m', strtotime($courseSession->application_start_date))==8 && date('Y', strtotime($courseSession->application_start_date))==date('Y')? date('d', strtotime($courseSession->application_start_date)) :'') }}</th>
                                                <th>{{ \App\Helpers\Classes\NumberToBanglaWord::engToBn(date('m', strtotime($courseSession->application_start_date))==9 && date('Y', strtotime($courseSession->application_start_date))==date('Y')? date('d', strtotime($courseSession->application_start_date)) :'') }}</th>
                                                <th>{{ \App\Helpers\Classes\NumberToBanglaWord::engToBn(date('m', strtotime($courseSession->application_start_date))==10 && date('Y', strtotime($courseSession->application_start_date))==date('Y')? date('d', strtotime($courseSession->application_start_date)) :'') }}</th>
                                                <th>{{ \App\Helpers\Classes\NumberToBanglaWord::engToBn(date('m', strtotime($courseSession->application_start_date))==11 && date('Y', strtotime($courseSession->application_start_date))==date('Y')? date('d', strtotime($courseSession->application_start_date)) :'') }}</th>
                                                <th>{{ \App\Helpers\Classes\NumberToBanglaWord::engToBn(date('m', strtotime($courseSession->application_start_date))==12 && date('Y', strtotime($courseSession->application_start_date))==date('Y')? date('d', strtotime($courseSession->application_start_date)) :'') }}</th>
                                                <th>{{ \App\Helpers\Classes\NumberToBanglaWord::engToBn(date('m', strtotime($courseSession->application_start_date))==1 && date('Y', strtotime($courseSession->application_start_date))==date('Y')+1? date('d', strtotime($courseSession->application_start_date)) :'') }}</th>
                                                <th>{{ \App\Helpers\Classes\NumberToBanglaWord::engToBn(date('m', strtotime($courseSession->application_start_date))==2  && date('Y', strtotime($courseSession->application_start_date))==date('Y')+1? date('d', strtotime($courseSession->application_start_date)) :'') }}</th>
                                                <th>{{ \App\Helpers\Classes\NumberToBanglaWord::engToBn(date('m', strtotime($courseSession->application_start_date))==3  && date('Y', strtotime($courseSession->application_start_date))==date('Y')+1? date('d', strtotime($courseSession->application_start_date)) :'') }}</th>
                                                <th>{{ \App\Helpers\Classes\NumberToBanglaWord::engToBn(date('m', strtotime($courseSession->application_start_date))==4  && date('Y', strtotime($courseSession->application_start_date))==date('Y')+1? date('d', strtotime($courseSession->application_start_date)) :'') }}</th>
                                                <th>{{ \App\Helpers\Classes\NumberToBanglaWord::engToBn(date('m', strtotime($courseSession->application_start_date))==5  && date('Y', strtotime($courseSession->application_start_date))==date('Y')+1? date('d', strtotime($courseSession->application_start_date)) :'') }}</th>
                                                <th>{{ \App\Helpers\Classes\NumberToBanglaWord::engToBn(date('m', strtotime($courseSession->application_start_date))==6  && date('Y', strtotime($courseSession->application_start_date))==date('Y')+1? date('d', strtotime($courseSession->application_start_date)) :'') }}</th>
                                            </tr>
                                        @endforeach
                                    @endforeach
                                @else
                                    <tr>
                                        <th colspan="18">
                                            <div class="alert text-danger text-center">
                                            {{__('generic.no_data_found')}}
                                            </div>
                                        </th>
                                    </tr>
                                @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
@push('css')
    <style>
        .fc-button-group {
            position: relative;
            display: inline-flex;
        }

        .fc .fc-button, .fc .fc-button .fc-icon, .fc .fc-button-group, .fc .fc-timegrid-slot-label {
            vertical-align: middle;
        }

        .fc-button:not(:disabled), a[data-navlink], .fc-event.fc-event-draggable, .fc-event[href] {
            cursor: pointer;
        }

        .fc-button-group > .fc-button:not(:last-child) {
            border-top-right-radius: 0;
            border-bottom-right-radius: 0;
        }

        .fc-button-group > .fc-button:not(:first-child) {
            margin-left: -1px;
            border-top-left-radius: 0;
            border-bottom-left-radius: 0;
        }

        .fc-button-group > .fc-button {
            position: relative;
            flex: 1 1 auto;
        }

        .fc-button-primary {
            color: #fff;
            background-color: #2C3E50;
            border-color: #2C3E50;
        }

        .fc-button {
            -webkit-appearance: button;
            -webkit-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;
            user-select: none;
            padding: .4em .65em;
            font-size: 1em;
            line-height: 1.5;
            border-radius: .25em;
            overflow: visible;
            text-transform: none;
            margin: 0;
            font-family: inherit;
            vertical-align: middle;
            display: inline-block;
            font-weight: 400;
            text-align: center;
        }

        .fc-button-group > .fc-button.fc-button-active, .fc-button-group > .fc-button:active, .fc-button-group > .fc-button:focus, .fc-button-group > .fc-button:hover {
            z-index: 1;
            color: #fff;
            background-color: #1a252f;
            border-color: #151e27;
        }

        .fc-button-active {
            z-index: 1;
            color: #fff;
            background-color: #1a252f;
            border-color: #151e27;
        }

        .fc-button-primary:not(:disabled).fc-button-active, .fc-button-primary:not(:disabled):active {
            color: #fff;
            background-color: #1a252f;
            border-color: #151e27;
        }

        .calender-custom-btn {
            margin-right: 45px;
        }
        #fixed-scrollbar{
            overflow: auto;
        }
    </style>
@endpush
@push('js')
    <script>
        var scrollbar = $('<div id="fixed-scrollbar"><div></div></div>').appendTo($(document.body));
        scrollbar.hide().css({
            overflowX:'auto',
            position:'fixed',
            width:'100%',
            bottom:0
        });
        var content = scrollbar.find('div');

        function mtop(e) {
            return e.offset().top;
        }

        function bottom(e) {
            return e.offset().top + e.height();
        }

        var active = $([]);
        function find_active() {
            scrollbar.show();
            var active = $([]);
            $('.fixed-scrollbar').each(function() {
                if (mtop($(this)) < mtop(scrollbar) && bottom($(this)) > bottom(scrollbar)) {
                    content.width($(this).get(0).scrollWidth);
                    content.height(1);
                    active = $(this);
                }
            });
            fit(active);
            return active;
        }

        function fit(active) {
            if (!active.length) return scrollbar.hide();
            scrollbar.css({left: active.offset().left, width:active.width()});
            content.width($(this).get(0).scrollWidth);
            content.height(1);
            delete lastScroll;
        }

        function onscroll(){
            var oldactive = active;
            active = find_active();
            if (oldactive.not(active).length) {
                oldactive.unbind('scroll', update);
            }
            if (active.not(oldactive).length) {
                active.scroll(update);
            }
            update();
        }

        var lastScroll;
        function scroll() {
            if (!active.length) return;
            if (scrollbar.scrollLeft() === lastScroll) return;
            lastScroll = scrollbar.scrollLeft();
            active.scrollLeft(lastScroll);
        }

        function update() {
            if (!active.length) return;
            if (active.scrollLeft() === lastScroll) return;
            lastScroll = active.scrollLeft();
            scrollbar.scrollLeft(lastScroll);
        }

        scrollbar.scroll(scroll);

        onscroll();
        $(window).scroll(onscroll);
        $(window).resize(onscroll);
    </script>
@endpush
