@extends('layouts.adminlayout')
@section('page_meta')
    <meta name="description" content={{ !empty($site_settings) ? $site_settings->site_meta_desc : '' }}">
    <meta name="keywords" content="{{ !empty($site_settings) ? $site_settings->site_meta_keyword : '' }}">
    <meta name="author" content="{{ !empty($site_settings->site_name) ? $site_settings->site_name : 'Login' }}">
    <title>Admin - {{ $site_settings->site_name }}</title>
@endsection
@section('page_content')
    @if (request()->segment(3) == 'view')
    {!!breadcrumb('ID Verification Details')!!}
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card border shadow-none">
                      <div class="card-body p-4">
                        <h4 class="card-title mb-3">ID Verification Details</h4>
                        <div class="d-flex align-items-center justify-content-between pb-7">
                        </div>
                        <div class="d-flex align-items-center justify-content-between py-3 border-top">
                          <div>
                            <h5 class="fs-4 fw-semibold mb-0">User Name</h5>
                          </div>
                          <div class="d-flex align-items-center">
                                <img src="{{ get_site_image_src('members', !empty($row->member_row) ? $row->member_row->mem_image : '') }}" class="rounded-circle" width="40" height="40">
                                <div class="ms-3">
                                <h6 class="fs-4 fw-semibold mb-0">{{ $row->member_row->mem_fullname }}</h6>
                                </div>
                            </div>
                        </div>
                        <div class="d-flex align-items-center justify-content-between py-3 border-top">
                            <div>
                              <h5 class="fs-4 fw-semibold mb-0">Selfie</h5>
                            </div>
                           
                           <div class="p-4 d-flex align-items-center gap-3">
                            <img src="{{ get_site_image_src('attachments', !empty($row->selfie) ? $row->selfie : '') }}" alt="" class="object-fit-cover rounded-4" width="250" height="200">
                            <a href="{{ get_site_image_src('attachments', !empty($row->selfie) ? $row->selfie : '') }}" download target="_blank" class="btn btn-info">Download</a>
                          </div>
                        </div>
                        <div class="d-flex align-items-center justify-content-between py-3 border-top">
                            <div>
                              <h5 class="fs-4 fw-semibold mb-0">CNIC</h5>
                            </div>
                           
                           <div class="p-4 d-flex align-items-center gap-3">
                            <img src="{{ get_site_image_src('attachments', !empty($row->cnic) ? $row->cnic : '') }}" alt="" class="object-fit-cover rounded-4" width="250" height="200">
                            <a href="{{ get_site_image_src('attachments', !empty($row->cnic) ? $row->cnic : '') }}" download target="_blank" class="btn btn-info">Download</a>
                          </div>
                        </div>
                        <div class="d-flex align-items-center justify-content-between py-3 border-top">
                            <div>
                              <h5 class="fs-4 fw-semibold mb-0">CNIC With Selfie</h5>
                            </div>
                           
                           <div class="p-4 d-flex align-items-center gap-3">
                            <img src="{{ get_site_image_src('attachments', !empty($row->cnic_selfie) ? $row->cnic_selfie : '') }}" alt="" class="object-fit-cover rounded-4" width="250" height="200">
                            <a href="{{ get_site_image_src('attachments', !empty($row->cnic_selfie) ? $row->cnic_selfie : '') }}" download target="_blank" class="btn btn-info">Download</a>
                          </div>
                        </div>
                        
                        <div class="d-flex align-items-center justify-content-between py-3 border-top">
                            <div>
                              <h5 class="fs-4 fw-semibold mb-0">Status</h5>
                            </div>
                            <p class="mb-0">{!! getUserIdStatus($row->status) !!}</p>
                        </div>
                      </div>
                    </div>
                  </div>
            </div>
        </div>
    </div>
    
    @else
    {!!breadcrumb('Member ID Verifications')!!}
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="table-responsive">  
                    <table id="zero_config" class="table table-striped table-bordered text-nowrap align-middle">
                        <thead>
                        <!-- start row -->
                        <tr>
                            <th>Sr#</th>
                            <th>User Name</th>
                            <th>Status</th>
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
                                    <img src="{{ get_site_image_src('members', !empty($row->member_row) ? $row->member_row->mem_image : '') }}" class="rounded-circle" width="40" height="40">
                                    <div class="ms-3">
                                      <h6 class="fs-4 fw-semibold mb-0">{{ $row->member_row->mem_fullname }}</h6>
                                    </div>
                                </div>
                            </td>
                            <td>{!! getUserIdStatus($row->status) !!}</td>      
                            <td>
                                <div class="dropdown dropstart">
                                    <a href="javascript:void(0)" class="text-muted" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="ti ti-dots-vertical fs-6"></i>
                                    </a>
                                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                    <li>
                                        <a class="dropdown-item d-flex align-items-center gap-3" href="{{ url('admin/user_id_verifications/view/' . $row->id) }}">
                                        <i class="fs-4 ti ti-eye"></i>View
                                        </a>
                                    </li>
                                    @if($row->status=='verified')
                                    <li>
                                        <a class="dropdown-item d-flex align-items-center gap-3" href="{{ url('admin/user_id_verifications/unverified/' . $row->id) }}"  onclick="return confirm('Are you sure?');">
                                        <i class="fs-4 ti ti-check"></i>Mark as Unverified
                                        </a>
                                    </li>
                                    @endif
                                    @if($row->status=='requested')
                                    <li>
                                        <a class="dropdown-item d-flex align-items-center gap-3" href="{{ url('admin/user_id_verifications/verified/' . $row->id) }}"  onclick="return confirm('Are you sure?');">
                                        <i class="fs-4 ti ti-check"></i>Mark as Verified
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item d-flex align-items-center gap-3" href="{{ url('admin/user_id_verifications/unverified/' . $row->id) }}"  onclick="return confirm('Are you sure?');">
                                        <i class="fs-4 ti ti-check"></i>Mark as Unverified
                                        </a>
                                    </li>
                                    @endif
                                    <li>
                                        <a class="dropdown-item d-flex align-items-center gap-3" href="{{ url('admin/user_id_verifications/delete/' . $row->id) }}"  onclick="return confirm('Are you sure?');">
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
