@php
    $edit = !empty($locDivision->id);
    $authUser = \App\Helpers\Classes\AuthHelper::getAuthUser();
@endphp

<div class="modal-header custom-bg-gradient-info">
    <h4 class="modal-title">
        <i class="fas fa-eye"></i> {{!$edit ? 'Add Division': 'Update Division'}}
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
                  action="{{$edit ? route('admin.loc-divisions.update', $locDivision->id) : route('admin.loc-divisions.store')}}"
                  enctype="multipart/form-data">
                @csrf
                @if($edit)
                    @method('put')
                @endif

                <div class="col-sm-6 col-md-4">
                    <label for="name">Title <span style="color: red"> * </span></label>
                    <input type="text" class="form-control" name="title" value="{{$edit ? $locDivision->title : ''}}"/>
                </div>
                <div class="col-sm-6 col-md-4">
                    <label for="name">BBS Code <span style="color: red"> * </span></label>
                    <input type="text" class="form-control" name="bbs_code" value="{{$edit ? $locDivision->bbs_code : ''}}"/>
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
