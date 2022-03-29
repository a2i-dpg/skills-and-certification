@extends('master::layouts.front-end')
@section('title', 'verify email')

@section('full_page_content')
    <div class="container h-100 mt-5 outer-card">
        <div class="row h-100">
            <div class="col-md-12 align-self-center">
                <div class="row inner-card justify-content-center">
                    <div class="card">
                        <div class="card-body">
                            <div class="col-md-12">
                                <h1 class="text-center">Welcome</h1>
                                <div class="row justify-content-center">
                                    <img src=" https://img.icons8.com/clouds/100/000000/handshake.png"
                                         alt="welcome image" width="125" height="120"
                                         style="display: block; border: 0;">
                                </div>
                                <h2 class="text-center">A verification email has sent to your email.Please verify your
                                    email by clicking the given link or
                                    click resend if you not get one.</h2>
                            </div>

                            <div class="col-md-12">
                                <div class="row justify-content-center">
                                    <form action="{{ route('verification.resend') }}" method="post">
                                        @csrf
                                        <button type="submit" class="btn-lg btn-primary">Resend</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('css')
    <style>
        .outer-card {
            position: relative;
        }

        .inner-card {
            /*position: absolute;*/
            /*top: 140px;*/
            /*left: 144px;*/
        }
    </style>
@endpush
