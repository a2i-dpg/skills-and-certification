@php
    $currentInstitute = app('currentInstitute');
    $layout = 'master::layouts.front-end';

    $authTrainee = \App\Helpers\Classes\AuthHelper::isAuthTrainee();
@endphp
@extends($layout)

@section('title')
    {{$siteSettingInfo->site_title}} :: {{__('generic.home_page')}}
@endsection

@section('content')

    @if ($siteSettingInfo->show_slider)
        
    @php
        $sl=0;
        $sliderImageNo=0;
    @endphp
    @if(!$sliders->isEmpty())
        <!-- Top content Slider Start -->
        <section class="top-content ">
            <!-- Carousel -->
            <div id="topCarousel" class="carousel slide" data-ride="carousel">
                <div class="carousel-inner">
                    @foreach($sliders as $key => $slider)
                        @if (!$slider->slider)
                        <div class="carousel-item {{ ++$sl==1?'active':'' }}">
                            <div style="background: url('{{asset('/assets/default/slider-'.($key+1).'.jpg')}}');
                                background-position: center;
                                background-size: 100% 100%;
                                background-repeat: no-repeat;
                                min-height: 100%;
                                opacity: 1;
                                "></div>

                            <div class="carousel-caption">
                                <h3 class="slider-title" title="{{ $slider->title }}">
                                    {{ $slider->title }}</h3>
                                <div class="slider-button">
                                    @if($slider->is_button_available && $slider->link)
                                        <button class="btn btn-sm btn-link">{{ $slider->button_text }}</button>
                                    @endif
                                </div>
                            </div>
                        </div>    
                        @else
                        <div class="carousel-item {{ ++$sl==1?'active':'' }}">
                            <div style="background: url('{{asset('/storage/'. $slider->slider)}}');
                                background-position: center;
                                background-size: 100% 100%;
                                background-repeat: no-repeat;
                                min-height: 100%;
                                opacity: 1;
                                "></div>

                            <div class="carousel-caption">
                                <h3 class="slider-title" title="{{ $slider->title }}">
                                    {{ $slider->title }}</h3>
                                <div class="slider-button">
                                    @if($slider->is_button_available && $slider->link)
                                        <button class="btn btn-sm btn-link">{{ $slider->button_text }}</button>
                                    @endif
                                </div>
                            </div>
                        </div>
                        @endif
                    @endforeach
                </div>

                <a class="carousel-control-prev slider-previous-link" href="#topCarousel" role="button"
                   data-slide="prev">
                <span class="slider-previous-icon" aria-hidden="true">
                        <i class="fas fa-chevron-left"></i>
                </span>
                    <span class="sr-only">{{__('frontend.welcome.previous')}}</span>
                </a>
                <a class="carousel-control-next" href="#topCarousel" role="button" data-slide="next">
                <span class="slider-next-icon" aria-hidden="true">
                        <i class="fas fa-chevron-right"></i>
                </span>
                    <span class="sr-only">{{__('frontend.welcome.next')}}</span>
                </a>
            </div>
            <!-- End carousel -->
        </section>
        <!-- End Top Content Slider -->
    @endif

    @endif
    <!-- About Us Start-->
    <section class="about-us-section position-relative">
        <div class="about-section-color">
            <div class="container pt-5 pb-5">
                <div class="row">
                    <div class="col-md-7">
                        <!--Services Heading-->
                        <h2 class="section-heading-h2 pb-3 mb-0 font-weight-bold">{{strtoupper(__('generic.about_us'))}}</h2>
                        <div class="about-us-content">
                            @if(!empty($staticPage))
                                @if(strlen(strip_tags($staticPage->page_contents)) > 500)
                                    <p>
                                        {!! \Illuminate\Support\Str::limit( strip_tags($staticPage->page_contents), 460) !!}
                                    </p>

                                    <a href="{{route('frontend.static-content.show', ['page_id' => 'aboutus', 'instituteSlug' => $currentInstitute->slug ?? ''])}}"
                                       target="_blank"
                                       class="more-course-button mt-3 mb-5 bg-transparent">{{__('generic.see_more')}}<i
                                            class="fas fa-arrow-right btn-arrow"></i></a>
                                @else
                                    <p> {!! $staticPage->page_contents !!}</p>

                                @endif
                            @endif
                        </div>
                    </div>

                    <div class="col-md-5">
                        <div class="about-us-media" style="margin-top: -80px">
                            <iframe
                                src="https://www.youtube.com/embed/{{ !empty($introVideo)? $introVideo->youtube_video_id: '' }}"
                                height="400" width="100%"
                                title="Iframe" class="cr-img"></iframe>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- End About Us-->

    @if ($siteSettingInfo->show_glance)
    <!-- At A Glance Start -->
    <section class="bg-white pb-5">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="text-center">
                        <h2 class="section-heading d-inline-block">{{__('generic.at_a_glance')}}</h2>
                    </div>
                    <p class="text-center pb-2">
                        {{ $currentInstitute && !empty($currentInstitute->title)? $currentInstitute->title:'' }}
                        {{__('generic.static_on_training_course')}}
                    </p>
                    <div class="template-space"></div>
                </div>
            </div>
            <div class="row m-left-right-10">
                <div class="col-md-3 ">
                    <div class="instant-view-box instant-view-box-home">
                        <img src="{{asset('assets/atAglance/atg-1.png')}}" class="p-3" alt="">
                        <h3>{{($statistics['total_course'] ? $statistics['total_course'] :'0') }}</h3>
                        <p>{{__('generic.courses_are_providing_training')}}</p>
                    </div>
                </div>
                <div class="col-md-3 ">
                    <div class="instant-view-box instant-view-box-home">
                        <img src="{{asset('assets/atAglance/atg-2.png')}}" class="p-3" alt="">
                        <h3>{{($statistics['total_registered_trainee']?$statistics['total_registered_trainee']:'0') }}
                        </h3>
                        <p>{{__('generic.trainee')}}<br>{{__('generic.training_received')}}</p>
                    </div>
                </div>
                <div class="col-md-3 ">
                    <div class="instant-view-box instant-view-box-home">
                        <img src="{{asset('assets/atAglance/atg-3.png')}}" class="p-3" alt="">
                        <h3>{{($statistics['total_training_center']? $statistics['total_training_center']:'0') }}
                        </h3>
                        <p class="mt-4 mb-4">{{__('generic.training_center')}}</p>
                    </div>
                </div>
                <div class="col-md-3 ">
                    <div class="instant-view-box instant-view-box-home">
                        <img src="{{asset('assets/atAglance/atg-4.png')}}" class="p-3" alt="">
                        <h3>{{($statistics['total_trainer'] ? $statistics['total_trainer'] : '0') }}
                        </h3>
                        <p class="mt-4 mb-4">{{__('generic.skilled_trainer')}}</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- End At A Glance -->
    @endif

    @if ($siteSettingInfo->show_course)
    <!-- Courses Start -->
    <section class="container-fluid slider-area course-section">
        <div class="container my-4">

            @include('utils.trainee-loggedin-confirm-modal')

            <div class="row">
                <div class="col-md-12 mb-3">
                    <div class="text-center">
                        <h2 class="section-heading d-inline-block">{{__('generic.courses')}}</h2>
                    </div>
                    <p class="text-center pb-2">
                        {{ !empty($currentInstitute->title)? $currentInstitute->title:'' }}
                        {{__('generic.training_is_provided')}}
                    </p>
                </div>

                {{-- <div class="col-md-12 mb-5 mt-5 mt-5">
                    <div class="row">
                        <ul class="nav nav-tabs">
                            <li class="nav-item">
                                <a class="courseTabButton mr-3 active" id=""
                                   data-toggle="tab" href="#all-course" role="tab" aria-controls="all-course"
                                   aria-selected="true">{{strtoupper(__('generic.ongoing_course'))}}</a>
                            </li>
                        </ul>
                    </div>
                </div> --}}
                <div class="tab-content">
                    
                    @if($runningCourses->count() > 4)
                    <a id="running_courses"> 
                        <div id="all-course" class="tab-pane active">
                            <div id="courseCarousel" class="carousel custom-carousel slide w-100" data-ride="carousel">
                                <div class="custom-carousel-inner w-100" role="listbox">
                                    <div class="col-md-12 p-0">
                                        <div class="row">
                                            @php
                                                $ml=0;
                                            @endphp
                                            @foreach($runningCourses as $key => $course)
                                                <div
                                                    class="carousel-item custom-carousel-item {{ ++$ml==1?'active':'' }}">
                                                    <div class="col-md-3 course-card">
                                                        <a href="{{ route('frontend.course-details', ['course_id' => $course->id]) }}">
                                                            <div class="card card-main mb-2">
                                                                <div class="card-bar-home-course">
                                                                    <div class="pb-3">
                                                                        <img class="slider-img border-top-radius"
                                                                             src="{{$course->cover_image ? asset('/storage/'. $course->cover_image) : 'http://via.placeholder.com/640x360'}}"
                                                                             alt="{{ $course->title }}">
                                                                    </div>
                                                                    <div class="text-left pl-4 pr-4 pt-1 pb-1">
                                                                        {{-- <p class="font-weight-light" style="color: #9c36c6">{{ \App\Helpers\Classes\Helper::getLocaleCurrency($course->course_fee) ?? 'Free' }}</p> --}}
                                                                        <p class="font-weight-light" style="color: #9c36c6">{{ \App\Helpers\Classes\Helper::getLocaleCustomCurrency($course->course_fee, $siteSettingInfo->locale, $siteSettingInfo->local_currency) ?? 'Free' }} </p>
                                                                        
                                                                        <p class="font-weight-bold course-heading-wrap">{{ $course? $course->title :'' }}</p>

                                                                        <p class="font-weight-light mb-1"><i
                                                                                class="fas fa-clock gray-color mr-2"></i>
                                                                            <span
                                                                                class="course-p">{{__('frontend.welcome.duration')}}: {{ $course->duration ? $course->duration .' Hour' : __('generic.duration_not_specified') }}</span>

                                                                        </p>

                                                                        <p class="font-weight-light mb-1"><i
                                                                                class="fa fa-user gray-color mr-2"></i>
                                                                            <span
                                                                                class="course-p">{{__('frontend.welcome.student')}}({{ $course->enrolledTrainees ? $course->enrolledTrainees->count() : 0 }})</span>

                                                                        </p>
                                                                    </div>

                                                                    {{--                                                                    @unless($course->runningBatches->count() <= 0)--}}
                                                                    <div class="col-md-12">
                                                                        <a href="#"
                                                                           onclick="checkAuthTrainee({{ $course->id }})"
                                                                           class="btn btn-success btn-sm float-right mb-1"
                                                                           style="visibility: {{ $course->runningBatches->count() <= 0 ? 'hidden'  : 'visible'}}">{{ __('generic.apply') }}</a>
                                                                    </div>
                                                                    {{--                                                                    @endunless--}}
                                                                </div>
                                                            </div>
                                                        </a>

                                                        <div class="bg-white course-date-card">
                                                            <div class="text-primary text-center">
                                                                <p><span
                                                                        class="font-weight-bold">{{ optional($course->created_at)->format('d') }} </span>
                                                                    <br> {{ optional($course->created_at)->format('M') }}
                                                                </p>

                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>

                                <!--Controls-->
                                <div class="controls-top">
                                    <a class="btn-floating left-btn-arrow" href="#courseCarousel"
                                       data-slide="prev"><i
                                            class="fas fa-chevron-left"></i></a>
                                    <a class="btn-floating right-btn-arrow" href="#courseCarousel"
                                       data-slide="next"><i
                                            class="fas fa-chevron-right"></i></a>
                                </div>
                                <!--/.Controls-->
                            </div>
                        </div>
                    </a>
                    @elseif($runningCourses->isEmpty())
                        <div id="all-course" class="tab-pane active">
                            <div class="col-md-12">
                                <div class="alert text-danger text-center">
                                    {{__('generic.no_running_course_found')}}
                                </div>
                            </div>
                        </div>
                    @else
                    <a id="running_courses"> 
                        <div id="all-course" class="tab-pane active">
                            <div class="col-md-12 p-0">
                                <div class="row">
                                    @foreach($runningCourses as $key => $course)
                                        <div class="col-md-3 course-card">
                                            <a href="{{ route('frontend.course-details', ['course_id' => $course->id]) }}">
                                                <div class="card card-main mb-2">
                                                    <div class="card-bar-home-course">
                                                        <div class="pb-3">
                                                            <img class="slider-img border-top-radius"
                                                                 src="{{$course->cover_image ? asset('/storage/'. $course->cover_image) : 'http://via.placeholder.com/640x360'}}"
                                                                 alt="{{ $course->title }}">
                                                        </div>
                                                        <div class="text-left pl-4 pr-4 pt-1 pb-1">
                                                            {{-- <p class="font-weight-light" style="color: #9c36c6">{{ \App\Helpers\Classes\Helper::getLocaleCurrency($course->course_fee) ?? 'Free' }}</p> --}}
                                                            <p class="font-weight-light" style="color: #9c36c6">
                                                                {{ \App\Helpers\Classes\Helper::getLocaleCustomCurrency($course->course_fee, $siteSettingInfo->locale, $siteSettingInfo->local_currency) ?? 'Free' }}
                                                                {{-- {{$siteSettingInfo->locale}} -- {{$siteSettingInfo->local_currency}} --}}
                                                            </p>
                                                            <p class="font-weight-bold course-heading-wrap">{{ $course? $course->title :'' }}</p>

                                                            <p class="font-weight-light mb-1"><i
                                                                    class="fas fa-clock gray-color mr-2"></i> <span
                                                                    class="course-p">{{__('frontend.welcome.duration')}}: {{ $course->duration ? $course->duration. ' Hour' : __('generic.duration_not_specified') }}</span>

                                                            </p>

                                                            <p class="font-weight-light mb-1"><i
                                                                    class="fa fa-user gray-color mr-2"></i> <span
                                                                    class="course-p">{{__('frontend.welcome.student')}}({{ $course->enrolledTrainees ? $course->enrolledTrainees->count() : 0 }})</span>

                                                            </p>

                                                        </div>

                                                        {{--                                                        @unless($course->runningBatches->count() <= 0)--}}
                                                        <div class="col-md-12">
                                                            <a href="#"
                                                               onclick="checkAuthTrainee({{ $course->id }})"
                                                               class="btn btn-success btn-sm float-right mb-1"
                                                               style="visibility: {{ $course->runningBatches->count() <= 0  ? 'hidden' : 'visible'}}">{{ __('generic.apply') }}</a>
                                                        </div>
                                                        {{--                                                        @endunless--}}
                                                    </div>
                                                </div>
                                            </a>

                                            <div class="bg-white course-date-card">
                                                <div class="text-primary text-center">
                                                    <p><span
                                                            class="font-weight-bold">{{ optional($course->created_at)->format('d') }} </span>
                                                        <br> {{ optional($course->created_at)->format('M') }}</p>

                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                        </div>
                    </a>
                    @endif

                </div>
            </div>
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
    </section>
    <!-- End Courses -->
    @endif
    <!-- Event Start -->

    @if(app('currentInstitute'))
        <section class="yearly-training-calendar bg-white">
            <div class="container pb-3">
                <div class="row">
                    <div class="col-md-12">
                        <div class="text-center">
                            <h2 class="section-heading d-inline-block">{{__('generic.event')}}</h2>
                        </div>
                    </div>
                </div>
            </div>
            <div class="container p-5 card">
                <div class="row">
                    <div class="col-md-6">
                        <div class="row">
                            <div class="col-md-12" a id="event_area">
                                <h3 class="accordion-heading"
                                    id="eventDateTime">{{ \App\Helpers\Classes\EnglishToBanglaDate::dateFormatEnglishToBangla(date("l, j F Y")) }}</h3>
                                
                                <div id="eventArea" class="accordion">

                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 rounded">
                        <div id='calendar'></div>
                    </div>
                </div>

            </div>
        </section>

        <!-- End Event -->
    @else
        @if ($siteSettingInfo->show_provider)
        <!-- Skill Service Provider -->
        <section class="bg-white pb-5">
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <div class="text-center">
                            <h2 class="section-heading d-inline-block">{{__('generic.skill_service_provider')}}</h2>
                        </div>
                        <p class="text-center pb-2">
                            {{__('generic.all_service_provider')}}
                        </p>
                        <div class="template-space"></div>
                    </div>
                </div>
                <div class="row">
                    @foreach($skillServiceProviders as $skillServiceProvider)
                        <div class="col-md-3">
                            <div class="card card-main mb-2">
                                <div class="card-bar-home-course">
                                    <div class="pb-3">
                                        <img class="slider-img border-top-radius"
                                             src="{{$skillServiceProvider->institute_logo ? asset('/storage/'. $skillServiceProvider->institute_logo) : 'http://via.placeholder.com/640x360'}}"
                                             alt="{{ $skillServiceProvider->title }}">
                                    </div>
                                    <div class="text-left pl-4 pr-4 pt-1 pb-1">
                                        <p class="font-weight-bold course-heading-wrap">{{ $skillServiceProvider->title }}</p>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <p class="font-weight-light mb-1"><i
                                                        class="fas fa-book-open gray-color mr-2"></i> <span
                                                        class="course-p">{{$skillServiceProvider->total_courses > 0 ? $skillServiceProvider->total_courses .' Courses'  : __('generic.no_courses') }}</span>
                                                </p>
                                            </div>
                                            <div class="col-md-12">
                                                <p class="font-weight-light mb-1"><i
                                                        class="fas fa-user-plus gray-color mr-2"></i> <span
                                                        class="course-p">{{$skillServiceProvider->total_courses > 0 ? $skillServiceProvider->total_student .' Students'  : __('generic.no_courses') }}</span>
                                                </p>
                                            </div>
                                            <div class="col-md-12">
                                                <p class="font-weight-light mb-1 course-heading-wrap"><i
                                                        class="fas fa-map-marker-alt gray-color mr-2"></i> <span
                                                        class="course-p">{{$skillServiceProvider->institute_address ?? __('generic.no_address') }}</span>
                                                </p>
                                            </div>
                                        </div>
                                        <p class="float-right">
                                            <a href="{{ route('frontend.institute-details', ['id' => $skillServiceProvider->institute_id]) }}"
                                               class="btn btn-primary btn-sm">{{__('generic.details')}}</a>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                @if($skillServiceProviders->count() > 4)
                    <div class="col-md-12 text-center pt-3 pb-5">
                        <a href="{{ route('frontend.institute-list') }}" target="_blank"
                           class="more-course-button">{{__('generic.see_more')}}<i
                                class="fas fa-arrow-right btn-arrow"></i></a>
                    </div>
                @endif
            </div>
            <!-- End Skill Service Provider -->
            @endif
        @endif
        @if ($siteSettingInfo->show_gallary)
        <!-- Gallery Start -->
            <section class="gallery">
                <div class="container">
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <div class="text-center">
                                <h2 class="section-heading d-inline-block">{{__('generic.gallery')}}</h2>
                            </div>
                        </div>
                        @if($galleryCategories->count() > 3)
                            <div class="col-md-12">
                                <div class="row">
                                    @foreach($galleryCategories as $key => $galleryCategory)
                                        <div class="col-md-3">
                                            <a href="{{ route('frontend.gallery-category', ['galleryCategory' => $galleryCategory->id]) }}">
                                                <div class="card card-main mb-2 shadow-none bg-transparent">
                                                    <img class="slider-img slider-radius"
                                                         src="{{($galleryCategory->image) ? asset('/storage/'. $galleryCategory->image) : asset('/assets/default/Gallery-1'. ($key+1).'.jpg') }}">
                                                    <h3 class="gallery-post-heading">{{ mb_strimwidth($galleryCategory->title, 0, 20) }} {{ strlen($galleryCategory->title) > 20 ?'...':'' }}</h3>
                                                </div>
                                            </a>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @elseif($galleryCategories->count() > 2)
                            <div class="col-md-12">
                                <div class="row">
                                    @foreach($galleryCategories as $key=>$galleryCategory)
                                        <div class="col-md-4">
                                            <a href="{{ route('frontend.gallery-category', ['galleryCategory' => $galleryCategory->id]) }}">
                                                <div class="card card-main mb-2 shadow-none bg-transparent">
                                                    <img class="slider-img slider-radius"
                                                         src="{{($galleryCategory->image) ? asset('/storage/'. $galleryCategory->image) : asset('/assets/default/Gallery-1'. ($key+1).'.jpg') }}">
                                                    <h3 class="gallery-post-heading">{{ mb_strimwidth($galleryCategory->title, 0, 20) }} {{ strlen($galleryCategory->title) >20 ?'...':'' }}</h3>
                                                </div>
                                            </a>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @elseif($galleryCategories->count() > 1)
                            <div class="col-md-12">
                                <div class="row w-100 justify-content-center text-center">
                                    @foreach($galleryCategories as $key=>$galleryCategory)

                                        <div class="col-md-4 ">
                                            <a href="{{ route('frontend.gallery-category', ['galleryCategory' => $galleryCategory->id]) }}">
                                                <div class="card card-main mb-2 shadow-none bg-transparent">
                                                    <img class="slider-img slider-radius"
                                                         src="{{($galleryCategory->image) ? asset('/storage/'. $galleryCategory->image) : asset('/assets/default/Gallery-1'. ($key+1).'.jpg') }}">
                                                    <h3 class="gallery-post-heading">{{ mb_strimwidth($galleryCategory->title, 0, 20) }} {{ strlen($galleryCategory->title) >20 ?'...':'' }}</h3>
                                                </div>
                                            </a>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @elseif($galleryCategories->count() > 0)
                            <div class="col-md-12">
                                <div class="row w-100 justify-content-center text-center">
                                    @foreach($galleryCategories as $key=>$galleryCategory)
                                        <div class="col-md-4">
                                            <a href="{{ route('frontend.gallery-category', ['galleryCategory' => $galleryCategory->id]) }}">
                                                <div class="card card-main mb-2 shadow-none bg-transparent">
                                                    <img class="slider-img slider-radius"
                                                         src="{{($galleryCategory->image) ? asset('/storage/'. $galleryCategory->image) : asset('/assets/default/Gallery-1'. ($key+1).'.jpg') }}">
                                                    <h3 class="gallery-post-heading">{{ mb_strimwidth($galleryCategory->title, 0, 20) }} {{ strlen($galleryCategory->title) >20 ?'...':'' }}</h3>
                                                </div>
                                            </a>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @elseif($galleryCategories->isEmpty())
                            <div class="col-md-12">
                                <div class="alert text-danger text-center">
                                    {{__('generic.no_gallery_found')}}
                                </div>
                            </div>
                        @else
                            <div class="col-md-12">
                                <div class="row">
                                    @foreach($galleryCategories as $galleryCategory)
                                        <div class="col-md-3">
                                            <a href="{{ route('frontend.gallery-category', ['galleryCategory' => $galleryCategory->id]) }}">
                                                <div class="card card-main mb-2 shadow-none bg-transparent">
                                                    <img class="slider-img slider-radius"
                                                         src="{{asset('/storage/'. $galleryCategory->image)}}">
                                                    <h3 class="gallery-post-heading">{{ mb_strimwidth($galleryCategory->title, 0, 20) }} {{ strlen($galleryCategory->title) >20 ?'...':'' }}</h3>
                                                </div>
                                            </a>
                                        </div>
                                    @endforeach
                                </div>
                                @endif
                            </div>
                            @if((isset($galleryAllCategories) && $galleryAllCategories->count() > 3) && ($galleryCategories->count() > 0))
                                <div class="col-md-12 text-center pb-5">
                                    <a href="{{ route('frontend.gallery-categories') }}" target="_blank"
                                       class="more-course-button mt-3">{{__('generic.see_more')}}<i
                                            class="fas fa-arrow-right btn-arrow"></i></a>
                                </div>
                            @endif
                    </div>
                </div>
            </section>
            <!-- End Gallery -->
            @endif
            @endsection

            @push('css')
                <style>
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


                    .section-heading-h2 {
                        color: #671688;
                    }

                    .about-section-color {
                        background-color: #f6f9f9;
                    }

                    .course-section {
                        background: #FFFFFF;
                    }


                    .card-p1 {
                        color: #671688;
                    }

                    .cr-img {
                        border: 0;
                        border-radius: 15px;
                    }


                    .banner-bar h3, .banner-bar p {
                        color: #ffffff;
                    }


                    .banner-bar p {
                        font-size: 15px;
                    }

                    .left-btn-arrow {
                        position: absolute;
                        left: -2%;
                        bottom: 46%;
                    }

                    .right-btn-arrow {
                        position: absolute;
                        right: -2%;
                        bottom: 46%;
                    }


                    .carousel-indicators li {
                        width: 10px;
                        height: 10px;
                        border-radius: 50%;
                        background-color: #c4c4c4;
                    }

                    .carousel-control-prev, .carousel-control-next {
                        opacity: 1;
                    }

                    .card-main {
                        border-radius: 5px;
                    }

                    .card-bar {
                        padding: 10px 15px;
                        text-align: center;
                        margin: 0 10px;
                        transition: .4s;
                        cursor: pointer;
                        border-radius: 50%;
                    }

                    .more-course-button {
                        background: #fff;
                        color: #671688;
                        padding: 10px 25px;
                        display: inline-block;
                        margin: 30px 0 0 0;
                        transition: .4s;
                        border: 1px solid #671688;
                        border-radius: 20px;
                    }

                    .btn-arrow {
                        font-size: 1rem;
                        padding-left: 1rem;
                        margin-right: -10px;
                    }

                    .btn-floating {
                        color: black;
                    }

                    .modal-header .close, .modal-header .mailbox-attachment-close {
                        padding: 1rem;
                        margin: -1rem -1rem -1rem auto;
                        color: white;
                        outline: none;
                    }

                </style>
                <style>
                    /*sliders css*/
                    .slider-img {
                        width: 100%;
                        height: 11vw;
                        object-fit: cover;
                    }

                    .slider-radius {
                        border-radius: 0.5rem !important;
                    }

                    .slider-left-content h1 {
                        color: #000000;
                        font-size: 1.5rem;
                        font-weight: bold;
                        margin-bottom: 15px;
                    }

                    .slider-left-content p {
                        margin-bottom: 45px;
                        color: #6C6B76;
                    }

                    .slider-left-content a {
                        background: #671688;
                        padding: 15px 25px;
                        color: #fff;
                        border: 1px solid #671688;
                        border-radius: 5px;
                        letter-spacing: 2px;
                        transition: .4s;
                    }

                    .slider-left-content a:hover {
                        background: #4c4c4c;
                        border: 1px solid #4c4c4c;
                        transition: .4s;
                    }


                    .slider-right-content img {
                        float: right;
                        height: 135px !important;
                        width: 100% !important;
                        margin-top: 150px;
                    }

                    .slider-previous-icon, .slider-next-icon {
                        border: 1px solid white;
                        padding: 15px;
                        border-radius: 50%;
                    }

                    .slider-previous-icon i, .slider-next-icon i {
                        display: block;
                        width: 15px;
                        color: white;
                        font-size: 15px;
                    }

                    .slider-title {
                        font-family: Hind Siliguri;
                        font-style: normal;
                        font-size: 25px;
                        line-height: 40px;
                    }


                    /*Top Content Slider*/

                    .top-content {
                        width: 100%;
                        padding: 0;
                    }

                    .top-content .carousel-control-prev {
                        border-bottom: 0;
                    }

                    .top-content .carousel-control-next {
                        border-bottom: 0;
                    }

                    .top-content .carousel-caption {
                        padding-top: 0;
                        padding-bottom: 0;
                        left: 0;
                        right: 0;
                    }

                    .top-content .carousel-caption h1 {
                        padding-top: 60px;
                        color: #fff;
                    }

                    .top-content .carousel-caption h3 {
                        color: #fff;
                        background: linear-gradient(rgba(0, 0, 0, 0.2), rgba(0, 0, 0, 0.2));
                        padding: 10px;
                        margin-bottom: 0;
                    }

                    .top-content .carousel-caption .carousel-caption-description {
                        color: #fff;
                        color: rgba(255, 255, 255, 0.8);
                    }

                    .top-content .carousel-indicators li {
                        width: 16px;
                        height: 16px;
                        margin-left: 5px;
                        margin-right: 5px;
                        border-radius: 50%;
                    }

                    .top-content .carousel-item {
                        height: 110vh;
                    }

                    .carousel-caption {
                        bottom: 0;
                    }

                    .carousel-control-prev, .carousel-control-next {
                        width: 21%;
                    }

                    /*About Us*/

                    .about-us-section {
                        background: #FFFFFF;
                        padding-top: 4rem;
                    }

                    .about-us-content p {
                        line-height: 30px;
                        font-size: 20px;
                    }

                    .sidebar-list li {
                        list-style: none;
                        font-size: 14px;
                        line-height: 30px;
                    }

                    .custom-carousel-inner .carousel-item.active,
                    .custom-carousel-inner .carousel-item-next,
                    .custom-carousel-inner .carousel-item-prev {
                        display: flex;
                    }

                    .custom-carousel-inner .carousel-item-right.active,
                    .custom-carousel-inner .carousel-item-next {
                        transform: translateX(25%);
                    }

                    .custom-carousel-inner .carousel-item-left.active,
                    .custom-carousel-inner .carousel-item-prev {
                        transform: translateX(-25%);
                    }

                    .custom-carousel-inner .carousel-item-right,
                    .custom-carousel-inner .carousel-item-left {
                        transform: translateX(0);

                    }

                    /* At a Glance */

                    .section-heading-home {
                        color: #671688;
                        font-weight: bold;
                    }

                    .instant-view-box-home {
                        margin-right: 20px;
                        padding: 0;
                        box-shadow: 0px 5px 5px #d7d7d7;
                        transition: 0.3s;
                    }

                    .instant-view-box-home:hover {
                        box-shadow: 0px 0px 5px #d7d7d7;
                    }

                    .instant-view-box-home i {
                        font-size: 35px;
                    }

                    .instant-view-box-home h1 {
                        font-size: 30px;
                    }

                    .instant-view-box-home p {
                        color: #39759f;
                        padding: 0 10px;
                    }

                    /* Courses */

                    .card-bar-home-course {
                        padding: 0;
                        margin: 0;
                    }

                    .gray-color {
                        color: #73727f;
                    }

                    .course-p {
                        font-size: 14px;
                        font-weight: 400;
                        color: #671688;
                    }

                    .border-top-radius {
                        border-top-left-radius: 5px;
                        border-top-right-radius: 5px;
                    }

                    .course-heading-wrap {
                        text-overflow: ellipsis;
                        white-space: nowrap;
                        overflow: hidden;
                    }

                    .course-heading-wrap:hover {
                        overflow: visible;
                    }

                    .course-heading-wrap:hover {
                        overflow: visible;
                        white-space: normal;
                        cursor: pointer;
                    }

                    .courseTabButton {
                        padding: 10px 30px;
                        color: #671688;
                        border-radius: 5px;
                    }

                    .courseTabButton:hover, .courseTabButton:active, .courseTabButton:focus {
                        background: #671688;
                        padding: 10px 30px;
                        color: #fff;
                        border-radius: 5px;
                        border: 1px solid #671688;
                    }

                    #all-course {
                        width: 100%;
                    }

                    .tab-content {
                        width: 100%;
                    }

                    .nav-tabs {
                        border-bottom: 0;
                    }

                    .nav-tabs .nav-item .active {
                        background: #671688;
                        padding: 10px 30px;
                        color: #fff;
                        border-radius: 5px;
                        border: 1px solid #671688;
                    }


                    /* Gallery */
                    .gallery {
                        background: #FFFFFF;
                    }

                    .gallery-section-heading:before {
                        left: 50.5%;
                    }

                    .gallery-post-heading {
                        font-size: 1rem;
                        padding: 15px;
                        color: black;
                        font-weight: 400;
                    }

                    /* Event */
                    .accordion-heading {
                        background: #671688;
                        color: #ffffff;
                        padding: 20px;
                        border-radius: 10px;
                        font-size: 20px;
                        text-align: center;
                    }

                    .collapsible-link {
                        width: 100%;
                        position: relative;
                        text-align: left;
                    }

                    .collapsible-link::before {
                        content: '\f107';
                        position: absolute;
                        top: 50%;
                        right: 0.8rem;
                        transform: translateY(-50%);
                        display: block;
                        font-family: 'Font Awesome 5 Free';
                        font-size: 1.1rem;
                    }

                    .collapsible-link[aria-expanded='true']::before {
                        content: '\f106';
                    }

                    .accordion-date {
                        font-size: 12px;
                        padding-left: 5px;
                        color: darkgray;
                    }

                    .custom-carousel-inner {
                        position: relative;
                        width: 100%;
                        overflow: hidden;
                    }

                    .m-left-right-10 {
                        margin-left: 7.5px;
                        margin-right: 7.5px;
                    }

                    /* Responsive Design */

                    @media screen and (max-width: 767px) {
                        .about-us-media {
                            margin-top: 0px !important;
                        }

                        .slider-img {
                            height: 200px;
                        }

                        .about-us-section {
                            text-align: justify;
                        }

                        .top-content .carousel-item {
                            height: 60vh;
                        }


                        .slider-previous-icon, .slider-next-icon {
                            padding: 10px;
                        }
                    }

                    @media screen and (min-width: 987px ) {
                        .m-left-right-10 {
                            margin-left: 10%;
                            margin-right: 10%;
                        }
                    }


                </style>
                <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/fullcalendar@5.7.0/main.min.css"
                      type="text/css">
                <style>
                    #calendar {
                        background-color: #fff;
                        border-radius: 5px;
                    }

                    .fc .fc-col-header-cell-cushion {
                        display: inline-block;
                        padding: 2px 4px;
                        color: #2c3e50;
                    }

                    .fc-theme-standard td, .fc-theme-standard th {
                        border: none !important;
                        cursor: pointer;
                    }

                    .fc-theme-standard .fc-scrollgrid {
                        border: none !important;
                    }

                    .fc .fc-daygrid-day-number {
                        position: relative;
                        z-index: 4;
                        padding: 4px;
                        color: #000;
                    }

                    .fc .fc-day-other .fc-daygrid-day-top {
                        opacity: 1 !important;
                    }

                    .fc .fc-day-past:not(.fc-day-other) .fc-scrollgrid-sync-inner, .fc-day-future:not(.fc-day-other) .fc-scrollgrid-sync-inner {
                        background: #c7c7c7;
                        border: 1px solid #c7c7c7;
                        margin: 3px;
                        border-radius: 5px;
                    }


                    .fc-day-today a {
                        color: #fff !important;
                    }

                    .fc .fc-button-primary {
                        color: #000 !important;
                        background: none !important;
                        border: none !important;
                    }

                    .fc .fc-toolbar {
                        justify-content: center !important;
                    }

                    .fc .fc-button:focus {
                        outline: none;
                        box-shadow: none !important;
                    }

                    .fc .fc-daygrid-day-top {
                        display: flex;
                        flex-direction: row-reverse;
                        padding: 10px;
                        margin-bottom: 5px;
                    }


                    .fc .fc-scroller-liquid-absolute {
                        /*overflow: hidden !important;*/
                    }

                    .fc .fc-scroller {
                        overflow: hidden !important;
                    }

                    .fc .fc-highlight {
                        background: #3788d8;
                    }

                    .fc .fc-daygrid-body-unbalanced .fc-daygrid-day-events {
                        position: absolute;
                        min-height: 1em !important;
                        left: 0;
                        bottom: 0;
                        width: 25px;
                        text-align: center;
                        margin: 0;
                        padding: 0;
                    }

                    .carousel-item {
                        transition-duration: 3s;
                    }

                    .card {
                        /*min-height: 320px;*/
                        background-color: #FFFFFF;
                        padding: 0;
                        -webkit-border-radius: 4px;
                        -moz-border-radius: 4px;
                        border-radius: 4px;
                        box-shadow: 0 4px 5px 0 rgba(0, 0, 0, 0.14), 0 1px 10px 0 rgba(0, 0, 0, 0.12), 0 2px 4px -1px rgba(0, 0, 0, 0.3);
                    }


                </style>
            @endpush
            @push('js')
                <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.9.0/main.min.js"></script>
                <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.9.0/locales-all.js"></script>

                <script>

                    $(function () {
                        let eventDateTime = new Date();
                        eventDateTime = new Intl.DateTimeFormat('{{ config('settings.locale') }}', {
                            weekday: 'long',
                            day: '2-digit',
                            month: 'long',
                            year: 'numeric',
                        }).format(eventDateTime);

                        $('#eventDateTime').html(eventDateTime);
                    })

                    function checkAuthTrainee(courseId) {
                        const isTraineeAuthenticated = !!'{{$authTrainee}}';

                        if (isTraineeAuthenticated) {
                            window.location = "{!! route('frontend.course-apply', ['course_id' => '__']) !!}".replace('__', courseId);
                        } else {
                            $('#loggedIn_confirm__modal').modal('show');
                        }
                    }

                    function todayEvent() {
                        let today = new Date();
                        today = new Intl.DateTimeFormat('{{ config('settings.locale') }}').format(today);
                        return today;
                    }

                    function eventDate(date) {
                        let eventDate = new Date(date);
                        eventDate = new Intl.DateTimeFormat('{{ config('settings.locale') }}').format(eventDate);
                        return eventDate;
                    }

                    function eventTime(date) {
                        let time = new Date(date);
                        time = time.toLocaleTimeString(['{{ config('settings.locale') }}'], {
                            hour: '2-digit',
                            minute: '2-digit'
                        });
                        return time;
                    }

                    function eventsTemplate(key) {
                        let eventClass = '';
                        if (todayEvent() == eventDate(this.date)) {
                            eventClass = 'today-event';
                        } else {
                            eventClass = '';
                        }
                        return '<div class="card shadow-none mb-0">' +
                            '<div id="heading' + key + '" class="card-header bg-white shadow-sm border-0">' +
                            '<h2 class="mb-0">' +
                            '<button type="button" data-toggle="collapse" data-target="#collapse' + key + '"' +
                            'aria-expanded="true" aria-controls="collapseOne"' +
                            'class="btn btn-link text-dark font-weight-bold text-uppercase collapsible-link">' +
                            this.caption +
                            '<p class="mb-0 mt-1">' +
                            '<i class="far fa-calendar-minus gray-color"></i>' +
                            '<span class="accordion-date ' + eventClass + ' ml-1">' + eventDate(this.date) + '    ' + eventTime(this.date) + '</span>' +
                            '</p> </button> </h2>' +
                            '</div>' +
                            '<div id="collapse' + key + '" aria-labelledby="heading' + key + '"' +
                            'data-parent="#eventArea" class="collapse">' +
                            '<div class="card-body p-5">' +
                            '<p class="font-weight-light m-0">' + this.details +
                            '</p> <a href="{{ route('frontend.single-event', ['event' => '__']) }}"'.replace('__', this.id) +
                            'class="btn btn-sm btn-info mt-3">{{__('generic.see_details')}}</a>' +
                            '</div>' +
                            '</div>' +
                            '</div>';
                    }

                    let events = '';
                    @if(isset($currentInstituteEvents))
                        @foreach($currentInstituteEvents as $key => $currentInstituteEvent)
                        events += eventsTemplate.call(@json($currentInstituteEvent), '{{$key}}');
                    @endforeach
                    @endif

                    $("#eventArea").html(events);

                    function eventsOfSpecificDate(date) {
                        $.ajax({
                            url: '{{route('frontend.institute-events-date', ['instituteSlug' => $currentInstitute->slug ?? ''])}}',
                            type: "POST",
                            data: {date: date},
                        }).done(function (response) {
                            $("#eventArea").hide().empty();
                            let events = '';
                            $.each(response, function (key, value) {
                                events += eventsTemplate.call(value, key)
                            })
                            $("#eventArea").delay(100).fadeIn(800).html(events);
                        }).fail(function (xhr) {
                            failureCallback([]);
                        });
                    }

                    $(function () {
                        let calendarEl = document.getElementById('calendar');
                        let initialDate = '{{date('Y-m-d')}}';
                        let initialLocaleCode = '{{ config('settings.locale_code') }}';

                        let calendar = new FullCalendar.Calendar(calendarEl, {
                            initialView: 'dayGridMonth',
                            initialDate,
                            height: 500,
                            aspectRatio: 1,
                            displayEventTime: true,
                            selectable: true,
                            customButtons: {
                                myCustomButton: {
                                    text: '{{__('generic.year')}}',
                                    click: function () {
                                        window.location = '{{ route('frontend.fiscal-year') }}';
                                    }
                                }
                            },
                            headerToolbar: {
                                // left: 'prev,next today',
                                left: 'prev',
                                center: 'title',
                                //right: 'timeGridDay,timeGridWeek,dayGridMonth,myCustomButton'
                                right: 'next'
                            },
                            locale: initialLocaleCode,
                            // events: function (fetchInfo, successCallback, failureCallback) {
                            //     $.ajax({
                            //         url: '{{route('frontend.institute-events', ['instituteSlug' => $currentInstitute->slug ?? ''])}}',
                            //         type: "POST",
                            //     }).done(function (response) {
                            //         successCallback(response);
                            //     }).fail(function (xhr) {
                            //         failureCallback([]);
                            //     });
                            // },
                            dateClick: function (info) {
                                console.log(info);
                                let eventDateTime = new Date(info.dateStr);
                                eventDateTime = new Intl.DateTimeFormat('{{ config('settings.locale') }}', {
                                    weekday: 'long',
                                    day: '2-digit',
                                    month: 'long',
                                    year: 'numeric',
                                }).format(eventDateTime)
                                $('#eventDateTime').html(eventDateTime);
                                const start = info.dateStr;
                                eventsOfSpecificDate(start);
                            },
                        });
                        calendar.render();

                    });
                </script>

                <script>
                    $(document).ready(function () {

                        $(function () {
                            $("li").click(function () {
                                $("li").removeClass("active-menu");
                                $(this).addClass("active-menu");
                            });
                        });

                        $('#topCarousel').carousel({
                            interval: 8000
                        })
                        $('#recipeCarousel').carousel({
                            interval: 2000
                        })
                        $('#courseCarousel').carousel({
                            interval: 2000
                        })
                        $('.custom-carousel  .custom-carousel-item').each(function () {
                            let next = $(this).next();
                            if (!next.length) {
                                next = $(this).siblings(':first');
                            }
                            next.children(':first-child').clone().appendTo($(this));

                            for (let i = 0; i < 2; i++) {
                                next = next.next();
                                if (!next.length) {
                                    next = $(this).siblings(':first');
                                }

                                next.children(':first-child').clone().appendTo($(this));
                            }
                        });

                    });
                </script>
        @endpush
