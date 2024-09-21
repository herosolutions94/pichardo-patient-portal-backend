@extends('layouts.adminlayout')
@section('page_meta')
    <meta name="description" content={{ !empty($site_settings) ? $site_settings->site_meta_desc : '' }}">
    <meta name="keywords" content="{{ !empty($site_settings) ? $site_settings->site_meta_keyword : '' }}">
    <meta name="author" content="{{ !empty($site_settings->site_name) ? $site_settings->site_name : 'Login' }}">
    <title>Admin - {{ $site_settings->site_name }}</title>
@endsection
@section('page_content')
    @if (request()->segment(3) == 'view')
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
            @if(!empty($rows->messages))
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center gap-3 mb-4">
                            <h4 class="mb-0 fw-semibold">Comments</h4>
                            <span class="badge bg-primary-subtle text-primary fs-4 fw-semibold px-6 py-8 rounded">{{count($rows->messages)}}</span>
                        </div>
                        <div class="position-relative">

                                <div class="p-4 rounded-2 text-bg-light mb-3">
                                    <div class="d-flex align-items-center gap-3 text-bg-light-primary rounded-2 p-4">
                                        <img src="{{ get_site_image_src('images', $site_settings->site_icon) }}" alt="matdash-img" class="rounded-circle" width="50" height="50">
                                        <div>
                                            <span class="p-1 d-inline-block fs-2 mb-1">{{format_date($rows->created_at,'M d, Y')}}</span>
                                            <h6 class="fw-semibold mb-0 fs-4">Support Team</h6>
                                        </div>
                                    </div>
                                    <p class="my-3">{!! $site_settings->generate_questions !!}</p>
                                </div>
                                @foreach($rows->messages as $message)
                                <div class="p-4 rounded-2 text-bg-light mb-3">
                                    <div class="d-flex align-items-center gap-3 text-bg-light-primary rounded-2 p-4">
                                        @if($message->receiver_id === 1)
                                        <img src="{{ get_site_image_src('members', $rows->member_row->mem_image) }}" alt="matdash-img" class="rounded-circle" width="50" height="50">
                                        <div>
                                            <span class="p-1 d-inline-block fs-2 mb-1">{{format_date($message->created_at,'M d, Y')}}</span>
                                            <h6 class="fw-semibold mb-0 fs-4">{{$rows->member_row->mem_fullname}}</h6>
                                        </div>
                                        @else
                                        <img src="{{ get_site_image_src('images', $site_settings->site_icon) }}" alt="matdash-img" class="rounded-circle" width="50" height="50">
                                        <div>
                                            <span class="p-1 d-inline-block fs-2 mb-1">{{format_date($message->created_at,'M d, Y')}}</span>
                                            <h6 class="fw-semibold mb-0 fs-4">Support Team</h6>
                                        </div>
                                        @endif
                                        
                                    </div>
                                    <p class="my-3">{!! $message->msg !!}</p>
                                    @if($message->attachments->count() > 0)
                                    <div class="mb-3">
                                        <h6 class="fw-semibold mb-0 text-dark mb-3">
                                        Attachments
                                        </h6>
                                        <div class="d-block d-sm-flex align-items-center gap-4">
                                        @foreach($message->attachments as $attachment)
                                        <a href="{{ get_site_image_src('attachments', !empty($attachment) ? $attachment->file : '') }}" class="hstack gap-3 mb-2 mb-sm-0" target="_blank">
                                            <div class="d-flex align-items-center gap-3">
                                                <div class="rounded-1 p-6 attachment-icon">
                                                    <img src="{{ asset('admin/images/file.png')}}" alt="matdash-img" width="24" height="24">
                                                </div>
                                            </div>
                                        </a>
                                        @endforeach
                                        </div>
                                    </div>
                                    @endif
                                </div>
                                @endforeach
                        </div>
                        @if($rows->status == 'closed')
                        <div class="alert alert-danger">Request has been closed!</div>
                        @else
                        <h4 class="mb-4 fw-semibold">Post Comments</h4>
                        <form method="post" action="{{ url('admin/requests/post-comment/'.$rows->id) }}" enctype="multipart/form-data">
                            @csrf                
                            <textarea class="form-control mb-4" rows="5" name="comment" required=""></textarea>
                            <div class="mb-3">
                                <label for="formFileMultiple" class="form-label">Select attachments</label>
                                <input class="form-control" type="file" name="attachments[]" id="formFileMultiple" multiple />
                            </div>
                            <button class="btn btn-primary" type="submit">Post Comment</button>
                        </form>
                        @endif
                    </div>
                </div>
            @endif
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
