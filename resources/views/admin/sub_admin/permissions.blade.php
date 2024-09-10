@extends('layouts.adminlayout')
@section('page_meta')
    <meta name="description" content={{ !empty($site_settings) ? $site_settings->site_meta_desc : '' }}">
    <meta name="keywords" content="{{ !empty($site_settings) ? $site_settings->site_meta_keyword : '' }}">
    <meta name="author" content="{{ !empty($site_settings->site_name) ? $site_settings->site_name : 'Login' }}">
    <title>Admin - {{ $site_settings->site_name }}</title>
@endsection
@section('page_content')
    {!!breadcrumb('Add/Update Permissions')!!}
<form class="form theme-form" method="post" action="" enctype="multipart/form-data"
    id="saveForm">
    @csrf
    <div class="card">
        <div class="card-body">
            <div class="row">
                  <div class="col-lg-12 d-flex align-items-stretch">
                    <div class="card w-100 border position-relative overflow-hidden">
                      <div class="card-body p-4">
                        <h4 class="card-title">Permissions to {{$row->site_admin_name}}</h4>
                        <div class="row">
                            @foreach($permissions as $permission)
                                <div class="col-sm-4">
                                    <div class="form-check py-2 form-switch">
                                    <input class="form-check-input" type="checkbox" id="{{$permission->permission}}" value="{{$permission->id}}" name="permissions[]" {{ $permission->subPermissions->isNotEmpty() ? 'checked' : '' }}>
                                    <label class="form-check-label" for="{{$permission->permission}}">{{$permission->permission}}</label>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <div class="d-flex align-items-center justify-content-end mt-4 gap-6">
                                <button class="btn btn-primary" type="submit">Update</button>
                                </div>
                            </div>
                        </div>
                      </div>
                    </div>
                  </div>
                  
            </div>
        </div>
    </div>
</form>
@endsection