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
                        <h3 class="card-title font-weight-bold text-primary">{{ ! $edit ?  __('admin.certificate.batch_certificate_add')  : __('admin.certificate.batch_certificate_update') }}</h3>
                        <div>
                            <a href="{{route('admin.batches.index')}}" class="btn btn-sm btn-rounded btn-outline-primary">
                                <i class="fas fa-backward"></i>{{__('admin.common.back')}}
                            </a>
                        </div>
                    </div>

                    <div class="card-body">
                        <form class="row edit-add-form" method="post"
                              action="{{ $edit ? route('admin.batch.certificates.store', [$batchCertificate->id]) : route('admin.batch.certificates.store')}}">
                            @csrf
                            {{--@if($edit)
                                @method('put')
                            @endif--}}

                            <div class="col-sm-6">
                                <div class="form-group">
                                    <input type="hidden" name="batch_id" id="batch_id" value="{{$batchCertificate->id}}">
                                    <label for="title">{{ __('admin.certificate.course_title')}}</label>
                                    <input type="text" class="form-control" id="title"
                                           name="title"
                                           value="Course Title" disabled>
                                </div>
                            </div>

                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="title">{{ __('admin.certificate.batch_title')}}</label>
                                    <input type="text" class="form-control" id="title"
                                           name="title"
                                           value="Batch Title" disabled>
                                </div>
                            </div>

                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="authorized_by">{{ __('admin.certificate.authorized_by')}}</label>
                                    <input type="text" class="form-control" id="authorized_by"
                                           name="authorized_by"
                                           value="">
                                </div>
                            </div>

                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="issued_date">{{ __('admin.certificate.issue_date')}}</label>
                                    <input type="text" class="flat-date flat-date-custom-bg" id="issued_date"
                                           name="issued_date"
                                           value="{{old('issued_date') }}"
                                    >
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="logo">{{ __('admin.certificate.signature') }} <span
                                            style="color: red"> * </span></label>
                                    <div class="input-group">
                                        <div class="logo-upload-section">
                                            <div class="avatar-preview text-center">
                                                <label for="signature">
                                                    <img class="figure-img"
                                                         src={{ $edit && !empty($institute->signature) ? asset('storage/'. $institute->signature) :  "https://via.placeholder.com/350x350?text=Institute+Logo"}}
                                                             width="200" height="100"
                                                         alt="Institute logo"/>
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

                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="title">{{ __('admin.certificate.certificate_template')}}</label>
                                    <input type="text" class="form-control" id="tamplate"
                                           name="tamplate"
                                           value="Batch Title">
                                </div>
                            </div>

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
                title: {
                    required: true,
                },
                institute_id: {
                    required: true
                },
                course_id: {
                    required: true
                },
                code: {
                    required: true,
                    number: true,
                    min: 1,
                },
                max_student_enrollment: {
                    required: true,
                    number: true,
                    min: 1,
                },
                start_date: {
                    required: true,
                },
                end_date: {
                    required: true,
                },
                start_time: {
                    required: true,
                },
                end_time: {
                    required: true,
                },

            },
            messages: {
                start_date: {
                  min: "Not valid",
                },
                end_date: {
                    min: "batch end date should be after batch start date",
                }
            },
            submitHandler: function (htmlForm) {
                $('.overlay').show();
                htmlForm.submit();
            }
        });


        $('#institute_id').on('change', function () {
            let id = $(this).val();
            $('#course_id').empty();
            $('#course_id').append(`<option value="0" disabled selected>Processing...</option>`);
            $.ajax({
                type: 'GET',
                url: '/getCourses/' + id,
                success: function (response) {
                    $('#course_id').empty();
                    $('#course_id').append(`<option value=""></option>`);
                    response.forEach(element => {
                        $('#course_id').append(`<option value="${element['id']}">${element['title']}</option>`);
                    });
                }
            });
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


