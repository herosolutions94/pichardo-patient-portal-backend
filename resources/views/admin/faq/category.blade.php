@extends('layouts.adminlayout')
@section('page_meta')
    <meta name="description" content={{ !empty($site_settings) ? $site_settings->site_meta_desc : '' }}">
    <meta name="keywords" content="{{ !empty($site_settings) ? $site_settings->site_meta_keyword : '' }}">
    <meta name="author" content="{{ !empty($site_settings->site_name) ? $site_settings->site_name : 'Login' }}">
    <title>Admin - {{ $site_settings->site_name }}</title>
@endsection
@section('page_content')
@if (request()->segment(3) == 'edit' || request()->segment(3) == 'add')
    {!!breadcrumb('Add/Update FAQ Category')!!}
    <form class="form theme-form" method="post" action="" enctype="multipart/form-data"
    id="saveForm">
    @csrf
    <div class="card">
        <div class="card-body">
            <div class="row">
                  <div class="col-lg-12 d-flex align-items-stretch">
                    <div class="card w-100 border position-relative overflow-hidden">
                      <div class="card-body p-4">
                        <h4 class="card-title">Category Block</h4>
                          <div class="mb-3">
                            <label for="name" class="form-label">Name</label>
                            <input type="text" class="form-control" name="name" value="{{!empty($row->name) ? $row->name : ""}}">
                          </div>
                          <div class="mb-3">
                            <div class="form-check form-switch py-2">
                                <input class="form-check-input success" type="checkbox" id="color-success"  {{ !empty($row) ? ($row->status == 1 ? 'checked' : '') : '' }} name="status" />
                                <label class="form-check-label" for="color-success"> {{ !empty($row) ? ($row->status == 0 ? 'InActive' : 'Active') : 'Status' }}</label>
                              </div>
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
{!!breadcrumb('FAQ Categories',url('admin/faq_categories/add/'))!!}
<div class="card">
  <div class="card-body">
      <div class="row">
          <div class="table-responsive">  
            <table id="zero_config" class="table table-striped table-bordered text-nowrap align-middle">
                <thead>
                  <!-- start row -->
                  <tr>
                    <th>Sr#</th>
                    <th>Category</th>
                    <th>Status</th>
                    <th>Action</th>
                  </tr>
                  <!-- end row -->
                </thead>
                <tbody>
                @if (!empty($rows))
                    @foreach ($rows as $key => $row)
                  <tr>
                    <td>{{ $key + 1 }}</td>
                    <td>
                      <div class="d-flex align-items-center gap-6 crud_thumbnail_icon">
                        <h6 class="mb-0"> {{ $row->name }}</h6>
                      </div>

                    </td>
                    <td>{!! getStatus($row->status) !!}</td>        
                    <td>
                        <div class="dropdown dropstart">
                            <a href="javascript:void(0)" class="text-muted" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                              <i class="ti ti-dots-vertical fs-6"></i>
                            </a>
                            <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                              <li>
                                <a class="dropdown-item d-flex align-items-center gap-3" href="{{ url('admin/faq_categories/edit/' . $row->id) }}">
                                  <i class="fs-4 ti ti-edit"></i>Edit
                                </a>
                              </li>
                              <li>
                                <a class="dropdown-item d-flex align-items-center gap-3" href="{{ url('admin/faq_categories/delete/' . $row->id) }}"  onclick="return confirm('Are you sure?');">
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
