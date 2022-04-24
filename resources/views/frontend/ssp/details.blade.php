@php
    $layout = 'master::layouts.front-end';
@endphp

@extends($layout)

@section('title')
{{$siteSettingInfo->site_title}} :: {{ __('generic.ssp.label') }}
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-md-10 py-2">
                <div class="card mb-2">
                    <h3 class="card-header text-center p-5">{{ $institute->title }}</h3>
                    <div class="card-body">
                    <a href="{{ url($institute->slug)}}">
                        <div class="row">
                        

                            
                            @if($institute->logo)
                            <div class="col-md-6 pr-5">
                                <img class="card-img"
                                     src="{{asset('/storage/'.$institute->logo)}}"
                                     height="300" alt="Card image cap" title="{{$institute->title}} image">
                            </div>
                            <div class="col-md-6">
                                <div class="row mr-2 pb-3">
                                    <h4 class="font-weight-bold card-title">{{ __('generic.ssp') }} {{__('frontend.ssp.information')}}</h4>
                                    <p class="card-info col-md-10 pl-0 pt-2"><span class="font-weight-bold">{{__('frontend.ssp.head_office')}}: </span>{{ $institute->office_head_name }}</p>
                                    <p class="card-info col-md-10 pl-0"><span class="font-weight-bold">{{__('frontend.ssp.office_head_post')}}: </span>{{ $institute->office_head_post }}</p>
                                    <p class="card-info col-md-10 pl-0"><span class="font-weight-bold">{{__('frontend.ssp.mobile')}}: </span>{{ $institute->mobile }}</p>
                                    <p class="card-info col-md-10 pl-0"><span class="font-weight-bold">{{__('frontend.ssp.email')}}: </span>{{ $institute->email }}</p>
                                    <p class="card-info col-md-10 pl-0"><span class="font-weight-bold">{{__('frontend.ssp.address')}}: </span>{{ $institute->address}}</p>
                                </div>
                                <div class="row">
                                    <h4 class="font-weight-bold card-title">{{__('frontend.ssp.contact_information')}}</h4>
                                    <p class="card-info col-md-10 pl-0"><span class="font-weight-bold"> {{__('frontend.ssp.mobile')}}: </span>{{ $institute->contact_person_name }}</p>
                                    <p class="card-info col-md-10 pl-0"><span class="font-weight-bold"> {{__('frontend.ssp.email')}}: </span>{{ $institute->contact_person_email }}</p>
                                    <p class="card-info col-md-10 pl-0"><span class="font-weight-bold"> {{__('frontend.ssp.address')}}: </span>{{ $institute->contact_person_mobile }}</p>
                                </div>
                            </div>
                            @else
                                <div class="col-md-6">
                                    <div class="row ml-5 pb-3">
                                        <h4 class="font-weight-bold card-title">{{__('frontend.ssp.institute_information')}}</h4>
                                        <p class="card-info col-md-10 pl-0 pt-2"><span class="font-weight-bold">{{__('frontend.ssp.head_office')}}: </span>{{ $institute->office_head_name }}</p>
                                        <p class="card-info col-md-10 pl-0"><span class="font-weight-bold">{{__('frontend.ssp.office_head_post')}}: </span>{{ $institute->office_head_post }}</p>
                                        <p class="card-info col-md-10 pl-0"><span class="font-weight-bold">{{__('frontend.ssp.mobile')}}: </span>{{ $institute->mobile }}</p>
                                        <p class="card-info col-md-10 pl-0"><span class="font-weight-bold">{{__('frontend.ssp.email')}}: </span>{{ $institute->email }}</p>
                                        <p class="card-info col-md-10 pl-0"><span class="font-weight-bold">{{__('frontend.ssp.address')}}: </span>{{ $institute->address}}</p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="row ml-5 ">
                                        <h4 class="font-weight-bold card-title float-none">{{__('frontend.ssp.contact_information')}}</h4>
                                        <p class="card-info col-md-10 pl-0"><span class="font-weight-bold"> {{__('frontend.ssp.name')}}: </span>{{ $institute->contact_person_name }}</p>
                                        <p class="card-info col-md-10 pl-0"><span class="font-weight-bold"> {{__('frontend.ssp.email')}}: </span>{{ $institute->contact_person_email }}</p>
                                        <p class="card-info col-md-10 pl-0"><span class="font-weight-bold"> {{__('frontend.ssp.mobile')}}: </span>{{ $institute->contact_person_mobile }}</p>
                                    </div>
                                </div>
                            @endif

                        </div>
                    </a>
                    </div>

                    <div class="card-footer text-center">

                    </div>
                </div>
            </div>
        </div>
@endsection

@push('css')
<style>
    .card {
        box-shadow: 0 5px 5px #e5e5e5 !important;
    }
    .card-info {
        margin-bottom: 0;
    }
    .card-title {
        border-bottom: 1px solid #e5e5e5;
        padding-bottom: 10px;
        margin-bottom: 10px;
    }
</style>
@endpush
