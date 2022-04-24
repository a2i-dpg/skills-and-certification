@php
    $edit = !empty($trainers);
    $currentInstitute = app('currentInstitute');
    $layout = 'master::layouts.front-end';
@endphp
@extends($layout)

@section('title')
{{$siteSettingInfo->site_title}} :: {{__('generic.feedback')}}
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row trainee-profile" id="trainee-profile">
            <div class="col-md-3 mt-2">
                <div class="user-details card mb-3">
                    <div
                        class="card-header custom-bg-gradient-info">
                        <div class="card-title float-left font-weight-bold text-primary">{{__('generic.details')}}</div>
                    </div>
                    <div class="card-body">
                        <div class="user-image text-center">
                            <img
                                src="{{ $trainee->profile_pic ? asset('storage/'. $trainee->profile_pic ) : "http://via.placeholder.com/640x360"}}"
                                height="100" width="100" class="rounded-circle" alt="Trainee profile picture">
                        </div>
                        <div class="d-flex justify-content-center user-info normal-line-height mt-3">
                            <div class="text-bold">
                                {{ optional($trainee)->name }}
                            </div>
                        </div>
                    </div>
                </div>

                <div class="user-contact card bg-white mb-3">
                    <div class="card-header">
                        <div class="row">
                            <div class="text-center">
                                <i class="fa fa-phone"></i>
                            </div>
                            <p class="medium-text ml-2 text-primary">{{ __('generic.mobile')  }}</p>
                        </div>
                        <div class="phone">
                            <p class="medium-text">{{ $trainee->mobile ? $trainee->mobile : "N/A" }}</p>
                        </div>
                    </div>
                    <div class="card-header">
                        <div class="row">
                            <div class="text-center">
                                <i class="fa fa-envelope"></i>
                            </div>
                            <p class="medium-text ml-2 text-primary">{{ __('generic.email') }}</p>
                        </div>
                        <div class="email">
                            <p class="medium-text">{{ $trainee->email ?? "N/A"}}</p>
                        </div>
                    </div>
                    <div class="card-header">
                        <div class="row">
                            <div class="text-center">
                                <i class="fas fa-edit"></i>
                            </div>
                            <p class="medium-text ml-2 text-primary">{{__('generic.signature')}}</p>
                        </div>
                        <div class="email">
                            <img
                                src="{{ $trainee->student_signature_pic ? asset('storage/'. $trainee->student_signature_pic ) : "http://via.placeholder.com/640x360" }}"
                                height="40" alt="Trainee profile picture">
                        </div>
                    </div>
                </div>

            </div>

            <div class="col-md-9 mt-2">
                <div class="card bg-white">
                    <div
                        class="card-header custom-bg-gradient-info">
                        <div class="card-title float-left font-weight-bold text-primary">{{__('generic.feedback')}}</div>
                        <div class="card-tools">
                            <a href="{{route('frontend.trainee-enrolled-courses')}}"
                               class="btn btn-sm btn-outline-primary btn-rounded">
                                <i class="fas fa-backward"></i> {{__('admin.common.back')}}
                            </a>
                        </div>
                    </div>

                    <div class="col">
                        <div class="overlay" style="display: none">
                            <i class="fas fa-2x fa-sync-alt fa-spin"></i>
                        </div>
                    </div>

                    <div class="card-body">
                        @if($trainers->isEmpty())
                            <h3 class="text-center text-danger">{{__('frontend.trainer.trainer_yet')}}</h3>
                        @else
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>{{ __('admin.trainee_batches.trainer') }}</th>
                                        <th>{{ __('admin.trainee_batches.feedback') }}</th>
                                    </tr>
                                </thead>
                                @foreach($trainers as $key => $trainer)
                                    <tbody>
                                        <tr>
                                            <th scope="row">{{$key+1}}</th>
                                            <td>{{$trainer->name}}</td>
                                            <td>
                                                <a href="{{ route('frontend.trainee-feedback', $trainer->id) }}" class="btn btn-sm btn-info">{{__('frontend.trainer.feedback')}}</a>
                                            </td>
                                        </tr>
                                    </tbody>
                                @endforeach
                            </table>
                        @endif
                    </div>
                </div>

            </div>
        </div>
    </div>


    <!-----------modal Start----------->
    <div class="modal modal-danger fade" tabindex="-1" id="pay-now-modal" role="dialog" data-backdrop="static">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header custom-bg-gradient-info">
                    <h4 class="modal-title">
                        <i class="fas fa-exclamation-triangle"></i></i> {{ __('Do you want to pay now?') }}
                    </h4>
                    <button type="button" class="close" data-dismiss="modal"
                            aria-label="{{ __('voyager::generic.close') }}">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-right"
                            data-dismiss="modal">{{ __('Cancel') }}</button>
                    <form action="#" id="pay-now-form" method="POST">
                        {{--{{ method_field("DELETE") }}--}}
                        {{ csrf_field() }}
                        <input type="submit" class="btn btn-danger pull-right"
                               value="{{ __('Confirm') }}">
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-----------modal End------------->

@endsection

@push('css')
    <link rel="stylesheet" href="{{asset('/css/datatable-bundle.css')}}">
@endpush

@push('js')
    <script type="text/javascript" src="{{asset('/js/datatable-bundle.js')}}"></script>
@endpush


