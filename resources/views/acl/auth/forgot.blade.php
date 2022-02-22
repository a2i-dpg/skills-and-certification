@extends('master::layouts.front-end')

@section('content')
    <div class="container pb-5">
        <div class="row justify-content-center align-items-center">
            <div class="col-md-4">
                <div class="card shadow-lg">
                    <div class="card-header text-center">
                        <i class="fas fa-fingerprint fa-4x text-success"></i>
                    </div>
                    <div class="card-body login-card-body form-area">
                        <p class="text-center font-weight-bold">Recover Password</p>
                        <form class="login-form" action="" method="post">
                            {{ csrf_field() }}
                            <div class="form-group">
                                <div class="input-group mb-3">
                                    <input name="email" id="email" type="email" class="form-control"
                                           placeholder="Email/username">
                                    <div class="input-group-append">
                                        <div class="input-group-text input-group-text-border">
                                            <i class="fas fa-envelope"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-6">
                                    <div class="icheck-primary">

                                    </div>
                                </div>
                                <!-- /.col -->
                                <div class="col-6">
                                    <button type="submit" class="btn btn-primary btn-block form-submit-btn">Reset
                                        Password
                                    </button>
                                </div>
                                <!-- /.col -->
                            </div>
                        </form>
                        <p class="mt-3"><a href="{{route('admin.login')}}">Back to Login page</a></p>
                    </div>
                    <!-- /.login-card-body -->

                    <div class="overlay" style="display: none">
                        <i class="fas fa-2x fa-sync-alt fa-spin"></i>
                    </div>
                </div>
                <!-- /.login-box -->
            </div>
        </div>
    </div>
@endsection
@push('css')
    <style>

    </style>
@endpush

@push('js')
    <x-generic-validation-error-toastr/>
    <script>
        const loginForm = $('.login-form');
        loginForm.validate({
            rules: {
                email: {
                    required: true
                },
                password: {
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
