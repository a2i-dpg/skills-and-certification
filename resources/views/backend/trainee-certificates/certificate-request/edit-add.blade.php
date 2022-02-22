@php
    $edit = !empty($batchCertificate->id);
    /** @var \App\Models\User $authUser */
    $authUser = \App\Helpers\Classes\AuthHelper::getAuthUser();
@endphp

@extends('master::layouts.master')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card card-outline">
                    <div class="card-header d-flex justify-content-between custom-bg-gradient-info">
                        <h3 class="card-title font-weight-bold text-primary">{{ ! $edit ?  __('admin.certificate.certificate_template')  : __('admin.batches.update') }}</h3>
                        <div>
                            <a href="{{route('admin.trainee.certificates.request')}}" class="btn btn-sm btn-rounded btn-outline-primary">
                                <i class="fas fa-backward"></i>{{__('admin.common.back')}}
                            </a>
                        </div>
                    </div>

                    <div class="card-body">
                        <form class="row edit-add-form" method="post"
                              action="{{ route('admin.trainee.certificates.request.store', [1]) }}">
                            @csrf
                            {{--@if($edit)
                                @method('put')
                            @endif--}}

                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="title">{{ __('admin.certificate.given_name')}}</label>
                                    <input type="text" class="form-control" id="title"
                                           name="title"
                                           value="{{$certificateRequest->given_name}}" disabled>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <input type="hidden" name="certificate_request_id" id="certificate_request_id" value="{{$certificateRequest->id}}">
                                    <label for="title">{{ __('admin.certificate.registered_name')}}</label>
                                    <input type="text" class="form-control" id="title"
                                           name="title"
                                           value="{{$certificateRequest->registered_name}}" disabled>
                                </div>
                            </div>

                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="title">{{ __('admin.certificate.given_father_name')}}</label>
                                    <input type="text" class="form-control" id="title"
                                           name="title"
                                           value="{{$certificateRequest->given_father_name}}" disabled>
                                </div>
                            </div>


                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="title">{{ __('admin.certificate.given_mother_name')}}</label>
                                    <input type="text" class="form-control" id="title"
                                           name="title"
                                           value="{{$certificateRequest->given_mother_name}}" disabled>
                                </div>
                            </div>


                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="title">{{ __('admin.certificate.given_date_of_birth')}}</label>
                                    <input type="text" class="form-control" id="title"
                                           name="title"
                                           value="{{$certificateRequest->given_date_of_birth}}" disabled>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="title">{{ __('admin.certificate.registered_date_of_birth')}}</label>
                                    <input type="text" class="form-control" id="title"
                                           name="title"
                                           value="{{$certificateRequest->registered_date_of_birth}}" disabled>
                                </div>
                            </div>

                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="title">{{ __('admin.certificate.batch_title')}}</label>
                                    <input type="text" class="form-control" id="title"
                                           name="title"
                                           value="{{$certificateRequest->batch_title}}" disabled>
                                </div>
                            </div>

                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="authorized_by">{{ __('admin.certificate.course_title')}}</label>
                                    <input type="text" class="form-control" id="authorized_by"
                                           name="authorized_by"
                                           value="{{$certificateRequest->course_title}}" disabled>
                                </div>
                            </div>

                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="title">{{ __('admin.certificate.remark')}}</label>
                                    <textarea type="text" class="form-control" id="comment"
                                           name="comment"
                                    ></textarea>
                                </div>
                            </div>

                           {{-- <div class="col-sm-6">
                                <div class="form-group">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="1" id="approve_status" name="approve_status">
                                        <label class="form-check-label text-bold" for="defaultCheck1">
                                            {{ __('admin.certificate.issue_certificate')}}
                                        </label>
                                    </div>
                                    --}}{{--<label for="authorized_by">{{ __('admin.certificate.issue_certificate')}}</label>
                                    <input type="text" class="form-control" id="authorized_by"
                                           name="authorized_by"
                                           value="{{$certificateRequest->course_title}}" disabled>--}}{{--
                                </div>
                            </div>--}}

                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="logo">{{ __('admin.certificate.id_proof') }} <span
                                            style="color: red"> * </span></label>
                                    <div class="input-group">
                                        <div class="logo-upload-section">
                                            <div class="avatar-preview text-center">
                                                <label for="signature">
                                                    <img class="figure-img"
                                                         src={{ asset('storage/'. $certificateRequest->id_image) }}
                                                             width="200" height="100"
                                                         alt="Institute logo"/>
                                                    <span class="p-1 bg-gray"
                                                          style="position: relative; right: 0; bottom: 50%; border: 2px solid #afafaf; border-radius: 50%;margin-left: -31px; overflow: hidden">
                                                        <i class="fa fa-pencil-alt text-white"></i>
                                                    </span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-sm-12 text-right">
                                <input type="submit"
                                        class="btn btn-success" name="approve_status" value="{{  __('admin.certificate.approve_button')}}">
                                <input type="submit" class="btn btn-danger" value="{{  __('admin.certificate.reject_button')}}">
                            </div>

                        </form>
                    </div><!-- /.card-body -->
                    <div class="overlay" style="display: none">
                        <i class="fas fa-2x fa-sync-alt fa-spin"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
@push('css')
    <style>
        .flat-date-custom-bg, .flat-time-custom-bg{
            background-color: #fafdff !important;
        }
    </style>
@endpush

@push('js')
    <script>
    </script>
@endpush


