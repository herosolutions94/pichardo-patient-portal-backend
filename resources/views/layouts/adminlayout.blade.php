<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    @include('admin/includes/master')

    @yield('page_meta')

</head>

<body>
    <div class="page-wrapper" id="main-wrapper" data-layout="vertical" data-navbarbg="skin6"  data-sidebartype="full"
    data-sidebar-position="fixed" data-header-position="fixed">
        @include('admin/includes/sidebar')
       
        <div class="body-wrapper">
            @include('admin/includes/header')
            <div class="body-wrapper-inner">
                <div class="container-fluid">
                    {!!showMessage()!!}
                    @yield('page_content')
                </div>
            </div>
        </div>
    </div>

    @include('admin/includes/footer')

</body>

</html>
