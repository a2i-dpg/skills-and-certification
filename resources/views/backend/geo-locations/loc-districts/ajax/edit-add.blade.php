@php
    $edit = !empty($locDistrict->id);
    $authUser = \App\Helpers\Classes\AuthHelper::getAuthUser();
@endphp

<div class="modal-header custom-bg-gradient-info">
    <h4 class="modal-title">
        <i class="fas fa-eye"></i> {{!$edit ? 'Add District': 'Update District'}}
    </h4>
    <button type="button" class="close" data-dismiss="modal"
            aria-label="{{ __('voyager::generic.close') }}">
        <span aria-hidden="true">&times;</span>
    </button>
</div>

<div class="modal-body">
    <div class="card card-outline">
        <div class="card-body">
            <form class="row edit-add-form" method="post"
                  action="{{$edit ? route('admin.loc-districts.update', $locDistrict->id) : route('admin.loc-districts.store')}}"
                  enctype="multipart/form-data">
                @csrf
                @if($edit)
                    @method('put')
                @endif

                <div class="col-sm-6 col-md-4">
                    <label for="name">Title <span style="color: red"> * </span></label>
                    <input type="text" class="form-control" name="title" value="{{$edit ? $locDistrict->title : ''}}"/>
                </div>
                <div class="col-sm-6 col-md-4">
                    <label for="bbs_code">BBS Code <span style="color: red"> * </span></label>
                    <input type="text" class="form-control" name="bbs_code" value="{{$edit ? $locDistrict->bbs_code : ''}}"/>
                </div>
                <div class="col-sm-6 col-md-4">
                    <label for="loc_division_id">{{ __('Division') }} <span
                            style="color: red"> * </span></label>
                    <select class="form-control select2-ajax-wizard"
                            name="loc_division_id"
                            data-model="{{base64_encode(App\Models\LocDivision::class)}}"
                            data-label-fields="{title}"
                            @if($edit)
                            data-preselected-option="{{json_encode(['text' =>  $locDistrict->division->title, 'id' =>  $locDistrict->division->id])}}"
                            @endif
                            data-placeholder="{{ __('generic.select_placeholder') }}"
                    >
                    </select>
                </div>

                <div class="col-sm-12 text-right mt-2">
                    <button type="submit" class="btn btn-success">{{$edit ? 'Update' : 'Create'}}</button>
                </div>
            </form>
        </div><!-- /.card-body -->
        <div class="overlay" style="display: none">
            <i class="fas fa-2x fa-sync-alt fa-spin"></i>
        </div>
    </div>
</div>
