@extends('master::layouts.master')
@section('title')
    Update User Type
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between custom-header custom-bg-gradient-info">
                        <h3 class="card-title font-weight-bold">Update User Type</h3>
                        <div class="card-tools">
                            <a href="{{route('admin.user-types.index')}}"
                               class="btn btn-sm btn-outline-primary btn-rounded">
                                <i class="fas fa-backward"></i> {{__('generic.back')}}
                            </a>
                        </div>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <form
                            action="{{route('admin.user-types.update',$userType->id)}}"
                            method="POST" class="edit-add-form">
                            @csrf
                            @method('put')
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label for="title">Title<span style="color:red">*</span></label>
                                    <input type="text" class="form-control custom-input-box" name="title" id="title"
                                           value="{{!empty($userType->title)?$userType->title: old('title')}}"
                                           placeholder="Title" required>
                                </div>

                                <div class="form-group col-md-6">
                                    <label for="default_role_id">Default Role <span style="color:red">*</span></label>
                                    <select class="form-control select2-ajax-wizard"
                                            name="default_role_id"
                                            id="default_role_id"
                                            data-model="{{base64_encode(App\Models\Role::class)}}"
                                            data-label-fields="{title}"
                                            @if ($userType->role)
                                            data-preselected-option="{{json_encode(['text' =>  optional($userType->role)->title, 'id' =>  optional($userType->role)->id])}}"
                                            @endif
                                            data-placeholder="{{ __('generic.select_placeholder') }}"
                                    >
                                    </select>
                                </div>
                                <div class="col-sm-12 text-right">
                                    <button type="submit" class="btn btn-success">{{ __('Update')}}</button>
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

@push('js')
    <script>
        const editAddForm = $('.edit-add-form');
        editAddForm.validate({
            rules: {
                title: {
                    required: true,
                },
                code: {
                    required: true
                },
                default_role: {
                    required: true,
                },
                status: {
                    required: true
                }
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


