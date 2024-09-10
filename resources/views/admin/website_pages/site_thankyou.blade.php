@extends('layouts.adminlayout')
@section('page_meta')
    <meta name="description" content={{ !empty($site_settings) ? $site_settings->site_meta_desc : '' }}">
    <meta name="keywords" content="{{ !empty($site_settings) ? $site_settings->site_meta_keyword : '' }}">
    <meta name="author" content="{{ !empty($site_settings->site_name) ? $site_settings->site_name : 'Login' }}">
    <title>Admin - {{ $site_settings->site_name }}</title>
@endsection
@section('page_content')
{!!breadcrumb('Thank You Page')!!}

<form class="form theme-form" method="post" action="" enctype="multipart/form-data"
id="saveForm">
@csrf
<div class="card">
    <div class="card-body">
        <div class="row">
           
            <div class="row">
                <div class="col">
                    <div>
                        <label class="form-label" for="page_title">Page Title</label>
                        <input class="form-control" id="page_title" type="text" name="page_title"
                                        placeholder="" value="{{ !empty($sitecontent['page_title']) ? $sitecontent['page_title'] : "" }}">
                    </div>
                </div>
            </div>
             <div class="row">
                 <div class="col">
                     <div>
                         <label class="form-label" for="meta_title">Meta Title</label>
                         <input class="form-control" id="meta_title" type="text" name="meta_title"
                                        placeholder="" value="{{ !empty($sitecontent['meta_title']) ? $sitecontent['meta_title'] : "" }}">
                     </div>
                 </div>
             </div>
            <div class="row">
                <div class="col">
                    <div>
                        <label class="form-label" for="site_meta_desc">Meta Description</label>
                        <textarea class="form-control" id="meta_description" rows="3" name="meta_description">{{ !empty($sitecontent['meta_description']) ? $sitecontent['meta_description'] : "" }}</textarea>
                    </div>
                </div>
            </div>
            <div class="row">
                 <div class="col">
                    <div>
                        <label class="form-label" for="meta_keywords">Meta Keywords</label>
                        <textarea class="form-control" id="meta_keywords" rows="3" name="meta_keywords">{{ !empty($sitecontent['meta_keywords']) ? $sitecontent['meta_keywords'] : "" }}</textarea>
                    </div>
                </div>
            </div>
            
        </div>
    </div>
</div>
<div class="card">

    <div class="card-header">
        <h5>Banner</h5>
    </div>

    <div class="card-body">

        <div class="row">
            <div class="col-md-12">
                <div class="row">
                    <div class="col">
                        <div class="mb-3">
                            <label class="form-label" for="banner_text">Main Text</label>
                            <textarea id="banner_text" name="banner_text" rows="4" class="editor">{{ !empty($sitecontent['banner_text']) ? $sitecontent['banner_text'] : "" }}</textarea>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        <div class="mb-3">
                            <label class="form-label" for="section1_link_text">Explore Link Text</label>
                            <input class="form-control" id="section1_link_text" type="text"
                                name="section1_link_text" placeholder=""
                                value="{{ !empty($sitecontent['section1_link_text']) ? $sitecontent['section1_link_text'] : "" }}">
                        </div>
                    </div>
                    <div class="col">
                        <div class="mb-3">
                            <label class="form-label" for="section1_link1_text">Chat Link Text</label>
                            <input class="form-control" id="section1_link1_text" type="text"
                                name="section1_link1_text" placeholder=""
                                value="{{ !empty($sitecontent['section1_link1_text']) ? $sitecontent['section1_link1_text'] : "" }}">
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
<div class="card">

    <div class="card-header">
        <h5>Discover Section</h5>
    </div>

    <div class="card-body">

        <div class="row">
            <div class="col-md-12">
                <div class="row">
                    <div class="col">
                        <div class="mb-3">
                            <label class="form-label" for="discover_text">Discover Text</label>
                            <textarea id="discover_text" name="discover_text" rows="4" class="editor">{{ !empty($sitecontent['discover_text']) ? $sitecontent['discover_text'] : "" }}</textarea>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>



<div class="col-12">
    <div class="d-flex align-items-center justify-content-end mt-4 gap-6">
      <button class="btn btn-primary" type="submit">Update Page</button>
    </div>
  </div>
@endsection
