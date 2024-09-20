@extends('layouts.adminlayout')
@section('page_meta')
    <meta name="description" content={{ !empty($site_settings) ? $site_settings->site_meta_desc : '' }}">
    <meta name="keywords" content="{{ !empty($site_settings) ? $site_settings->site_meta_keyword : '' }}">
    <meta name="author" content="{{ !empty($site_settings->site_name) ? $site_settings->site_name : 'Login' }}">
    <title>Admin - {{ $site_settings->site_name }}</title>
@endsection
@section('page_content')
    @if (request()->segment(3) == 'view')
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif
        {!! breadcrumb('Requests') !!}
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card border shadow-none">
                            <div class="card-body p-4">
                                
                                <div class="d-flex align-items-center justify-content-between pb-7">
                                    <div>
                                        <h4 class="card-title mb-3">Request Details</h4>
                                        {{format_date($rows->created_at,'M d, Y')}}
                                    </div>
                                    <div>
                                        {!! getRequestsStatus($rows->status) !!}
                                    </div>
                                </div>
                                <div class="d-flex align-items-center justify-content-between py-3 border-top">
                                    <div>
                                        <h5 class="fs-4 fw-semibold mb-0">Patient</h5>
                                    </div>
                                    <div class="d-flex align-items-center gap-6 crud_thumbnail_icon">
                                        <img src="{{ get_site_image_src('members', !empty($rows->member_row->mem_image) ? $rows->member_row->mem_image : '') }}" width="45" class="rounded-circle" />
                                        <h6 class="mb-0">{{ $rows->member_row->mem_fullname }}</h6>
                                    </div>
                                </div>
                                <div class="d-flex align-items-center justify-content-between py-3 border-top">
                                    <div>
                                        <h5 class="fs-4 fw-semibold mb-0">Subject</h5>
                                    </div>
                                    <p class="mb-0">{{$rows->subject}}</p>
                                </div>
                                <div class="d-flex align-items-center justify-content-between py-3 border-top">
                                    <div>
                                        <h5 class="fs-4 fw-semibold mb-0">Prefered Pharmacy</h5>
                                    </div>
                                    <p class="mb-0">{{$rows->preferred_pharmacy}}</p>
                                </div>
                                <div class="d-flex align-items-center justify-content-between py-3 border-top">
                                    <div>
                                        <h5 class="fs-4 fw-semibold mb-0">Address</h5>
                                    </div>
                                    <p class="mb-0">{{$rows->address}}</p>
                                </div>
                                <div class="d-flex align-items-center justify-content-between py-3 border-top">
                                    <div>
                                        <h5 class="fs-4 fw-semibold mb-0">Symptoms</h5>
                                    </div>
                                    <p class="mb-0">{{$rows->symptoms}}</p>
                                </div>
                                <div class="d-flex align-items-center justify-content-between py-3 border-top">
                                    <div>
                                        <h5 class="fs-4 fw-semibold mb-0">Requested Medication</h5>
                                    </div>
                                    <p class="mb-0">{{$rows->requested_medication}}</p>
                                </div>
                                <div class="d-flex align-items-center justify-content-between py-3 border-top">
                                    <div>
                                        <h5 class="fs-4 fw-semibold mb-0">Attachment</h5>
                                    </div>
                                    <a href="{{ get_site_image_src('attachments', !empty($rows->document) ? $rows->document : '') }}">
                                    <img src="{{ get_site_image_src('attachments', !empty($rows->document) ? $rows->document : '') }}" width="145" height="100"/>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
        </div>
        <div class="card">
            <div class="card-body">
            @if($rows->status == "closed")
                <div class="alert alert-danger">
                    This request has been closed!!
                </div>
                @else
                <div class="chat_area">
                    <h4>Start Chat</h4>
                    <div class="d-flex patient-chat-box">
                        <div class="chat-box w-100">
                            <div class="chat-box-inner p-9">
                                <div class="chat-list chat active-chat">
                                    <!-- ==========start loop============= -->
                                    <div class="hstack gap-3 align-items-start mb-7">
                                        <div>
                                            <div className="buble you ">
                                                <div className="ico">
                                                    <img src="http://127.0.0.1:8000/storage/members//BFgcrTEBPPH6GwTFwZOUZf0dwnc3ugFQETwBdPAC.png" width="45" class="rounded-circle"/>
                                                </div>
                                                <div className="txt">
                                                    <div className="time">34/43/4343</div>
                                                    <div className="cntnt">
                                                        some text will be here from patient about request
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- =============end loop============== -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
            </div>
        </div>
        
    @else
        {!! breadcrumb('Manage Requests') !!}
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="table-responsive">
                        <table id="zero_config" class="table table-striped table-bordered text-nowrap align-middle">
                            <thead>
                                <!-- start row -->
                                <tr>
                                    <th>Sr#</th>
                                    <th>Patient</th>
                                    <th>Subject</th>
                                    <th>Date</th>
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
                                                <div class="d-flex align-items-center gap-6 crud_thumbnail_icon">
                                                    <img src="{{ get_site_image_src('members', !empty($row->member_row->mem_image) ? $row->member_row->mem_image : '') }}" width="45" class="rounded-circle" />
                                                    <h6 class="mb-0">{{ $row->member_row->mem_fullname }}</h6>
                                                </div>
                                            </td>
                                            <td>{{ $row->subject }}</td>
                                            <td>{{format_date($row->created_at,'M d, Y')}}</td>
                                            <td>{!! getRequestsStatus($row->status) !!}</td>
                                            <td>
                                                <div class="dropdown dropstart">
                                                    <a href="javascript:void(0)" class="text-muted" id="dropdownMenuButton"
                                                        data-bs-toggle="dropdown" aria-expanded="false">
                                                        <i class="ti ti-dots-vertical fs-6"></i>
                                                    </a>
                                                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                                        <li>
                                                            <a class="dropdown-item d-flex align-items-center gap-3"
                                                                href="{{ url('admin/requests/view/' . $row->id) }}">
                                                                <i class="fs-4 ti ti-eye"></i>View
                                                            </a>
                                                        </li>
                                                        @if($row->status == "new")
                                                            <li>
                                                                <a class="dropdown-item d-flex align-items-center gap-3"
                                                                    href="{{ url('admin/requests/edit/' . $row->id) }}">
                                                                    <i class="fs-4 ti ti-check"></i>Mark as in progress
                                                                </a>
                                                            </li>
                                                        
                                                        @endif
                                                        
                                                        <li>
                                                            <a class="dropdown-item d-flex align-items-center gap-3"
                                                                href="{{ url('admin/requests/delete/' . $row->id) }}"
                                                                onclick="return confirm('Are you sure?');">
                                                                <i class="fs-4 ti ti-trash"></i>Delete
                                                            </a>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
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
