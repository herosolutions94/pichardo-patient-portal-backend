@extends('layouts.adminlayout')
@section('page_meta')
    <meta name="description" content={{ !empty($site_settings) ? $site_settings->site_meta_desc : '' }}">
    <meta name="keywords" content="{{ !empty($site_settings) ? $site_settings->site_meta_keyword : '' }}">
    <meta name="author" content="{{ !empty($site_settings->site_name) ? $site_settings->site_name : 'Login' }}">
    <title>Admin - {{ $site_settings->site_name }}</title>
@endsection
@section('page_content')
    @if (request()->segment(3) == 'edit' || request()->segment(3) == 'add')
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
                        <h4 class="card-title">Module Details</h4>
                          <div class="mb-3">
                            <label for="permission" class="form-label">Module Name</label>
                            <input type="text" class="form-control" name="permission" value="{{!empty($row) ? $row->permission : ""}}">
                          </div>
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
    </form>
   
    @else
    {!!breadcrumb('Permissions',url('admin/permissions/add/'))!!}
    <div class="card">
      <div class="card-body">
          <div class="row">
              <div class="table-responsive">  
                  <table id="zero_config" class="table table-striped table-bordered text-nowrap align-middle">
                      <thead>
                        <!-- start row -->
                        <tr>
                          <th>Sr#</th>
                          <th>Module Name</th>
                        </tr>
                        <!-- end row -->
                      </thead>
                      <tbody>
                      @if (!empty($rows))
                          @foreach ($rows as $key => $row)
                        <tr>
                          <td class="sorting_1">{{ $key + 1 }}</td>
                          <td>{!! ($row->permission) !!}</td>
                          <td>
                              <div class="dropdown dropstart">
                                  <a href="javascript:void(0)" class="text-muted" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="ti ti-dots-vertical fs-6"></i>
                                  </a>
                                  <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                    <li>
                                      <a class="dropdown-item d-flex align-items-center gap-3" href="{{ url('admin/permissions/edit/' . $row->id) }}">
                                        <i class="fs-4 ti ti-edit"></i>Edit
                                      </a>
                                    </li>
                                    <li>
                                      <a class="dropdown-item d-flex align-items-center gap-3" href="{{ url('admin/permissions/delete/' . $row->id) }}"  onclick="return confirm('Are you sure?');">
                                        <i class="fs-4 ti ti-trash"></i>Delete
                                      </a>
                                    </li>
                                  </ul>
                                </div>
                          </td>
                        </tr>
                        @endforeach
                        @else
                              <tr class="odd">
                                  <td colspan="4">No record(s) found!</td>
                              </tr>
                          @endif
                      </tbody>
                  </table>
              </div>
          </div>
      </div>
    </div>
    @endif
@endsection
