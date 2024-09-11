@extends('layouts.adminlayout')
@section('page_meta')
    <meta name="description" content={{ !empty($site_settings) ? $site_settings->site_meta_desc : '' }}">
    <meta name="keywords" content="{{ !empty($site_settings) ? $site_settings->site_meta_keyword : '' }}">
    <meta name="author" content="{{ !empty($site_settings->site_name) ? $site_settings->site_name : 'Login' }}">
    <title>Admin - {{ $site_settings->site_name }}</title>
@endsection
@section('page_content')
{!!breadcrumb('Website Pages')!!}
<div class="card">
    <div class="card-body">
        <div class="row">
            <div class="table-responsive">  
                <table class="table table-bordered text-nowrap align-middle dataTable basic-datatable">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Page Name</th>
                            <th scope="col">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>1</td>
                            <td width="65%">Home</td>
                            <td>
                                <a href="{{ url('admin/pages/home') }}" class="btn btn-primary active">Edit
                                    Page</a>
                            </td>
                        </tr>
                        {{-- <tr>
                            <td>2</td>
                            <td width="65%">How it works</td>
                            <td>
                                <a href="{{ url('admin/pages/how_it_works') }}" class="btn btn-primary active">Edit
                                    Page</a>
                            </td>
                        </tr> --}}
                        {{-- <tr>
                            <td>3</td>
                            <td width="65%">Help</td>
                            <td>
                                <a href="{{ url('admin/pages/help') }}" class="btn btn-primary active">Edit
                                    Page</a>
                            </td>
                        </tr> --}}
                        <tr>
                            <td>4</td>
                            <td width="65%">About</td>
                            <td>
                                <a href="{{ url('admin/pages/about') }}" class="btn btn-primary active">Edit
                                    Page</a>
                            </td>
                        </tr>
                        <tr>
                            <td>5</td>
                            <td width="65%">Services</td>
                            <td>
                                <a href="{{ url('admin/pages/services') }}" class="btn btn-primary active">Edit
                                    Page</a>
                            </td>
                        </tr>
                        <tr>
                            <td>6</td>
                            <td width="65%">Contact Us</td>
                            <td>
                                <a href="{{ url('admin/pages/contact') }}" class="btn btn-primary active">Edit
                                    Page</a>
                            </td>
                        </tr>
                        <tr>
                            <td>6</td>
                            <td width="65%">Privacy Policy</td>
                            <td>
                                <a href="{{ url('admin/pages/privacy_policy') }}" class="btn btn-primary active">Edit
                                    Page</a>
                            </td>
                        </tr>
                        <tr>
                            <td>7</td>
                            <td width="65%">Terms & Conditions</td>
                            <td>
                                <a href="{{ url('admin/pages/terms_conditions') }}" class="btn btn-primary active">Edit
                                    Page</a>
                            </td>
                        </tr>
                        <tr>
                            <td>8</td>
                            <td width="65%">Signup</td>
                            <td>
                                <a href="{{ url('admin/pages/signup') }}" class="btn btn-primary active">Edit
                                    Page</a>
                            </td>
                        </tr>
                        <tr>
                            <td>9</td>
                            <td width="65%">Login</td>
                            <td>
                                <a href="{{ url('admin/pages/login') }}" class="btn btn-primary active">Edit
                                    Page</a>
                            </td>
                        </tr>
                        <tr>
                            <td>10</td>
                            <td width="65%">Forget Password</td>
                            <td>
                                <a href="{{ url('admin/pages/forgot') }}" class="btn btn-primary active">Edit
                                    Page</a>
                            </td>
                        </tr>
                        <tr>
                            <td>11</td>
                            <td width="65%">Reset Password</td>
                            <td>
                                <a href="{{ url('admin/pages/reset') }}" class="btn btn-primary active">Edit
                                    Page</a>
                            </td>
                        </tr>
                        {{-- <tr>
                            <td>12</td>
                            <td width="65%">Thank You</td>
                            <td>
                                <a href="{{ url('admin/pages/thankyou') }}" class="btn btn-primary active">Edit
                                    Page</a>
                            </td>
                        </tr> --}}
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
    @endsection
