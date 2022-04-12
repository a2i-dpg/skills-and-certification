@php
    $currentInstitute = app('currentInstitute');
    $layout = 'master::layouts.front-end';

@endphp
@extends($layout)
@section('title')
{{$siteSettingInfo->site_title}} :: {{__('generic.center_list')}}
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card mb-2">
                    <div class="card-header p-5">
                        <h2 class="text-center text-dark font-weight-bold">
                            {{__('generic.center_list')}}
                        </h2>
                    </div>
                    <div class="card-body">
                        <div class="">
                            <!-- BEGIN BORDERED TABLE PORTLET-->
                            <div class="portlet light bordered">
                                <div class="portlet-body">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <p>
                                            <form>
                                                {{--@csrf--}}
                                                <input class="form-control center-search" name="search"
                                                       id="venue_name"
                                                       value="{{ request()->query('search') }}"
                                                       placeholder="{{__('generic.search')}}">
                                            </form>
                                            </p>
                                        </div>
                                        <div class="col-md-12">
                                            <div id="venue-list">
                                                <ul class="center-list" id="center_list">
                                                    <?php
                                                    $sl = 0;
                                                    ?>
                                                    @foreach($publishedCourses as $publishedCourse)
                                                        <li style="list-style: none;">
                                                            <p>
                                                                {{ \App\Helpers\Classes\NumberToBanglaWord::engToBn(++$sl) }}
                                                                ) {{ $publishedCourse->trainingCenter? $publishedCourse->trainingCenter->title.',':''}}
                                                                {{ $publishedCourse->branch? $publishedCourse->branch->title.',':''}}
                                                                {{ $publishedCourse->institute? $publishedCourse->institute->title: ''}}
                                                            </p>
                                                            <p class="personmobile">
                                                                {{ $publishedCourse->institute? $publishedCourse->institute->primary_mobile: ''}} </p>
                                                            <address>
                                                                <i>{{__('generic.address')}} :
                                                                    @php
                                                                        if($publishedCourse->trainingCenter){
                                                                            $address =  $publishedCourse->trainingCenter->address;
                                                                        }elseif ($publishedCourse->branch){
                                                                            $address =   $publishedCourse->branch->address;
                                                                        }else{
                                                                            $address =   $publishedCourse->institute->address;
                                                                        }
                                                                    @endphp
                                                                    {{ $address }}
                                                                </i>
                                                            </address>
                                                            <hr>
                                                        </li>

                                                    @endforeach
                                                    @if(!count($publishedCourses))
                                                        <h5 class="text-danger text-center p-5">{{__('generic.no_center_found')}}</h5>
                                                    @endif

                                                </ul>
                                                {{--{{$publishedCourses->links()}}--}}

                                            </div>

                                        </div>
                                        <div class="col-md-12">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

