@extends('layouts.adminlayout')
@section('page_meta')
    <meta name="description" content={{ !empty($site_settings) ? $site_settings->site_meta_desc : '' }}">
    <meta name="keywords" content="{{ !empty($site_settings) ? $site_settings->site_meta_keyword : '' }}">
    <meta name="author" content="{{ !empty($site_settings->site_name) ? $site_settings->site_name : 'Login' }}">
    <title>Admin - {{ $site_settings->site_name }}</title>
@endsection
@section('page_content')

<div class="row">
    <div class="col-12">
      @if(access(3) || access(7) || access(8) || access(9))
        <div class="card">
            <div class="card-body pb-0" data-simplebar="">
                <div class="row flex-nowrap">
                  @if(access(3))
                    <div class="col">
                      <div class="card primary-gradient">
                        <div class="card-body text-center px-9 pb-4">
                          <div class="d-flex align-items-center justify-content-center round-48 rounded text-bg-primary flex-shrink-0 mb-3 mx-auto display-6">
                            <iconify-icon icon="lucide:users-round"></iconify-icon>
                          </div>
                          <h6 class="fw-normal fs-3 mb-1">Total Members</h6>
                          <h4 class="mb-3 d-flex align-items-center justify-content-center gap-1">
                            {{$members}}</h4>
                          <a href="{{url('/admin/members')}}" class="btn btn-white fs-2 fw-semibold text-nowrap">View
                            Details</a>
                        </div>
                      </div>
                    </div>
                    @endif
                    
                </div>
            </div>
        </div>
      @endif
      @if(access(5) || access(6) || access(10))
        <div class="card">
            <div class="card-body pb-0" data-simplebar="">
                <div class="row flex-nowrap">
                    @if(access(5))
                    <div class="col">
                      <div class="card primary-gradient">
                        <div class="card-body text-center px-9 pb-4">
                          <div class="d-flex align-items-center justify-content-center round-48 rounded text-bg-success flex-shrink-0 mb-3 mx-auto display-6">
                            <iconify-icon icon="tabler:message-user"></iconify-icon>
                          </div>
                          <h6 class="fw-normal fs-3 mb-1">Total Contact Messages</h6>
                          <h4 class="mb-3 d-flex align-items-center justify-content-center gap-1">
                            {{$contact}}</h4>
                          <a href="{{url('/admin/contact')}}" class="btn btn-white fs-2 fw-semibold text-nowrap">View
                            Details</a>
                        </div>
                      </div>
                    </div>
                    @endif
                    @if(access(6))
                    <div class="col">
                      <div class="card warning-gradient">
                        <div class="card-body text-center px-9 pb-4">
                          <div class="d-flex align-items-center justify-content-center round-48 rounded text-bg-info flex-shrink-0 mb-3 mx-auto display-6">
                            <iconify-icon icon="jam:newsletter"></iconify-icon>
                          </div>
                          <h6 class="fw-normal fs-3 mb-1">Total Subscribers</h6>
                          <h4 class="mb-3 d-flex align-items-center justify-content-center gap-1">
                            {{$subscribers}}</h4>
                          <a href="{{url('/admin/subscribers')}}" class="btn btn-white fs-2 fw-semibold text-nowrap">View
                            Details</a>
                        </div>
                      </div>
                    </div>
                    @endif
                   
                    
                </div>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
