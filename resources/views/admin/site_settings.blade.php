@extends('layouts.adminlayout')
@section('page_meta')
    <meta name="description" content={{ !empty($site_settings) ? $site_settings->site_meta_desc : '' }}">
    <meta name="keywords" content="{{ !empty($site_settings) ? $site_settings->site_meta_keyword : '' }}">
    <meta name="author" content="{{ !empty($site_settings->site_name) ? $site_settings->site_name : 'Login' }}">
    <title>Admin - {{ $site_settings->site_name }}</title>
@endsection
@section('page_content')
{!!breadcrumb('Site Settings')!!}
  <form class="form theme-form" method="post" action="{{ url('admin/settings') }}"
                        enctype="multipart/form-data" id="saveForm">
                        @csrf
  <div class="card">
    <div class="card-body">
        <div class="row">
            <div class="col-lg-4 d-flex align-items-stretch">
                <div class="card w-100 border position-relative overflow-hidden">
                  <div class="card-body p-4">
                    <h4 class="card-title">Change Logo</h4>
                    <p class="card-subtitle mb-4">Change your Site Logo</p>
                    <div class="text-center">
                     <div class="file_choose_icon">
                        <img src="{{ get_site_image_src('images', $site_settings->site_logo) }}" alt="matdash-img" class="img-fluid " >
                     </div>
                      <p class="mb-0">Allowed JPG, GIF or PNG. Max size of 800K</p>
                      <input class="form-control uploadFile" name="site_logo" type="file"
                                                    data-bs-original-title="" title="">
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-lg-4 d-flex align-items-stretch">
                <div class="card w-100 border position-relative overflow-hidden">
                  <div class="card-body p-4">
                    <h4 class="card-title">Change Fav Icon</h4>
                    <p class="card-subtitle mb-4">Change your Site FavIcon</p>
                    <div class="text-center">
                        <div class="file_choose_icon">
                            <img src="{{ get_site_image_src('images', $site_settings->site_icon) }}" alt="matdash-img" class="img-fluid " >
                        </div>
                      <p class="mb-0">Allowed JPG, GIF or PNG. Max size of 800K</p>
                      <input class="form-control uploadFile" name="site_icon" type="file"
                                                    data-bs-original-title="" title="">
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-lg-4 d-flex align-items-stretch">
                <div class="card w-100 border position-relative overflow-hidden">
                  <div class="card-body p-4">
                    <h4 class="card-title">Change Thumb</h4>
                    <p class="card-subtitle mb-4">Change your Site Thumb</p>
                    <div class="text-center">
                        <div class="file_choose_icon">
                            <img src="{{ get_site_image_src('images', $site_settings->site_thumb) }}" alt="matdash-img" class="img-fluid " >
                        </div>
                      <p class="mb-0">Allowed JPG, GIF or PNG. Max size of 800K</p>
                      <input class="form-control uploadFile" name="site_thumb" type="file"
                                                    data-bs-original-title="" title="">
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-12">
                <div class="card w-100 border position-relative overflow-hidden">
                  <div class="card-body p-4">
                    <h4 class="card-title">Personal Details</h4>
                    <p class="card-subtitle mb-4">To change your Website detail , edit and save from here</p>
                      <div class="row">
                        <div class="col-lg-6">
                          <div class="mb-3">
                            <label for="site_domain" class="form-label">Site Domain</label>
                            <input class="form-control" id="site_domain" type="text" name="site_domain"
                                                placeholder="www.example.come" value="{{ $site_settings->site_domain }}">
                          </div>
                        </div>
                        <div class="col-lg-6">
                          <div class="mb-3">
                            <label for="site_name" class="form-label">Site Name</label>
                            <input class="form-control" id="site_name" type="text" name="site_name"
                                                placeholder="" value="{{ $site_settings->site_name }}">
                          </div>
                        </div>
                        <div class="col-lg-4">
                          <div class="mb-3">
                            <label for="site_phone" class="form-label">Site Phone</label>
                            <input class="form-control" id="site_phone" type="text" name="site_phone"
                                                placeholder="" value="{{ $site_settings->site_phone }}">
                          </div>
                        </div>
                        <div class="col-lg-4">
                          <div class="mb-3">
                            <label for="site_email" class="form-label">Site Email</label>
                            <input class="form-control" id="site_email" type="text" name="site_email"
                                                placeholder="" value="{{ $site_settings->site_email }}">
                          </div>
                        </div>
                        <div class="col-lg-4">
                          <div class="mb-3">
                            <label for="site_noreply_email" class="form-label">Site No-Reply Email</label>
                            <input class="form-control" id="site_noreply_email" type="text" name="site_noreply_email"
                                                placeholder="" value="{{ $site_settings->site_noreply_email }}">
                          </div>
                        </div>
                        <div class="col-lg-12">
                          <div class="mb-3">
                            <label for="site_address" class="form-label">Site Address</label>
                            <textarea class="form-control" id="site_address" rows="3" name="site_address">{{ $site_settings->site_address }}</textarea>
                          </div>
                        </div>
                        <div class="col-lg-12">
                          <div class="mb-3">
                            <label for="site_about" class="form-label">Site About</label>
                            <textarea class="form-control" id="site_about" rows="3" name="site_about">{{ $site_settings->site_about }}</textarea>
                          </div>
                        </div>
                        <div class="col-lg-12">
                          <div class="mb-3">
                            <label for="site_copyright" class="form-label">Site Copyright</label>
                            <textarea class="form-control" id="site_copyright" rows="3" name="site_copyright">{{ $site_settings->site_copyright }}</textarea>
                          </div>
                        </div>
                      </div>
                    
                  </div>
                </div>
              </div>
              
              <div class="col-12">
                <div class="card w-100 border position-relative overflow-hidden">
                  <div class="card-body p-4">
                    <h4 class="card-title">Meta Details</h4>
                    <p class="card-subtitle mb-4">To change your meta detail , edit and save from here</p>
                      <div class="row">
                        <div class="col-lg-12">
                          <div class="mb-3">
                            <label for="site_domain" class="form-label">Site Meta Description</label>
                            <textarea class="form-control" id="site_meta_desc" rows="3" name="site_meta_desc">{{ $site_settings->site_meta_desc }}</textarea>
                          </div>
                        </div>
                        <div class="col-lg-12">
                          <div class="mb-3">
                            <label for="site_copyright" class="form-label">Site Meta Keywords</label>
                            <textarea class="form-control" id="site_meta_keyword" rows="3" name="site_meta_keyword">{{ $site_settings->site_meta_keyword }}</textarea>
                          </div>
                        </div>
                      </div>
                    
                  </div>
                </div>
              </div>
              <div class="col-12">
                <div class="card w-100 border position-relative overflow-hidden">
                  <div class="card-body p-4">
                    <h4 class="card-title">Site Payment Testing/Live</h4>
                    <p class="card-subtitle mb-4">To change your meta detail , edit and save from here</p>
                      <div class="row">
                        <div class="col-lg-12">
                          <div class="mb-3">
                            <div class="form-check form-switch py-2">
                                <input class="form-check-input success" type="checkbox" id="color-success"  {{ !empty($site_settings) ? ($site_settings->site_sandbox == 0 ? 'checked' : '') : '' }} name="site_sandbox" />
                                <label class="form-check-label" for="color-success"> {{ !empty($site_settings) ? ($site_settings->site_sandbox == 1 ? 'Sandbox' : 'Live') : '' }} Mode</label>
                              </div>
                          </div>
                        </div>
                        
                      </div>
                    
                  </div>
                </div>
              </div>
              <div class="col-12">
                <div class="card w-100 border position-relative overflow-hidden">
                  <div class="card-body p-4">
                    <h4 class="card-title">Site Processing Fee(%)</h4>
                    <p class="card-subtitle mb-4">Site processing fee or services charges</p>
                      <div class="row">
                        <div class="col-lg-12">
                          <div class="mb-3">
                            <div class="">
                               <input type="number" name="site_processing_fee" class="form-control" value="{{$site_settings->site_processing_fee}}" />
                              </div>
                          </div>
                        </div>
                        
                      </div>
                    
                  </div>
                </div>
              </div>
              <div class="col-12">
                <div class="card w-100 border position-relative overflow-hidden">
                  <div class="card-body p-4">
                    <h4 class="card-title">Site Profit Percentage(%)</h4>
                    <p class="card-subtitle mb-4">Site profit percentage that will be deducted from each booking</p>
                      <div class="row">
                        <div class="col-lg-12">
                          <div class="mb-3">
                            <div class="">
                               <input type="number" name="site_percentage" class="form-control" value="{{$site_settings->site_percentage}}" />
                              </div>
                          </div>
                        </div>
                        
                      </div>
                    
                  </div>
                </div>
              </div>
              <div class="col-12">
                <div class="card w-100 border position-relative overflow-hidden">
                  <div class="card-body p-4">
                    <h4 class="card-title">Site Social Links</h4>
                    <p class="card-subtitle mb-4">To change your meta detail , edit and save from here</p>
                      <div class="row">
                        <div class="col-lg-6">
                          <div class="mb-3">
                            <div class="">
                                <label class="form-check-label" for="color-success"> Instagram</label>
                                <input class="form-control" id="site_instagram" type="text"
                                                name="site_instagram" placeholder="www.instagram.com/account_name"
                                                value="{{ $site_settings->site_instagram }}">
                              </div>
                          </div>
                        </div>
                        <div class="col-lg-6">
                          <div class="mb-3">
                            <div class="">
                                <label class="form-check-label" for="color-success"> Facebook</label>
                                <input class="form-control" id="site_facebook" type="text"
                                                name="site_facebook" placeholder="www.instagram.com/account_name"
                                                value="{{ $site_settings->site_facebook }}">
                              </div>
                          </div>
                        </div>
                        <div class="col-lg-6">
                          <div class="mb-3">
                            <div class="">
                                <label class="form-check-label" for="color-success"> Twitter</label>
                                <input class="form-control" id="site_twitter" type="text"
                                                name="site_twitter" placeholder="www.instagram.com/account_name"
                                                value="{{ $site_settings->site_twitter }}">
                              </div>
                          </div>
                        </div>
                        <div class="col-lg-6">
                          <div class="mb-3">
                            <div class="">
                                <label class="form-check-label" for="color-success"> Linkedin</label>
                                <input class="form-control" id="site_discord" type="text"
                                                name="site_discord" placeholder="www.instagram.com/account_name"
                                                value="{{ $site_settings->site_discord }}">
                              </div>
                          </div>
                        </div>
                        <div class="col-12">
                          <div class="d-flex align-items-center justify-content-end mt-4 gap-6">
                            <button class="btn btn-primary" type="submit">Update Site Settings</button>
                          </div>
                        </div>
                      </div>
                    
                  </div>
                </div>
              </div>
              
        </div>
    </div>
  </div>
  </form>
@endsection
