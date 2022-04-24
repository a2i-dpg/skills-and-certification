@php
    $currentInstitute = app('currentInstitute');
    $layout = 'master::layouts.front-end';
@endphp
@extends($layout)

@section('title')
@endsection

@push('css')
    <style>
    </style>

@endpush

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="card mt-4">
                    <div class="card-header">
                        <div class="card-title">{{__('frontend.trainee.trainee-information')}}</div>
                        <div class="card-tools">
                            <a href="{{route('frontend.trainee-enrolled-courses')}}"
                               class="btn btn-sm btn-outline-primary btn-rounded">
                                <i class="fas fa-backward"></i> {{ __('generic.back_to_list') }}
                            </a>
                        </div>
                    </div>

                    <div class="card-body">
                        <form action="{{ route('certificate-request.store') }}"
                              method="post"
                              enctype="multipart/form-data" class="certificate-request-form">
                            @csrf
                            <div class="form-row">

                                <div class="form-group col-md-12">
                                    <label for="name">{{__('frontend.trainee.batch_name')}}:</label>
                                    <h3 > {{ $batch->title }} </h3>
                                </div>

                                <div class="form-group col-md-6">
                                    <label for="name">{{ __('generic.name') }}<span
                                            class="required">*</span> :</label>
                                    <input type="text" class="form-control" name="name" id="name"
                                           placeholder="{{ __('generic.name') }}" value="{{ $info['name'] }}"
                                            {{empty($info['edit'])? '' : 'disabled'}}
                                    >
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="name">{{ __('generic.father_name') }}<span
                                            class="required">*</span> :</label>
                                    <input type="text" class="form-control" name="father_name" id="father_name"
                                           placeholder="{{ __('generic.father_name') }}" value="{{  old('father_name',$info['father'])  }}"
                                            {{empty($info['edit'])? '' : 'disabled'}}
                                    >
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="name">{{ __('generic.mother_name') }}<span
                                            class="required">*</span> :</label>
                                    <input type="text" class="form-control" name="mother_name" id="mother_name"
                                           placeholder="{{ __('generic.mother_name') }}" value="{{ old('mother_name',$info['mother']) }}"
                                            {{empty($info['edit'])? '' : 'disabled'}}
                                    >
                                </div>

                                <div class="form-group col-md-6">
                                    <label for="date_of_birth">{{ __('generic.date_of_birth') }}<span
                                            class="required">*</span> :</label>
                                    <input type="text" class="flat-date form-control" name="date_of_birth"
                                           id="date_of_birth"
                                           placeholder="{{ __('generic.date_of_birth') }}"
                                           value="{{ $info['date_of_birth'] }}"
                                            {{empty($info['edit'])? '' : 'disabled'}}
                                    >
                                </div>
                                @if(empty($info['edit']))
                                    <div class=" form-group col-md-6">
                                        <label for="id_image">{{ __('generic.id_proof') }}
                                            <span class="required">*</span> :
                                        </label>
                                        <div class="input-group">
                                            <div class="custom-file">
                                                <input type="file" class="custom-file-input" id="id_image" name="id_image">
                                                <label class="custom-file-label" for="id image">Upload image</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <input type="submit" class="btn btn-primary float-right ml-2"
                                               value="{{ __('admin.common.request') }}">
                                    </div>
                                @endif
                                <input type="hidden" name="trainee_course_enrolls_id" value="{{$enroll_id}}"/>


                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <x-generic-validation-error-toastr></x-generic-validation-error-toastr>

    <script>
        const certificateRequestForm = $('.certificate-request-form');

        certificateRequestForm.validate({
            rules: {
                name: {
                    required: true,
                },
                father_name: {
                    required: true,

                },
                mother_name: {
                    required: true,

                },
                date_of_birth: {
                    required: true,
                },
                id_image: {
                    required: true,
                },
            }
        });

        $(".custom-file-input").on("change", function() {
            var fileName = $(this).val().split("\\").pop();
            $(this).siblings(".custom-file-label").addClass("selected").html(fileName);
        });

    </script>
@endpush
