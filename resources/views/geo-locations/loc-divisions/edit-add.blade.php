@extends('core.main')

@php
    $edit = !empty($locDivision->id);
@endphp

@section('content')
    <div class="card card-outline">
        <div class="card-header d-flex justify-content-between">
            <h3 class="card-title">{{!$edit ? 'Add Division': 'Update Division'}}</h3>
            <a href="{{route('admin.loc-divisions.index')}}" class="btn btn-sm btn-outline-primary">
                <i class="fas fa-backward"></i> {{__('admin.common.back')}}
            </a>
        </div>
        <div class="card-body">
            <form class="row edit-add-form" method="post"
                  action="{{$edit ? route('admin.loc-divisions.update', $locDivision->id) : route('admin.loc-divisions.store')}}">
                @csrf
                @if($edit)
                    @method('put')
                @endif
                <div class="col-sm-6 col-md-4">
                    <x-input.text name="title" id="title" label="Title"
                                  defaultValue="{{$edit ? $locDivision->title : ''}}"></x-input.text>
                </div>

                <div class="col-sm-6 col-md-4">
                    <x-input.text name="title" label="Title"
                                  defaultValue="{{$edit ? $locDivision->title : ''}}"></x-input.text>
                </div>
                <div class="col-sm-6 col-md-4">
                    <x-input.text name="bbs_code" label="BBS Code"
                                  defaultValue="{{$edit ? $locDivision->bbs_code : ''}}"></x-input.text>
                </div>

                <div class="col-sm-12 text-right">
                    <button type="submit" class="btn btn-success">{{$edit ? 'Update' : 'Create'}}</button>
                </div>
            </form>
        </div><!-- /.card-body -->
        <div class="overlay" style="display: none">
            <i class="fas fa-2x fa-sync-alt fa-spin"></i>
        </div>
    </div>
@endsection

@push('js')
    <x-generic-validation-error-toastr/>
    <script>
        const editAddForm = $('.edit-add-form');
        editAddForm.validate({
            rules: {
                title: {
                    required: true
                },
                bbs_code: {
                    required: true,
                    maxlength: 2
                }
            },
            submitHandler: function (htmlForm) {
                $('.overlay').show();
                htmlForm.submit();
            }
        });
    </script>
@endpush
