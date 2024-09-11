@extends('layouts.adminlayout')
@section('page_meta')
    <meta name="description" content={{ !empty($site_settings) ? $site_settings->site_meta_desc : '' }}">
    <meta name="keywords" content="{{ !empty($site_settings) ? $site_settings->site_meta_keyword : '' }}">
    <meta name="author" content="{{ !empty($site_settings->site_name) ? $site_settings->site_name : 'Login' }}">
    <title>Admin - {{ $site_settings->site_name }}</title>
@endsection
@section('page_content')
{!!breadcrumb('About Page')!!}
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
        <h5>Section 1 (Our Story)</h5>
    </div>

    <div class="card-body">

        <div class="row">
            <div class="col">
                <div class="card w-100 border position-relative overflow-hidden">
                    <div class="card-body p-4">
                      <div class="text-center">
                       <div class="file_choose_icon">
                          <img src="{{ get_site_image_src('images', !empty($sitecontent['image2']) ? $sitecontent['image2'] : "") }}" alt="matdash-img" class="img-fluid " >
                       </div>
                        <p class="mb-0">Allowed JPG, GIF or PNG. Max size of 800K</p>
                        <input class="form-control uploadFile" name="image2" type="file"
                            data-bs-original-title="" title="">
                      </div>
                    </div>
                  </div>
            </div>

            <div class="col-md-8">
                <div class="row">
                    <div class="col">
                        <div class="mb-3">
                            <label class="form-label" for="section1_text">Text</label>
                            <textarea id="section1_text" name="section1_text" rows="4" class="editor">{{ !empty($sitecontent['section1_text']) ? $sitecontent['section1_text'] : "" }}</textarea>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>


</div>
<div class="card">

    <div class="card-header">
        <h5>Section 2(Our Mission & Values)</h5>
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
                <div class="row">
                    <div class="col">
                        <div class="mb-3">
                            <label class="form-label" for="section2_link_text">Link Text</label>
                            <input class="form-control" id="section2_link_text" type="text"
                                name="section2_link_text" placeholder=""
                                value="{{ !empty($sitecontent['section2_link_text']) ? $sitecontent['section2_link_text'] : "" }}">
                        </div>
                    </div>
                    <div class="col">
                        <div class="mb-2">
                            <label class="form-label" for="section2_link_url">Link URL</label>
                            <select name="section2_link_url" class="form-control" required>
                                @foreach ($all_pages as $key => $page)
                                    <option value="{{ $key }}"
                                        {{ !empty($sitecontent['section2_link_url']) && $sitecontent['section2_link_url'] == $key ? 'selected' : '' }}>
                                        {{ $page }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>


</div>
<div class="row">
    <?php $mission = 0; ?>
    @for ($i = 3; $i <= 6; $i++)
        <?php $mission = $mission + 1; ?>
        <div class="col-lg-6">
            <div class="card">

                <div class="card-header">
                    <h5>Block {{ $mission }}</h5>
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
                                    for="sec2_heading{{ $i }}">Heading
                                    {{ $mission }}</label>
                                <input class="form-control"
                                    id="sec2_heading{{ $i }}" type="text"
                                    name="sec2_heading{{ $i }}" placeholder=""
                                    value="{{ !empty($sitecontent['sec2_heading' . $i]) ? $sitecontent['sec2_heading' . $i] : "" }}">
                            </div>
                        </div>

                    </div>
                    <div class="row">
                        <div class="col">
                            <div class="mb-3">
                                <label class="form-label"
                                    for="sec2_text{{ $i }}">Text
                                    {{ $mission }}</label>
                                <textarea id="sec2_text{{ $i }}" name="sec2_text{{ $i }}" rows="8"
                                    class="form-control">{{ !empty($sitecontent['sec2_text' . $i]) ? $sitecontent['sec2_text' . $i] : "" }}</textarea>
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
        <h5>Section 3(Why Choose Us)</h5>
    </div>

    <div class="card-body">

        <div class="row">
            <div class="col-md-12">
                <div class="row">
                    <div class="col">
                        <div class="mb-3">
                            <label class="form-label" for="section3_text">Text</label>
                            <textarea id="section3_text" name="section3_text" rows="4" class="editor">{{ !empty($sitecontent['section3_text']) ? $sitecontent['section3_text'] : "" }}</textarea>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>


</div>
<div class="card">

    <div class="card-header">
        <h5>Section 4(Team)</h5>
    </div>

    <div class="card-body">

        <div class="row">

            <div class="col-md-12">
                <div class="row">
                    <div class="col">
                        <div class="mb-3">
                            <label class="form-label" for="section4_text">Text</label>
                            <textarea id="section4_text" name="section4_text" rows="4" class=" editor">{{ !empty($sitecontent['section4_text']) ? $sitecontent['section4_text'] : "" }}</textarea>
                        </div>
                    </div>
                </div>
                
            </div>

        </div>
    </div>


</div>
<div class="card">
    <div class="card-header">
        <h5>Section 5(Cta)</h5>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col">
                <div class="card w-100 border position-relative overflow-hidden">
                    <div class="card-body p-4">
                      <div class="text-center">
                       <div class="file_choose_icon">
                          <img src="{{ get_site_image_src('images', !empty($sitecontent['image7']) ? $sitecontent['image7'] : '') }}" alt="matdash-img" class="img-fluid " >
                       </div>
                        <p class="mb-0">Allowed JPG, GIF or PNG. Max size of 800K</p>
                        <input class="form-control uploadFile" name="image7" type="file"
                            data-bs-original-title="" title="">
                      </div>
                    </div>
                  </div>
            </div>
            <div class="col-md-8">
                <div class="row">
                    <div class="col">
                        <div class="mb-3">
                            <label class="form-label" for="section5_text">Text</label>
                            <textarea id="section5_text" name="section5_text" rows="4" class=" editor">{{ !empty($sitecontent['section5_text']) ? $sitecontent['section5_text'] : "" }}</textarea>
                        </div>
                    </div>
                </div>          
            </div>
        </div>
        <div class="row">
            @for ($i = 1; $i < 3; $i++)
            <div class="col">
                <div class="mb-4">
                    <label class="form-label" for="section5_link_text_{{ $i }}">Link Text {{ $i }}</label>
                    <input class="form-control" id="section5_link_text_{{ $i }}" type="text"
                        name="section5_link_text_{{ $i }}" placeholder=""
                        value="{{ !empty($sitecontent['section5_link_text_' . $i]) ? $sitecontent['section5_link_text_' . $i] : '' }}">
                </div>
            </div>
            <div class="col">
                <div class="mb-5">
                    <label class="form-label" for="section5_link_url_{{ $i }}">Link URL {{ $i }}</label>
                    <select name="section5_link_url_{{ $i }}" class="form-control" required>
                        @foreach ($all_pages as $key => $page)
                            <option value="{{ $key }}"
                                {{ !empty($sitecontent['section5_link_url_' . $i]) && $sitecontent['section5_link_url_' . $i] == $key ? 'selected' : '' }}>
                                {{ $page }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            @endfor
        </div>
    </div>
</div>
<div class="col-12">
    <div class="d-flex align-items-center justify-content-end mt-4 gap-6">
      <button class="btn btn-primary" type="submit">Update Page</button>
    </div>
  </div>
@endsection
