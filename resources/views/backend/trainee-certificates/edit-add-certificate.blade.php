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
                        <h3 class="card-title font-weight-bold text-primary">{{ ! $edit ?  __('admin.certificate.certificate_template')  : __('admin.certificate.certificate_template') }}</h3>
                        <div>
                            <a href="{{route('admin.batches.certificates.index')}}" class="btn btn-sm btn-rounded btn-outline-primary">
                                <i class="fas fa-backward"></i>{{__('admin.common.back')}}
                            </a>
                        </div>
                    </div>

                    <div class="card-body">
                        <form class="row edit-add-form" method="post"
                              action="{{ route('admin.batch.certificates.store', [$batch_id]) }}"
                                enctype="multipart/form-data"
                                >
                            @csrf
                            {{--@if($edit)
                                @method('put')
                            @endif--}}

                            <div class="col-sm-6">
                                <div class="form-group">
                                    <input type="hidden" name="batch_id" id="batch_id" value="{{$batch_id}}">
                                    <label for="title">{{ __('admin.certificate.course_title')}}</label>
                                    <input type="text" class="form-control" id=""
                                           name=""
                                           value="{{$batch->course->title}}" disabled>
                                </div>
                            </div>

                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="title">{{ __('admin.certificate.batch_title')}}</label>
                                    <input type="text" class="form-control" id=""
                                           name=""
                                           value="{{$batch->title}}" disabled>
                                </div>
                            </div>

                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="authorized_by">{{ __('admin.certificate.authorized_by')}}</label>
                                    <input type="text" class="form-control" id="authorized_by"
                                           name="authorized_by"
                                           value="{{  $edit ? $batchCertificate->authorized_by : old('issued_date') }}"
                                    >
                                </div>
                            </div>

                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="issued_date">{{ __('admin.certificate.issue_date')}}</label>
                                    <input type="text" class="flat-date flat-date-custom-bg" id="issued_date"
                                           name="issued_date"
                                           value="{{  $edit ? $batchCertificate->issued_date ?? '': old('issued_date') }}"
                                    >
                                </div>
                            </div>

                            <div class="col-sm-12">
                                <div class="form-group">
                                  <label for="custom_css">{{ __('admin.certificate.custom_css') }}  <span style="color:tomato">like </span> <span>color:white;</span> <span style="color:tomato">if empty default color black </span> </label>
                                  <textarea class="form-control" id="custom_css"
                                            name="custom_css"
                                            rows="1"
                                            placeholder="{{ __('admin.certificate.custom_css') }}">{{ $edit ? $batchCertificate->custom_css : '' }}</textarea>
                                </div>
                            </div>

                            @if (count($certificateTemplates) > 0)
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label for="title">{{ __('admin.certificate.certificate_template')}} <span> <a href="{{route('admin.certificate-templates.index')}}">Add more template</a>  </span>
                                        </label>
                                        <div class="row">
                                            @foreach($certificateTemplates as $id=>$certificateTemplate)
                                            <div class="col-sm-3">
                                                <div class="custom-control custom-radio">
                                                    <input type="radio" id="{{$certificateTemplate->id}}" name="certificate_template_id" class="custom-control-input" value="{{$certificateTemplate->id}}" {{$edit ? $batchCertificate->certificate_template_id==$certificateTemplate->id ? 'checked':'':''}} >
                                                    <label class="custom-control-label" for="{{$certificateTemplate->id}}">
                                                        <img class="figure-img"
                                                            src={{asset('storage/'.$certificateTemplate->image)}}
                                                                width="200" height="120"
                                                            alt="certificate"/>
                                                    </label>

                                                </div>
                                            </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            @else
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label for="title">{{ __('admin.certificate.certificate_template')}} <span> <a href="{{route('admin.certificate-templates.index')}}">Add more template</a>  </span></label>
                                        <div class="row">
                                            @foreach(\App\Models\BatchCertificate::CERTIFICATE_TEMPLATE as $id=>$image)
                                            <div class="col-sm-3">
                                                <div class="custom-control custom-radio">
                                                    <input type="radio" id="{{$id}}" name="tamplate" class="custom-control-input" value="{{$id}}" {{$edit ? $batchCertificate->tamplate==$id ? 'checked':'':''}} >
                                                    <label class="custom-control-label" for="{{$id}}">
                                                        <img class="figure-img"
                                                            src={{asset('assets/'.$image)}}
                                                                width="200" height="120"
                                                            alt="certificate"/>
                                                    </label>
                                                </div>
                                            </div>
                                            @endforeach

                                        </div>
                                    </div>
                                </div>
                            @endif

                            

                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="logo">{{ __('admin.certificate.signature') }} <span
                                            style="color: red"> * </span></label>
                                    <div class="input-group">
                                        <div class="logo-upload-section">
                                            <div class="avatar-preview text-center">
                                                <label for="signature">
                                                    <img class="figure-img"
                                                         src={{ $edit && !empty($batchCertificate->signature) ? asset('storage/'. $batchCertificate->signature) :  "https://via.placeholder.com/350x350?text=Institute+Logo"}}
                                                             width="200" height="100"
                                                         alt="certificate"/>
                                                    <span class="p-1 bg-gray"
                                                          style="position: relative; right: 0; bottom: 50%; border: 2px solid #afafaf; border-radius: 50%;margin-left: -31px; overflow: hidden">
                                                        <i class="fa fa-pencil-alt text-white"></i>
                                                    </span>
                                                </label>
                                            </div>
                                            <input type="file" name="signature" style="display: none"
                                                   id="signature">
                                        </div>

                                    </div>
                                    <p class="font-italic text-secondary m-0 p-0">
                                        (Image max 500kb, file type
                                        must be jpeg,jpg,png or gif)</p>
                                </div>
                            </div>
                            <input type="hidden" value="{{$edit ? $batchCertificate->id : ''}}" name="batchCertificate_id">


                            <div class="col-sm-12 text-right">
                                <button type="submit"
                                        class="btn btn-success">{{ $edit ? __('admin.common.update') : __('admin.common.add')}}</button>
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
        const EDIT = !!'{{$edit}}';

        const editAddForm = $('.edit-add-form');
        editAddForm.validate({
            rules: {
                authorized_by: {
                    required: true,
                },
                institute_id: {
                    required: true
                },
                issued_date: {
                    required: true
                },
                tamplate: {
                    required: true
                },
            },
            messages: {
            },
            submitHandler: function (htmlForm) {
                $('.overlay').show();
                htmlForm.submit();
            }
        });




        function readURL(input) {
            if (input.files && input.files[0]) {
                let reader = new FileReader();
                reader.onload = function (e) {
                    $(input).parent().find('.avatar-preview img').attr('src', e.target.result);
                }
                reader.readAsDataURL(input.files[0]); // convert to base64 string
            }
        }

        $(document).ready(function () {
            $('#signature').change(async function () {
                await readURL(this); //preview image
                editAddForm.validate().element("#signature");
            });
        })
    </script>
@endpush


