@extends('layouts.adminlayout')
@section('page_meta')
    <meta name="description" content={{ !empty($site_settings) ? $site_settings->site_meta_desc : '' }}">
    <meta name="keywords" content="{{ !empty($site_settings) ? $site_settings->site_meta_keyword : '' }}">
    <meta name="author" content="{{ !empty($site_settings->site_name) ? $site_settings->site_name : 'Login' }}">
    <title>Admin - {{ $site_settings->site_name }}</title>
@endsection
@section('page_content')
    {!! breadcrumb('Create Invoice') !!}
    <div class="card">
        <div class="card-body">
            <div class="row card-body mb-4">
                <div class="col-lg-6">
                    <h4>Billing to</h4>
                    <p>{{$row->mem_fullname}}</p>
                    <p>{{$row->mem_address1}}</p>
                    <p>{{$row->mem_email}}</p>
                </div>
                <div class="col-lg-6">
                    <h4>Billing from</h4>
                    
                </div>
                <div class="col-lg-6">
                    <div class="d-flex align-items-center gap-2 pb-7">
                        <h4 class="mb-0">Tax(%)</h4>
                        <h5 class="mb-0">{{$site_settings->site_processing_fee}}</h5>
                    </div>
                </div>
            </div>
            <form class="form theme-form" method="post" action="" enctype="multipart/form-data">
            
            </form>
        </div>
    </div>
@endsection
