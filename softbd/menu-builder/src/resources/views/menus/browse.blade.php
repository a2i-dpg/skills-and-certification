@extends('menu-builder::core.main')

@section('title')
    {{ __('Menu Builder') }}
@endsection

@section(config('menu-builder.template.content_placeholder', 'content'))
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <h3 class="card-title font-weight-bold">Menu List</h3>

                    <div class="btn-group">
                        <a href="{{route('menu-builder.menus.create')}}" class="btn btn-sm btn-primary">
                            <i class="fas fa-plus-circle"></i> Add new
                        </a>

                        <a href="#" class="btn btn-sm btn-outline-success export">
                            <i class="fas fa-file-export"></i> Export Menu
                        </a>

                        <a href="#" class="btn btn-sm btn-outline-danger import">
                            <i class="fas fa-file-import"></i> Import Menu
                        </a>
                    </div>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    <table id="dataTable" class="table table-striped table-bordered table-sm">
                        <thead>
                        <tr>
                            <th>SL#</th>
                            <th>Menu</th>
                            <th>Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($menus as $i => $menu)
                            <tr>
                                <td>{{$i + 1}}</td>
                                <td>{{$menu->name}}</td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <a href="{{route('menu-builder.menus.builder', $menu->id)}}"
                                           class="btn btn-sm btn-outline-success">
                                            <i class="fas fa-building"></i> Builder
                                        </a>
                                        <a href="{{route('menu-builder.menus.edit', $menu->id)}}"
                                           class="btn btn-sm btn-outline-warning">
                                            <i class="fas fa-edit"></i> Edit
                                        </a>
                                        <a href="#" data-action="{{route('menu-builder.menus.destroy', $menu->id)}}"
                                           class="btn btn-sm delete btn-outline-danger">
                                            <i class="fas fa-trash"></i> Delete
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="1000">
                                    Empty table
                                </td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal modal-danger fade" tabindex="-1" id="delete_modal" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title"><i class="voyager-trash"></i> {{ __('Are you sure?') }}</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label=""><span
                            aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <p>This action is permanent.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default pull-right"
                        data-dismiss="modal">{{ __('Cancel') }}</button>
                <form action=""
                      id="delete_form"
                      method="POST">
                    {{ method_field("DELETE") }}
                    {{ csrf_field() }}
                    <input type="submit" class="btn btn-danger pull-right delete-confirm"
                           value="{{ __('Delete') }}">
                </form>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<div class="modal modal-danger fade" tabindex="-1" id="export_modal" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title"><i class="voyager-trash"></i> {{ __('Are you sure?') }}</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label=""><span
                            aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <p>This action will export all menu to json file.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default pull-right"
                        data-dismiss="modal">{{ __('Cancel') }}</button>
                <form action=""
                      id="export_form"
                      method="POST">
                    {{ csrf_field() }}
                    <input type="submit" class="btn btn-success pull-right export-confirm"
                           value="{{ __('Export') }}">
                </form>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<div class="modal modal-danger fade" tabindex="-1" id="import_modal" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title"><i class="voyager-trash"></i> {{ __('Are you sure?') }}</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label=""><span
                            aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <p>This action will import all menu from json file and replace current menu.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default pull-right"
                        data-dismiss="modal">{{ __('Cancel') }}</button>
                <form action=""
                      id="import_form"
                      method="POST">
                    {{ csrf_field() }}
                    <input type="submit" class="btn btn-danger pull-right import-confirm"
                           value="{{ __('Import') }}">
                </form>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
@endsection

@push(config('menu-builder.template.js_placeholder', 'js'))
<script>
    $(function () {
        $(document, 'td').on('click', '.delete', function (e) {
            $('#delete_form')[0].action = $(this).data('action');
            $('#delete_modal').modal('show');
        });
        $(document, 'td').on('click', '.export', function (e) {
            $('#export_form')[0].action = '{{route('menu-builder.menus.export')}}';
            $('#export_modal').modal('show');
        });
        $(document, 'td').on('click', '.import', function (e) {
            $('#import_form')[0].action = '{{route('menu-builder.menus.import')}}';
            $('#import_modal').modal('show');
        });
    });
</script>
@endpush
