@extends('layouts.adminlogin')
@section('page_meta')
    <meta name="description" content="Property Seeling Website.">
    <meta name="keywords" content="property, sales, leases, buy">
    <meta name="author" content="pixelstrap">
    <title>Admin - Register</title>
@endsection
@section('page_content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12 p-0">
                <div class="login-card">
                    <div>
                        <div><a class="logo text-start" href="index.html"><img class="img-fluid for-light"
                                    src="{{ asset('admin/images/logo/login.png') }}" alt="looginpage"><img
                                    class="img-fluid for-dark" src="{{ asset('admin/images/logo/logo_dark.png') }}"
                                    alt="looginpage"></a></div>
                        <div class="login-main">
                            <form class="theme-form" action="{{ url('admin/register') }}" method="post">
                                @csrf
                                <h4>Create an account</h4>
                                <p>Enter your information to signup</p>

                                <div class="form-group">
                                    <label class="col-form-label">Username</label>
                                    <input class="form-control @error('email') is-invalid @enderror" type="text"
                                        name="site_username" placeholder="Test@gmail.com"
                                        value="{{ old('site_username') }}">
                                    @error('site_username')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label class="col-form-label">Password</label>
                                    <div class="form-input position-relative">
                                        <input class="form-control @error('site_password') is-invalid @enderror"
                                            type="password" name="site_password" placeholder="*********">
                                        @error('site_password')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                </div>

                                <div class="form-group mb-0">
                                    <div class="text-end mt-3">
                                        <button class="btn btn-primary btn-block w-100" type="submit">Create
                                            Account</button>
                                    </div>
                                </div>

                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- latest jquery-->

    </div>
@endsection
