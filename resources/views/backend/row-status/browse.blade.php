@extends('master::layouts.master')
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header text-primary custom-bg-gradient-info">
                        <h3 class="card-title font-weight-bold">Row Status List</h3>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <table id="dataTable" class="table table-striped table-bordered table-sm">
                            <thead>
                            <tr>
                                <th>SL#</th>
                                <th>Title</th>
                                <th>Code</th>
                            </tr>
                            </thead>
                            <tbody>
                            @php
                                $sl = 0;
                            @endphp

                            @foreach($rowStatuses as $rowStatus)
                                <tr>
                                    <td>{{ ++$sl }}</td>
                                    <td>{{ $rowStatus->title }}</td>
                                    <td>{{ $rowStatus->code }}</td>
                                </tr>
                            @endforeach

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('utils.delete-confirm-modal')
@endsection

