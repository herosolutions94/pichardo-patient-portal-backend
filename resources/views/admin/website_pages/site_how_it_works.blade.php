@extends('layouts.adminlayout')
@section('page_meta')
    <meta name="description" content={{ !empty($site_settings) ? $site_settings->site_meta_desc : '' }}">
    <meta name="keywords" content="{{ !empty($site_settings) ? $site_settings->site_meta_keyword : '' }}">
    <meta name="author" content="{{ !empty($site_settings->site_name) ? $site_settings->site_name : 'Login' }}">
    <title>Admin - {{ $site_settings->site_name }}</title>
@endsection
@section('page_content')
{!!breadcrumb('How it works Page')!!}
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
            <div class="col">
                <div class="card w-100 border position-relative overflow-hidden">
                    <div class="card-body p-4">
                      <div class="text-center">
                       <div class="file_choose_icon">
                          <img src="{{ get_site_image_src('images', !empty($sitecontent['image1']) ? $sitecontent['image1'] : "") }}" alt="matdash-img" class="img-fluid " >
                       </div>
                        <p class="mb-0">Allowed JPG, GIF or PNG. Max size of 800K</p>
                        <input class="form-control uploadFile" name="image1" type="file"
                            data-bs-original-title="" title="">
                      </div>
                    </div>
                  </div>
            </div>

            <div class="col-md-8">
                <div class="row">
                    <div class="col">
                        <div class="mb-3">
                            <label class="form-label" for="banner_text">Text</label>
                            <textarea id="banner_text" name="banner_text" rows="4" class="editor">{{ !empty($sitecontent['banner_text']) ? $sitecontent['banner_text'] : "" }}</textarea>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>


</div>
<div class="card">

    <div class="card-header">
        <h5>Section 1</h5>
    </div>

    <div class="card-body">

        <div class="row">
            <div class="col-md-12">
                <div class="row">
                    <div class="col">
                        <div class="mb-3">
                            <label class="form-label" for="section1_heading1">Main Heading</label>
                            <textarea id="section1_heading1" name="section1_heading1" rows="4" class="editor">{{ !empty($sitecontent['section1_heading1']) ? $sitecontent['section1_heading1'] : "" }}</textarea>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        <div class="mb-3">
                            <label class="form-label" for="section1_heading2">Sub Heading</label>
                            <input class="form-control" id="section1_heading2" type="text"
                                name="section1_heading2" placeholder=""
                                value="{{ !empty($sitecontent['section1_heading2']) ? $sitecontent['section1_heading2'] : "" }}">
                        </div>
                    </div>
                    
                </div>
            </div>

        </div>
    </div>


</div>
<div class="row">
    <?php $how_block_count = 0; ?>
    @for ($i = 2; $i <= 4; $i++)
        <?php $how_block_count = $how_block_count + 1; ?>
        <div class="col">
            <div class="card">

                <div class="card-header">
                    <h5>Block {{ $how_block_count }}</h5>
                </div>
            
                <div class="card-body">
                    <div class="row">
                        <div class="col">
                            <div class="card w-100 border position-relative overflow-hidden">
                                <div class="card-body p-4">
                                  <div class="text-center">
                                   <div class="file_choose_icon">
                                      <img src="{{ get_site_image_src('images', !empty($sitecontent['image' . $i]) ? $sitecontent['image' . $i] : '') }}" alt="matdash-img" class="img-fluid " >
                                   </div>
                                    <p class="mb-0">Allowed JPG, GIF or PNG. Max size of 800K</p>
                                    <input class="form-control uploadFile" name="image{{ $i }}" type="file"
                                        data-bs-original-title="" title="">
                                  </div>
                                </div>
                              </div>
                        </div>
                       
                    </div>
                    <div class="row">
                        <div class="col">
                            <div class="mb-3">
                                <label class="form-label"
                                    for="sec3_heading{{ $i }}">Heading
                                    {{ $how_block_count }}</label>
                                <input class="form-control"
                                    id="sec3_heading{{ $i }}" type="text"
                                    name="sec3_heading{{ $i }}" placeholder=""
                                    value="{{ !empty($sitecontent['sec3_heading' . $i]) ? $sitecontent['sec3_heading' . $i] : "" }}">
                            </div>
                        </div>

                    </div>
                    <div class="row">
                        <div class="col">
                            <div class="mb-3">
                                <label class="form-label"
                                    for="sec3_text{{ $i }}">Text
                                    {{ $how_block_count }}</label>
                                <textarea id="sec3_text{{ $i }}" name="sec3_text{{ $i }}" rows="8"
                                    class="form-control">{{ !empty($sitecontent['sec3_text' . $i]) ? $sitecontent['sec3_text' . $i] : "" }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endfor
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
                            <label class="form-label" for="section2_text">Main Heading</label>
                            <textarea id="section2_text" name="section2_text" rows="4" class="editor">{{ !empty($sitecontent['section2_text']) ? $sitecontent['section2_text'] : "" }}</textarea>
                        </div>
                    </div>
                    
                </div>
            </div>

        </div>
    </div>


</div>
<?php $sec_count=2; ?>
@for ($i = 5; $i <= 7; $i++)
<div class="card">

    <div class="card-header">
        <h5>Section {{++$sec_count}}</h5>
    </div>

    <div class="card-body">

        <div class="row">
            <div class="col">
                <div class="card w-100 border position-relative overflow-hidden">
                    <div class="card-body p-4">
                      <div class="text-center">
                       <div class="file_choose_icon">
                          <img src="{{ get_site_image_src('images', !empty($sitecontent['image' . $i]) ? $sitecontent['image' . $i] : '') }}" alt="matdash-img" class="img-fluid " >
                       </div>
                        <p class="mb-0">Allowed JPG, GIF or PNG. Max size of 800K</p>
                        <input class="form-control uploadFile" name="image{{ $i }}" type="file"
                            data-bs-original-title="" title="">
                      </div>
                    </div>
                  </div>
            </div>

            <div class="col-md-8">
                <div class="row">
                    <div class="col">
                        <div class="mb-3">
                            <label class="form-label" for="section_text{{$i}}">Text</label>
                            <textarea id="section_text{{ $i }}" name="section_text{{$i}}" rows="4" class="editor">{{ !empty($sitecontent['section_text'.$i]) ? $sitecontent['section_text'.$i] : "" }}</textarea>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>


</div>
@endfor
<div class="card">

    <div class="card-header">
        <h5>Section 6</h5>
    </div>

    <div class="card-body">

        <div class="row">
            <div class="col-md-12">
                <div class="row">
                    <div class="col">
                        <div class="mb-3">
                            <label class="form-label" for="section5_text">Main Heading</label>
                            <textarea id="section5_text" name="section5_text" rows="4" class="editor">{{ !empty($sitecontent['section5_text']) ? $sitecontent['section5_text'] : "" }}</textarea>
                        </div>
                    </div>
                    
                </div>
            </div>

        </div>
    </div>


</div>
<div class="row">
    <?php $why_choose_us = 0; ?>
    @for ($i = 8; $i <= 11; $i++)
        <?php $why_choose_us = $why_choose_us + 1; ?>
        <div class="col-lg-6">
            <div class="card">

                <div class="card-header">
                    <h5>Block {{ $why_choose_us }}</h5>
                </div>
            
                <div class="card-body">
                    <div class="row">
                        <div class="col">
                            <div class="card w-100 border position-relative overflow-hidden">
                                <div class="card-body p-4">
                                  <div class="text-center">
                                   <div class="file_choose_icon">
                                      <img src="{{ get_site_image_src('images', !empty($sitecontent['image' . $i]) ? $sitecontent['image' . $i] : '') }}" alt="matdash-img" class="img-fluid " >
                                   </div>
                                    <p class="mb-0">Allowed JPG, GIF or PNG. Max size of 800K</p>
                                    <input class="form-control uploadFile" name="image{{ $i }}" type="file"
                                        data-bs-original-title="" title="">
                                  </div>
                                </div>
                              </div>
                        </div>
                       
                    </div>
                    <div class="row">
                        <div class="col">
                            <div class="mb-3">
                                <label class="form-label"
                                    for="sec3_heading{{ $i }}">Heading
                                    {{ $why_choose_us }}</label>
                                <input class="form-control"
                                    id="sec3_heading{{ $i }}" type="text"
                                    name="sec3_heading{{ $i }}" placeholder=""
                                    value="{{ !empty($sitecontent['sec3_heading' . $i]) ? $sitecontent['sec3_heading' . $i] : "" }}">
                            </div>
                        </div>

                    </div>
                    <div class="row">
                        <div class="col">
                            <div class="mb-3">
                                <label class="form-label"
                                    for="sec3_text{{ $i }}">Text
                                    {{ $why_choose_us }}</label>
                                <textarea id="sec3_text{{ $i }}" name="sec3_text{{ $i }}" rows="4"
                                    class="form-control">{{ !empty($sitecontent['sec3_text' . $i]) ? $sitecontent['sec3_text' . $i] : "" }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endfor
</div>
<div class="card">
    <div class="card-header">
        <h5>Section 7</h5>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col">
                <div class="card w-100 border position-relative overflow-hidden">
                    <div class="card-body p-4">
                      <div class="text-center">
                       <div class="file_choose_icon">
                          <img src="{{ get_site_image_src('images', !empty($sitecontent['image12']) ? $sitecontent['image12'] : '') }}" alt="matdash-img" class="img-fluid " >
                       </div>
                        <p class="mb-0">Allowed JPG, GIF or PNG. Max size of 800K</p>
                        <input class="form-control uploadFile" name="image12" type="file"
                            data-bs-original-title="" title="">
                      </div>
                    </div>
                  </div>
            </div>
            <div class="col-md-8">
                <div class="row">
                    <div class="col">
                        <div class="mb-3">
                            <label class="form-label" for="section6_text">Text</label>
                            <textarea id="section6_text" name="section6_text" rows="4" class=" editor">{{ !empty($sitecontent['section6_text']) ? $sitecontent['section6_text'] : "" }}</textarea>
                        </div>
                    </div>
                </div>          
            </div>
        </div>
    </div>
</div>
<div class="row">
    <?php $how_block_count = 0; ?>
    @for ($i = 13; $i <= 14; $i++)
        <?php $download_sec = $how_block_count + 1; ?>
        <div class="col">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col">
                            <div class="card w-100 border position-relative overflow-hidden">
                                <div class="card-body p-4">
                                  <div class="text-center">
                                   <div class="file_choose_icon">
                                      <img src="{{ get_site_image_src('images', !empty($sitecontent['image' . $i]) ? $sitecontent['image' . $i] : '') }}" alt="matdash-img" class="img-fluid " >
                                   </div>
                                    <p class="mb-0">Allowed JPG, GIF or PNG. Max size of 800K</p>
                                    <input class="form-control uploadFile" name="image{{ $i }}" type="file"
                                        data-bs-original-title="" title="">
                                  </div>
                                </div>
                              </div>
                        </div>
                       
                    </div>
                    <div class="row">
                        <div class="col">
                            <div class="mb-3">
                                <label class="form-label"
                                    for="sec3_heading{{ $i }}">Heading</label>
                                <input class="form-control"
                                    id="sec3_heading{{ $i }}" type="text"
                                    name="sec3_heading{{ $i }}" placeholder=""
                                    value="{{ !empty($sitecontent['sec3_heading' . $i]) ? $sitecontent['sec3_heading' . $i] : "" }}">
                            </div>
                        </div>
                        <div class="col">
                            <div class="mb-3">
                                <label class="form-label"
                                    for="sec3_text{{ $i }}">Heading</label>
                                <input class="form-control"
                                    id="sec3_text{{ $i }}" type="text"
                                    name="sec3_text{{ $i }}" placeholder=""
                                    value="{{ !empty($sitecontent['sec3_text' . $i]) ? $sitecontent['sec3_text' . $i] : "" }}">
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    @endfor
</div>
<div class="col-12">
    <div class="d-flex align-items-center justify-content-end mt-4 gap-6">
      <button class="btn btn-primary" type="submit">Update Page</button>
    </div>
  </div>
@endsection
