@php
    $edit = !empty($event->id);
    /** @var \App\Models\User $authUser */
    $authUser = \App\Helpers\Classes\AuthHelper::getAuthUser();
@endphp
@extends('master::layouts.master')

@section('title')
    {{ $edit? __('admin.event.add'): __('admin.event.edit') }}
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header text-primary custom-bg-gradient-info">
                        <h3 class="card-title font-weight-bold">{{ $edit? __('admin.event.edit'): __('admin.event.add') }}</h3>

                        <div class="card-tools">
                            <a href="{{route('admin.events.index')}}"
                               class="btn btn-sm btn-outline-primary btn-rounded">
                                <i class="fas fa-backward"></i> {{__('admin.common.back')}}
                            </a>
                        </div>

                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <form
                            action="{{$edit ? route('admin.events.update', $event->id) : route('admin.events.store')}}"
                            method="POST" class="edit-add-form" enctype="multipart/form-data">
                            @csrf
                            @if($edit)
                                @method('put')
                            @endif
                            <div class="form-row">
                                @if($authUser->isUserBelongsToInstitute())
                                    <input type="hidden" id="institute_id" name="institute_id"
                                           value="{{$authUser->institute_id}}">
                                @else
                                    <div class="form-group col-md-6">
                                        <label for="institute_id">{{ __('admin.event.institute_title')}} <span
                                                style="color: red"> * </span></label>
                                        <select class="form-control select2-ajax-wizard"
                                                name="institute_id"
                                                id="institute_id"
                                                data-model="{{base64_encode(\App\Models\Institute::class)}}"
                                                data-label-fields="{title}"
                                                data-dependent-fields="#branch_id"
                                                @if($edit)
                                                data-preselected-option="{{json_encode(['text' =>  $event->institute->title, 'id' =>  $event->institute->id])}}"
                                                @endif
                                                data-placeholder="{{ __('admin.event.institute_title')}}"
                                        >
                                        </select>
                                    </div>
                                @endif

                                <div class="form-group col-md-6">
                                    <label for="caption">{{ __('admin.event.caption')}} <span style="color: red"> * </span></label>
                                    <input type="text" class="form-control custom-input-box" name="caption"
                                           id="caption"
                                           value="{{$edit ? $event->caption : old('caption')}}"
                                           placeholder="{{ __('admin.event.caption')}}" required>
                                </div>
                                <div class="form-group col-md-6 event-image">
                                    <label for="image">{{ __('admin.event.image')}} </label>
                                    <input type="file" class="form-control custom-input-box" name="image"
                                           id="image"
                                           value="{{$edit ? $event->image : old('image')}}"
                                           placeholder="{{ __('admin.event.image')}}">
                                </div>

                                <div class="form-group col-md-6">
                                    <label for="date">{{ __('admin.event.date')}} <span style="color: red"> * </span></label>
                                    <input type="text" class="flat-datetime flat-date-custom-bg" name="date"
                                           id="date"
                                           value="{{$edit ? $event->date : old('date')}}"
                                           placeholder="{{ __('admin.event.date')}}">
                                </div>

                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label for="details">{{ __('admin.event.detail')}} <span style="color: red"> * </span></label>
                                        <textarea class="form-control" id="address" name="details"
                                                  placeholder="{{ __('admin.event.detail')}}"
                                                  rows="3">{{ $edit ? $event->details : old('details') }}</textarea>
                                    </div>
                                </div>


                                <div class="col-sm-12 text-right">
                                    <button type="submit"
                                            class="btn btn-success">{{ $edit ? __('admin.event.update') : __('admin.event.add') }}</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('utils.delete-confirm-modal')

@endsection

@push('css')
    <style>
        .required {
            color: red;
        }

        .flat-date-custom-bg {
            background-color: #fafdff !important;
        }

        #date-error, #institute_id-error{
            position: absolute;
            left: 5px;
            bottom: 0;
        }
    </style>
@endpush

@push('js')
    <x-generic-validation-error-toastr/>
    <script>
        const EDIT = !!'{{$edit}}';
        const INSTITUTE_USER = !!'{{$authUser->isUserBelongsToInstitute()}}';
        const editAddForm = $('.edit-add-form');


        /**
         * start event image dimension validation
         * */
        $.validator.addMethod('dimension', function(value, element, param) {
            if(element.files.length === 0){
                return true;
            }
            let width = $(element).data('imageWidth');
            let height = $(element).data('imageHeight');
            if(width === param[0] && height === param[1]){
                return true;
            }else{
                return false;
            }
        },'Please upload an image with 1200 x 675 pixels dimension');

        $('#image').change(function() {
            $('#image').removeData('imageWidth');
            $('#image').removeData('imageHeight');
            var file = this.files[0];
            var tmpImg = new Image();
            tmpImg.src=window.URL.createObjectURL( file );
            tmpImg.onload = function() {
                width = tmpImg.naturalWidth,
                    height = tmpImg.naturalHeight;
                $('#image').data('imageWidth', width);
                $('#image').data('imageHeight', height);
            }
        });
        /** end event image dimension validation  */



        editAddForm.validate({
            rules: {
                institute_id: {
                    required: function (){
                        if(!INSTITUTE_USER){
                            return true;
                        }else{
                            return false;
                        }
                    }
                },
                caption: {
                    required: true,
                },
                image: {
                    required: false,
                    accept: "image/*",
                    dimension: [1200,675],
                },
                date: {
                    required: true,
                },
                details: {
                    required: true,
                },
            },
            messages: {

            },
            submitHandler: function (htmlForm) {
                $('.overlay').show();
                htmlForm.submit();
            }
        });
    </script>
@endpush



