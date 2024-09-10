@extends('layouts.adminlogin')
@section('page_meta')
    <meta name="description" content="Property Seeling Website.">
    <meta name="keywords" content="property, sales, leases, buy">
    <meta name="author" content="pixelstrap">
    <title>Admin - {{ $site_settings->site_name }}</title>
@endsection
@section('page_content')
<div class="page-wrapper" id="main-wrapper" data-layout="vertical" data-navbarbg="skin6" data-sidebartype="full"
data-sidebar-position="fixed" data-header-position="fixed">
<div
  class="position-relative overflow-hidden auth-bg min-vh-100 w-100 d-flex align-items-center justify-content-center">
  <div class="d-flex align-items-center justify-content-center w-100">
    <div class="row justify-content-center w-100">
      <div class="col-md-8 col-lg-6 col-xxl-3">
        <div class="card mb-0">
          <div class="card-body">
            <a href="{{ url('admin/login') }}" class="text-nowrap logo-img text-center d-block py-3 w-100">
              <img class="for-light"
              src="{{ !empty($site_settings) ? get_site_image_src('images', $site_settings->site_logo) : get_site_image_src('images', '') }}" alt="{{ !empty($site_settings) ? $site_settings->site_name : 'Login' }}">
            </a>
            <p class="text-center">{{ !empty($site_settings) ? $site_settings->site_name : 'Login' }}</p>
            <form class="theme-form" action="" method="post">
                @csrf
                                <h4>Change Password</h4>
                                <p>Enter your password & confirm password</p>
                                {!!showMessage()!!}
              <div class="mb-3">
                <label for="exampleInputEmail1" class="form-label">Current Password</label>
                <input class="form-control @error('current_password') is-invalid @enderror"
                                        type="password" name="current_password" placeholder="*********" value="">
                    @error('current_password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
              </div>
              <div class="mb-4">
                <label for="" class="form-label">New Password</label>
                <input class="form-control @error('new_password') is-invalid @enderror"
                type="password" name="new_password" placeholder="*********">
                @error('new_password')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>
              <div class="mb-4">
                <label for="" class="form-label">Confirm Password</label>
                <input class="form-control @error('confirm_password') is-invalid @enderror"
                                            type="password" name="confirm_password" placeholder="*********">
                                        @error('confirm_password')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
              </div>
              
              <button class="btn btn-primary w-100 py-8 fs-4 mb-4 rounded-2" type="submit">Change Password</button>
              
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
</div>
    
@endsection
