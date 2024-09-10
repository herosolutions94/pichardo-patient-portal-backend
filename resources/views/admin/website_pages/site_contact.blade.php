@extends('layouts.adminlayout')
@section('page_meta')
    <meta name="description" content={{ !empty($site_settings) ? $site_settings->site_meta_desc : '' }}">
    <meta name="keywords" content="{{ !empty($site_settings) ? $site_settings->site_meta_keyword : '' }}">
    <meta name="author" content="{{ !empty($site_settings->site_name) ? $site_settings->site_name : 'Login' }}">
    <title>Admin - {{ $site_settings->site_name }}</title>
@endsection
@section('page_content')
{!!breadcrumb('Blog Page')!!}

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
        <h5>Main Text</h5>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-12">
                <div class="row">
                    <div class="col">
                        <div class="mb-3">
                            <label class="form-label" for="section1_text">Text</label>
                            <textarea id="section1_text" name="section1_text" rows="4" class=" editor">{{ !empty($sitecontent['section1_text']) ? $sitecontent['section1_text'] : "" }}</textarea>
                        </div>
                    </div>
                </div>          
            </div>
        </div>
    </div>
</div>
<div class="card">
    <div class="card-header">
        <h5>Section 2</h5>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-12">
                <div class="row">
                    <div class="col">
                        <div class="mb-3">
                            <label class="form-label" for="section2_text">Text</label>
                            <textarea id="section2_text" name="section2_text" rows="4" class=" editor">{{ !empty($sitecontent['section2_text']) ? $sitecontent['section2_text'] : "" }}</textarea>
                        </div>
                    </div>
                </div>          
            </div>
        </div>
    </div>
</div>
<div class="card">
    <div class="card-header">
        <h5>Section 3</h5>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-12">
                <div class="row">
                    <div class="col">
                        <div class="mb-3">
                            <label class="form-label" for="section3_text">Text</label>
                            <textarea id="section3_text" name="section3_text" rows="4" class=" editor">{{ !empty($sitecontent['section3_text']) ? $sitecontent['section3_text'] : "" }}</textarea>
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
