@extends('layouts.adminlayout')
@section('page_meta')
    <meta name="description" content={{ !empty($site_settings) ? $site_settings->site_meta_desc : '' }}">
    <meta name="keywords" content="{{ !empty($site_settings) ? $site_settings->site_meta_keyword : '' }}">
    <meta name="author" content="{{ !empty($site_settings->site_name) ? $site_settings->site_name : 'Login' }}">
    <title>Admin - {{ $site_settings->site_name }}</title>
@endsection
@section('page_content')
{!!breadcrumb('Subscribers')!!}
    <div class="card">
      <div class="card-body">
          <div class="row">
              <div class="table-responsive">  
                  <table id="zero_config" class="table table-striped table-bordered text-nowrap align-middle">
                        <thead>
                            <tr>
                                <th>Sr#</th>
                                <th>Email</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if (!empty($rows))
                                @foreach ($rows as $key => $row)
                                    <tr>
                                        <td class="sorting_1">{{ $key + 1 }}</td>
                                        <td>{{ $row->email }}</td>
                                        <td>{!! getReadStatus($row->status) !!}</td>
                                        <td class="action">
                                            <a href="{{ url('admin/subscribers/delete/' . $row->id) }}" class="text-dark delete ms-2" onclick="return confirm('Are you sure?');">
                                                <i class="ti ti-trash fs-5"></i>
                                              </a>
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr class="odd">
                                    <td colspan="6">No record(s) found!</td>
                                </tr>
                            @endif



                        </tbody>
                    </table>
                </div>
            </div>
        </div>
      </div>
    @endsection
