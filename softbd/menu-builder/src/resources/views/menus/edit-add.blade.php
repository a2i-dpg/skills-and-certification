@extends('menu-builder::core.main')

@php
    $edit = !empty($menu->id);
@endphp

@section(config('menu-builder.template.content_placeholder', 'content'))
    <div class="card card-outline">
        <div class="card-header d-flex justify-content-between">
            <h3 class="card-title">{{!$edit ? 'Add Menu': 'Update Menu'}}</h3>
            <a href="{{route('menu-builder.menus.index')}}" class="btn btn-sm btn-outline-secondary">
                <i class="fas fa-backward"></i> Back to list
            </a>
        </div>
        <div class="card-body">
            <form class="row edit-add-form" method="post"
                  action="{{$edit ? route('menu-builder.menus.update', $menu->id) : route('menu-builder.menus.store')}}">
                @csrf
                @if($edit)
                    @method('put')
                @endif
                <div class="col-sm-12">
                    <div class="form-group">
                        <label for="name">
                            {{ __('Name') }}
                        </label>
                        <input
                            type="text"
                            class="form-control"
                            id="name"
                            name="name"
                            value="{{$edit ? $menu->name : ''}}"
                            placeholder="{{ __('Name') }}"
                        >
                    </div>
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

@push(config('menu-builder.template.js_placeholder', 'js'))
    <script>
        @if (!empty($errors) && $errors->any())
        @foreach ($errors->all() as $error)
        toastr.error("{{ $error }}");
        @endforeach
        @endif
    </script>
    <script>
        const editAddForm = $('.edit-add-form');
        editAddForm.validate({
            rules: {
                name: {
                    required: true
                }
            },
            submitHandler: function (htmlForm) {
                $('.overlay').show();
                htmlForm.submit();
            }
        });
    </script>
@endpush
