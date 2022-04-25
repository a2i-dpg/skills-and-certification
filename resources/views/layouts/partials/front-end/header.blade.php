@php
    /** @var \App\Models\Institute $currentInstitute */
    $currentInstitute =  app('currentInstitute');
    $navItems = app('navHeaders');
@endphp

<div class="container-fluid">
    <div class="container">
        <header class="navbar navbar-expand flex-column flex-md-row bd-navbar">
            <div class="navbar-nav-scroll" style="width: 100%">
                <div class="nise3-logo" style="height: 0px;">
                    <div class="float-left px-1" style="max-width: 500px; padding: 0px;">
                        @if($currentInstitute)
                            <p class="p-0 m-0">
                                <img src="{{ asset('storage/'. $currentInstitute->logo) }}"
                                     alt="{{ $currentInstitute->title }}" height="36"> <span
                                    class="slogan slogan-tag">{{ $currentInstitute->title }}</span>
                            </p>
                        @else
                            @if ($siteSettingInfo->show_logo)
                                <p class="p-0 m-0">
                                    <img src="{{ asset('storage/'. $siteSettingInfo->site_logo) }}"
                                        alt="{{ $siteSettingInfo->site_title }}" height="48"> 
                                        <span class="slogan slogan-tag">{{ ($siteSettingInfo->site_title) ? $siteSettingInfo->site_title : env('APP_NAME') }}</span>
                                </p>
                            @else
                            <p class="slogan slogan-tag">{{ ($siteSettingInfo->site_title) ?? env('APP_NAME') }} </p>
                            @endif
                        @endif
                    </div>
                    @if ($siteSettingInfo->show_lang)
                    <div class="float-right" style=" {{($siteSettingInfo->show_logo)? 'margin-top: 10px' : 'margin-top:0px'}} ">
                        <ul class="navbar-nav ml-auto">
                            
                            <li class="nav-item m-auto">
                                <div class="btn-group language bg-light" style="border-radius: 10px">
                                    <button onclick="document.getElementById('change_language').submit()"
                                            class="btn btn-sm btn-{{app()->getLocale() === 'en' ? 'primary': 'default'}} padding8 font-size-12 border-rad-left14">
                                        English
                                    </button>
                                    <button onclick="document.getElementById('change_language').submit()"
                                            class="btn btn-sm btn-{{app()->getLocale() === 'bn' ? 'primary': 'default'}} padding5 font-size-12 border-rad-right14">
                                        বাংলা
                                    </button>
                                </div>
                                <form method="post" action="{{route('change-language', app()->getLocale() === 'bn' ? 'en': 'bn')}}"
                                      id="change_language">
                                    @csrf
                                </form>
                            </li>
                        </ul>
                    </div>
                    @endif
                </div>

                
            </div>
        </header>
    </div>
</div>

<nav class="container-fluid main-menu sticky-top navbar navbar-expand-lg navbar-light menu-bg-color">
    <div class="container">
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavDropdown"
                aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNavDropdown">
            <!-- Left menu items -->
            <ul class="navbar-nav mr-auto">
                <!-- Left menu item empty -->
                <li class="nav-item {{ request()->is('/') ? 'active-menu' : '' }}">
                    <a href="{{ route('frontend.main') }}"
                       class="btn ">{{__('generic.home')}}</a>
                </li>

                {{-- @if($currentInstitute)
                    <li class="nav-item {{ request()->path() == $currentInstitute->slug ? 'active-menu' : '' }}">
                        <a href="{{ route('frontend.main', ['instituteSlug' => $currentInstitute->slug ?? '']) }}"
                           class="btn ">{{__('generic.ssp_home')}}</a>
                    </li>
                @endif --}}

                {{-- @if($currentInstitute)
                    <li class="nav-item {{ strstr(request()->path(), 'aboutus')  == 'aboutus' ? 'active-menu' : '' }}">
                        <a href="{{route('frontend.static-content.show', ['page_id' => 'aboutus', 'instituteSlug' => $currentInstitute->slug ?? ''])}}"
                           class="btn ">{{__('generic.about_us')}}  </a>
                    </li>
                @endif --}}

                @if(!$currentInstitute)
                    <li class="nav-item {{ request()->is('ssp-list*') ? 'active-menu' : '' }}">
                        <a href="{{ route('frontend.institute-list') }}" class="btn ">Service Providers</a>
                    </li>
                @endif


                <li class="nav-item {{ ($currentInstitute ? request()->is($currentInstitute->slug. '/courses-search*') : request()->is('courses-search*')) ? 'active-menu' : '' }}">
                    <a href="{{ route('frontend.course_search', ['instituteSlug' => $currentInstitute->slug ?? '']) }}"
                       class="btn ">{{__('generic.courses')}}</a>
                </li>


                <li class="nav-item {{ ($currentInstitute ? request()->is($currentInstitute->slug. '/skill-videos*') : request()->is('skill-videos*')) ? 'active-menu' : '' }}">
                    <a href="{{ route('frontend.skill_videos', $currentInstitute->slug ?? '') }}"
                       class="btn ">{{__('generic.videos')}}</a>
                </li>

                <li class="nav-item {{ ($currentInstitute ? request()->is($currentInstitute->slug. '/general-ask-page*') : request()->is('general-ask-page*'))? 'active-menu' : '' }}">
                    <a href="{{ route('frontend.general-ask-page', $currentInstitute->slug ?? '') }}"
                       class="btn">{{__('generic.faq')}}</a>
                </li>

                @if($currentInstitute && $currentInstitute->slug)
                    <li class="nav-item {{ request()->is($currentInstitute->slug. '/contact-us-page*') ? 'active-menu' : '' }}">
                        <a href="{{ route('frontend.contact-us-page', $currentInstitute->slug) }}"
                           class="btn">{{__('generic.contact')}}</a>
                    </li>
                @endif

            </ul>


            <!-- Right menu items -->
            <ul class="nav navbar-nav navbar-right">
                @if(!\Illuminate\Support\Facades\Auth::guard('web')->check())
                    <div class="dropdown">
                        <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            SignIn/SignUp
                        </button>
                        <div class="dropdown-menu menu-bg-color" aria-labelledby="dropdownMenuButton">
                            @if(!auth()->guard('web')->check())
                                <a class="btn dropdown-item {{ request()->is('trainee-registrations') ? 'active' : '' }}"
                                   href="{{ route('frontend.trainee-registrations.index') }}"
                                   id="bd-versions" aria-haspopup="true">
                                    <i class="fa fa-file"> </i>&nbsp; {{__('generic.trainee_registration')}}
                                </a>


                                {{-- <a class="btn dropdown-item {{ request()->is('ssp-registration') ? 'active' : '' }}"
                                   href="{{ route('frontend.ssp-registration') }}"
                                   id="bd-versions" aria-haspopup="true">
                                    <i class="fa fa-file"> </i>&nbsp; {{__('generic.ssp_registration')}}
                                </a> --}}
                            @endif

                            @if(!\Illuminate\Support\Facades\Auth::guard('web')->check())
                                <a class="dropdown-item" href="{{ route('admin.login-form') }}"
                                   id="bd-versions">
                                    <i class="far fa-user"></i>&nbsp; {{__('generic.login')}}
                                </a>
                            @endif
                        </div>
                    </div>
                @endif


                @if(\App\Helpers\Classes\AuthHelper::checkAuthUser())
                    <li class="nav-item dropdown">
                        <a class="nav-item nav-link mr-md-2 text-white" href="{{ route('admin.dashboard') }}"
                           id="bd-versions">
                            <i class="fas fa-clipboard-list"></i>&nbsp; {{__('generic.dashboard')}}
                        </a>
                    </li>
                @elseif(\App\Helpers\Classes\AuthHelper::isAuthTrainee())
                    <li class="nav-item dropdown">
                        <a class="dropdown-toggle btn" href="#" id="navbarDropdown" role="button"
                           data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            {{__('generic.profile')}}
                        </a>
                        <div class="dropdown-menu menu-bg-color" aria-labelledby="navbarDropdown">
                            <a class="dropdown-item"
                               href="{{ route('frontend.trainee') }}">
                                <i class="fas fa-clipboard-list"></i>&nbsp; {{__('generic.my_profile')}}
                            </a>
                            <a class="dropdown-item"
                               href="{{ route('frontend.trainee-enrolled-courses') }}">
                                <i class="fas fa-clipboard-list"></i> &nbsp; {{__('generic.my_courses')}}
                            </a>
                        </div>
                    </li>

                    <li class="nav-item">
                        <a class="btn keycloak_logout" href="#"
                           onclick="document.getElementById('trainee-logout').submit()"
                           id="bd-versions">
                            <i class="fas fa-lock-open"></i>&nbsp; {{__('generic.logout')}}
                        </a>
                        <form id="trainee-logout" style="display: none" method="post"
                              action="{{route('admin.logout')}}">
                            @csrf
                        </form>
                    </li>
                @endif
            </ul>
        </div>
    </div>
</nav>



