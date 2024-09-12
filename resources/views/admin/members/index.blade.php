@extends('layouts.adminlayout')
@section('page_meta')
    <meta name="description" content={{ !empty($site_settings) ? $site_settings->site_meta_desc : '' }}">
    <meta name="keywords" content="{{ !empty($site_settings) ? $site_settings->site_meta_keyword : '' }}">
    <meta name="author" content="{{ !empty($site_settings->site_name) ? $site_settings->site_name : 'Login' }}">
    <title>Admin - {{ $site_settings->site_name }}</title>
@endsection
@section('page_content')
    @if (request()->segment(3) == 'edit' || request()->segment(3) == 'add')
    {!!breadcrumb('Add/Update Member')!!}
    <form class="form theme-form" method="post" action='' enctype="multipart/form-data"
    id="saveForm">
    @csrf
    <div class="card">
        <div class="card-body">
            <div class="row">
            
                <div class="col-lg-6 d-flex align-items-stretch">
                    <div class="card w-100 border position-relative overflow-hidden">
                      <div class="card-body p-4">
                        <h4 class="card-title">Change Image</h4>
                        <div class="text-center">
                            <div class="file_choose_icon">
                                <img src="{{ get_site_image_src('members', !empty($row) ? $row->mem_image : '') }}" alt="matdash-img" class="img-fluid" width="120" height="120">
                            </div>
                            <input class="form-control uploadFile" name="mem_image" type="file"
                            data-bs-original-title='' title=''>
                          <p class="mb-0">Allowed JPG, GIF or PNG. Max size of 800K</p>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="col-lg-6 d-flex align-items-stretch">
                    <div class="card w-100 border position-relative overflow-hidden">
                      <div class="card-body p-4">
                        <h4 class="card-title">Personal Information Block</h4>
                          <div class="mb-3">
                            <label for="mem_fullname" class="form-label">Name</label>
                            <input type="text" class="form-control" name="mem_fullname" value="{{!empty($row) ? $row->mem_fullname : ''}}">
                          </div>
                          {{-- <div class="mb-3">
                            <label for="mem_display_name" class="form-label">Display Name</label>
                            <input type="text" class="form-control" name="mem_display_name" value="{{!empty($row) ? $row->mem_display_name : ''}}">
                          </div> --}}
                          <div class="mb-3">
                            <label for="mem_email" class="form-label">Email</label>
                            <input type="email" class="form-control" name="mem_email" value="{{!empty($row) ? $row->mem_email : ''}}" readonly disabled />
                          </div>
                          <div class="mb-3">
                            <label for="mem_phone" class="form-label">Phone</label>
                            <input type="text" class="form-control" name="mem_phone" value="{{!empty($row) ? $row->mem_phone : ''}}" />
                          </div>
                          {{-- <div class="mb-3">
                            <label for="mem_phone" class="form-label">ABN</label>
                            <input type="text" class="form-control" name="mem_buisness_phone" value="{{!empty($row) ? $row->mem_buisness_phone : ''}}" />
                          </div> --}}
                          
                          
                      </div>
                    </div>
                  </div>
                  <div class="col-lg-12 d-flex align-items-stretch">
                    <div class="card w-100 border position-relative overflow-hidden">
                      <div class="card-body p-4">
                        <h4 class="card-title">General Information Block</h4>
                        <div class="row">
                            <div class="col-12">
                                <label for="name" class="form-label">Address</label>
                                <textarea class="form-control" name="mem_address1">{{ !empty($row) ? $row->mem_address1 : '' }}</textarea>
                              </div>
                        </div>
                        <div class="row mb-4">
                            <div class="col-12">
                                <label for="name" class="form-label">Personal Bio</label>
                                <textarea class="form-control" name="mem_bio">{{ !empty($row) ? $row->mem_bio : '' }}</textarea>
                              </div>
                        </div>
                        <div class="row">
                            <div class="col-4">
                                <div class="form-check form-switch py-2">
                                    <input class="form-check-input success" type="checkbox" id="color-success"  {{ !empty($row) ? ($row->mem_status == 1 ? 'checked' : '') : '' }} name="mem_status" />
                                    <label class="form-check-label" for="color-success"> {{ !empty($row) ? ($row->mem_status == 0 ? 'InActive' : 'Active') : 'Status' }}</label>
                                  </div>
                              </div>
                            <div class="col-4">
                                <div class="form-check form-switch py-2">
                                    <input class="form-check-input success" type="checkbox" id="color-success"  {{ !empty($row) ? ($row->mem_email_verified == 1 ? 'checked' : '') : '' }} name="mem_email_verified" />
                                    <label class="form-check-label" for="color-success"> {{ !empty($row) ? ($row->mem_email_verified == 0 ? 'Not Email Verified' : 'Email Verified') : 'Email Verification' }}</label>
                                  </div>
                              </div>
                            <div class="col-4">
                                <div class="form-check form-switch py-2">
                                    <input class="form-check-input success" type="checkbox" id="color-success"  {{ !empty($row) ? ($row->mem_verified == 1 ? 'checked' : '') : '' }} name="mem_verified" />
                                    <label class="form-check-label" for="color-success"> {{ !empty($row) ? ($row->mem_verified == 0 ? 'Not Verified' : 'Account Verified') : 'Account Verified' }}</label>
                                  </div>
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
    {!!breadcrumb('Members')!!}
    <div class="card">
      <div class="card-body">
          <div class="row">
              <div class="table-responsive">  
                  <table id="zero_config" class="table table-striped table-bordered text-nowrap align-middle">
                      <thead>
                        <!-- start row -->
                        <tr>
                          <th>Sr#</th>
                          <th>Seller</th>
                          <th>OTP</th>
                          <th>Account Type</th>
                          <th>Status</th>
                          <th>Is Verified?</th>
                          {{-- <th>ID Verification?</th> --}}
                          <th>Action</th>
                        </tr>
                        <!-- end row -->
                      </thead>
                      <tbody>
                      @if (!empty($rows))
                          @foreach ($rows as $key => $row)
                        <tr>
                          <td class="sorting_1">{{ $key + 1 }}</td>
                          <td>
                            <div class="d-flex align-items-center">
                                <img src="{{ get_site_image_src('members', !empty($row->mem_image) ? $row->mem_image : '') }}" class="rounded-circle" width="25" height="25">
                                <div class="ms-3">
                                  <h6 class="fs-4 fw-semibold mb-0">{{$row->mem_fullname}}</h6>
                                  <span class="fw-normal">{{$row->mem_email}}</span><br>
                                  {{-- <span class="fw-normal">{{!empty($row->mem_buisness_phone) ? "ABN: ".$row->mem_buisness_phone : ''}}</span> --}}
                                </div>
                            </div>
                          </td>
                          <td>{!! ($row->otp) !!}</td>
                          <td>{!! userAccountType($row->googleId) !!}</td>
                          <td>{!! getStatus($row->mem_status) !!}</td>
                          <td>{!! getFeatured($row->mem_verified) !!}</td>      
                          {{-- <td>{!! !empty($row->id_verification_status) ? getUserIdStatus($row->id_verification_status) : "N/A" !!}</td>       --}}
                          <td>
                              <div class="dropdown dropstart">
                                  <a href="javascript:void(0)" class="text-muted" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="ti ti-dots-vertical fs-6"></i>
                                  </a>
                                  <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                    <li>
                                      <a class="dropdown-item d-flex align-items-center gap-3" href="{{ url('admin/members/edit/' . $row->id) }}">
                                        <i class="fs-4 ti ti-edit"></i>Edit
                                      </a>
                                    </li>
                                    <li>
                                      <a class="dropdown-item d-flex align-items-center gap-3" href="{{ url('admin/members/delete/' . $row->id) }}"  onclick="return confirm('Are you sure?');">
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
