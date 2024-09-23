@extends('layouts.adminlayout')
@section('page_meta')
    <meta name="description" content={{ !empty($site_settings) ? $site_settings->site_meta_desc : '' }}">
    <meta name="keywords" content="{{ !empty($site_settings) ? $site_settings->site_meta_keyword : '' }}">
    <meta name="author" content="{{ !empty($site_settings->site_name) ? $site_settings->site_name : 'Login' }}">
    <title>Admin - {{ $site_settings->site_name }}</title>
@endsection
@section('page_content')
    @if (request()->segment(3) == 'view')
        {!! breadcrumb('Invoices') !!}
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card border shadow-none">
                            <div class="card-body p-4">
                                
                                <div class="d-flex align-items-center justify-content-between pb-7">
                                    <div><h4>#{{$invoice_id}}</h4></div>
                                    <div>
                                        {{format_american_date($rows->created_at,'M d, Y')}}
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-6">
                                        <h5>Billing to</h5>
                                        <div class="d-flex align-items-center justify-content-between py-3 border-top">
                                            <p class="mb-0">{{ $rows->member_data->mem_fullname }}</p>
                                        </div>
                                        <div class="d-flex align-items-center justify-content-between">
                                            <p class="mb-0">{{ $rows->member_row->address }}</p>
                                        </div>
                                        <div class="d-flex align-items-center justify-content-between">
                                            <p class="mb-0">{{ $rows->member_data->email }}</p>
                                        </div>
                                    </div>

                                    <div class="col-lg-6">
                                        <h5>Billing from</h5>
                                    </div>

                                </div>
                                
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
        </div>

            
    @else
        {!! breadcrumb('View Invoices') !!}
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
                                    <th>Status</th>
                                    <th>Issue Date</th>
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
                                                    <img src="{{ get_site_image_src('members', !empty($row->member_data->mem_image) ? $row->member_data->mem_image : '') }}" width="45" class="rounded-circle" />
                                                    <h6 class="mb-0">{{ $row->member_data->mem_fullname }}</h6>
                                                </div>
                                            </td>
                                            <td>{!! getInvoiceStatus($row->status) !!}</td>
                                            <td>{{format_american_date($row->created_at,'M d, Y')}}</td>
                                            <td>
                                                <div class="dropdown dropstart">
                                                    <a href="javascript:void(0)" class="text-muted" id="dropdownMenuButton"
                                                        data-bs-toggle="dropdown" aria-expanded="false">
                                                        <i class="ti ti-dots-vertical fs-6"></i>
                                                    </a>
                                                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                                        <li>
                                                            <a class="dropdown-item d-flex align-items-center gap-3"
                                                                href="{{ url('admin/invoice/view/' . $row->id) }}">
                                                                <i class="fs-4 ti ti-eye"></i>View
                                                            </a>
                                                        </li>
                                                        
                                                        <li>
                                                            <a class="dropdown-item d-flex align-items-center gap-3"
                                                                href="{{ url('admin/invoice/delete/' . $row->id) }}"
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
