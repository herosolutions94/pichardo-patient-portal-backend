<meta name="description" content={{ !empty($site_settings) ? $site_settings->site_meta_desc : '' }}">
<meta name="keywords" content="{{ !empty($site_settings) ? $site_settings->site_meta_keyword : '' }}">
<meta name="author" content="pixelstrap">
<link rel="icon"
    href="{{ !empty($site_settings) ? get_site_image_src('images', $site_settings->site_icon) : get_site_image_src('images', '') }}"
    type="image/x-icon">
<link rel="shortcut icon"
    href="{{ !empty($site_settings) ? get_site_image_src('images', $site_settings->site_icon) : get_site_image_src('images', '') }}"
    type="image/x-icon">
<!-- Google font-->
<link href="https://fonts.googleapis.com/css?family=Rubik:400,400i,500,500i,700,700i&amp;display=swap" rel="stylesheet">
<link href="https://fonts.googleapis.com/css?family=Roboto:300,300i,400,400i,500,500i,700,700i,900&amp;display=swap"
    rel="stylesheet">
<link rel="stylesheet" href="{{ asset('admin/datatables/datatables.min.css') }}" />
<link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/rateYo/2.3.2/jquery.rateyo.min.css">

<link rel="stylesheet" href="{{ asset('admin/css/owl.carousel.min.css') }}" />
<link rel="stylesheet" href="{{ asset('admin/css/styles.min.css') }}" />
<meta name="csrf-token" content="{{ csrf_token() }}" />

