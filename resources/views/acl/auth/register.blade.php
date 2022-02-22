@extends('master::layouts.front-end')
@section('content')
    <div class="container-fluid pb-5">
        <div class="row justify-content-center align-items-center">
            <div class="col-md-4">
                <div class="card shadow-lg">
                    <div class="card-header text-center">
                        <i class="fas fa-registered fa-4x text-success"></i>
                    </div>
                    <div class="card-body login-card-body form-area">
                        <p class="text-center font-weight-bold">Create new account</p>
                        <form class="login-form" action="{{route('admin.register')}}" method="post">
                            {{ csrf_field() }}
                            <div class="form-group">
                                <div class="input-group mb-3">
                                    <input name="name" id="name" type="text" class="form-control"
                                           placeholder="Name" value="{{ old('name') }}">
                                    <div class="input-group-append">
                                        <div class="input-group-text input-group-text-border">
                                            <i class="fas fa-user"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="input-group mb-3">
                                    <input name="email" id="email" type="email" class="form-control"
                                           placeholder="Email" value="{{ old('email') }}">
                                    <div class="input-group-append">
                                        <div class="input-group-text input-group-text-border">
                                            <i class="fas fa-envelope"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="input-group mb-3">
                                    <input name="password" id="password" type="password" class="form-control"
                                           placeholder="Password" value="{{ old('password') }}">
                                    <div class="input-group-append">
                                        <div class="input-group-text input-group-text-border">
                                            <i class="fas fa-lock"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="input-group mb-3">
                                    <input name="password_confirmation" id="password_confirmation" type="password"
                                           class="form-control"
                                           placeholder="Retype Password">
                                    <div class="input-group-append">
                                        <div class="input-group-text input-group-text-border">
                                            <i class="fas fa-lock"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-8">
                                    <div class="icheck-primary">
                                        <input type="checkbox" id="remember">
                                        <label for="remember">
                                            Accept terms and condition
                                        </label>
                                    </div>
                                </div>
                                <!-- /.col -->
                                <div class="col-4">
                                    <button type="submit" class="btn btn-primary btn-block form-submit-btn">Sign Up</button>
                                </div>
                                <!-- /.col -->
                            </div>
                        </form>
                        <p class="mt-3">Already have an account? <a href="{{route('login')}}">Log into your account</a></p>

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

@push('js')
    <x-generic-validation-error-toastr/>

    <script>
        const loginForm = $('.login-form');
        loginForm.validate({
            rules: {
                name: {
                    required: true
                },
                email: {
                    required: true,
                    email:true
                },
                password: {
                    required: true,
                    minlength:8
                },
                password_confirmation: {
                    equalTo: "#password"
                }
            },
            submitHandler: function (htmlForm) {
                $('.overlay').show();
                htmlForm.submit();
            }
        });
    </script>
@endpush
