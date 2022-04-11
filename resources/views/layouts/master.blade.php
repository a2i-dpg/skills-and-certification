<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="csrf-token" content="{{csrf_token()}}"/>
    <title>{{config('app.name')}} - @yield('title')</title>
    <link rel="stylesheet" href="{{asset('/css/app.css')}}">
    <link rel="stylesheet" href="{{asset('/css/frontendStyle.css')}}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css">
    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/overlayscrollbars/1.13.0/css/OverlayScrollbars.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Bengali:wght@300;400;500;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Noto Sans Bengali', sans-serif;
        }
        .sidebar-blue-dark {
            background: #2F49D1;
        }

        .sidebar-blue-dark .nav-sidebar .nav-item > .nav-link {
            color: #F2F4FC;
        }

        .sidebar-blue-dark .nav-pills .nav-link.active, .nav-pills .show > .nav-link {
            background-color: #0d2bc6;
        }

        /** need to move **/
          .nav-big-icon-sidebar .nav-item.parent > a .nav-icon{
              display: block;
              font-size: 35px !important;
              text-align: center;
              margin: auto !important;
              width: 50px !important;
              margin-bottom: 20px !important;
          }

        .nav-big-icon-sidebar .nav-item.parent > a{
            text-align: center;
            border-bottom: 1px solid #33a6f7;
            padding-bottom: 18px;
        }
        .nav-big-icon-sidebar .nav-treeview{
            background: #328cd8;
            padding: 14px 0 10px 0;
        }

        .nav-big-icon-sidebar .nav-treeview:before{
            content: '';
            margin-left: 25px;
            border-left: 13px solid transparent;
            border-right: 12px solid transparent;
            border-top: 15px solid #2f49d1;

        }

        .nav-big-icon-sidebar .nav-item.parent.menu-open > a{
            border-bottom: none !important;
        }
        .nav-big-icon-sidebar .nav-treeview .nav-link.active{
            background-color: #4288c3;
        }

        .nav-big-icon-sidebar .nav-item.parent.menu-open > a.active + .nav-treeview:before{
            border-top: 15px solid #0d2bc6;
        }

        .main-sidebar.sidebar-focused .nav-big-icon-sidebar .nav-link p,
        .main-sidebar:hover .nav-big-icon-sidebar .nav-link p{
            width: 100%;
        }

        .theme-inner-box{
            border-radius: 6px;
            box-shadow: 0px 0px 12px 6px #f4f4f4;
            padding: 15px 10px;
            background: #fff;
            margin-bottom: 25px !important;
            height: 100%;
        }
        .theme-box {
            -webkit-transition: transform 0.8s;
            -moz-transition: transform 0.8s;
            -o-transition: transform 0.8s;
            transition: transform 0.8s;
            width: 300px;
            min-height: 320px;
            position: relative;
        }
        .theme-box.active{
            border: 8px solid #0d2bc6 !important;
        }

        .theme-box:hover .theme-inner-box{
            box-shadow: 0 0 45px -10px #a6d1ff !important;
        }
        .theme-image{
            width: 80px;
            margin-left: 15px;
        }
        .theme-button-wrapper{
            text-align: center;
            position: absolute;
            bottom: 20px;
            width: 90%;
            display: none;
        }
        .theme-box:hover .theme-button-wrapper{
            display: block;
        }
    </style>
    @stack('css')
    <script>
        window.select_option_placeholder = '{{__('generic.add_button_label')}}';
    </script>
</head>
<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">
    @yield('header', \Illuminate\Support\Facades\View::make('master::layouts.partials.master.header'))

    @yield('sidebar', Illuminate\Support\Facades\View::make('master::layouts.partials.master.sidebar'))

    <div class="content-wrapper px-1 py-2">
        @yield('content')
    </div>

    @yield('footer', Illuminate\Support\Facades\View::make('master::layouts.partials.master.footer'))
</div>
<script src="{{asset('/js/app.js')}}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/overlayscrollbars/1.13.0/js/OverlayScrollbars.min.js"></script>
<script src="{{asset('/js/admin-lte.js')}}"></script>
<script src="{{asset('/js/on-demand.js')}}"></script>
<script>
    @if(\Illuminate\Support\Facades\Session::has('alerts'))
    let alerts = {!! json_encode(\Illuminate\Support\Facades\Session::get('alerts')) !!};
    helpers.displayAlerts(alerts, toastr);
    @endif
    @if(\Illuminate\Support\Facades\Session::has('message'))
    let alertType = {!! json_encode(\Illuminate\Support\Facades\Session::get('alert-type', 'info')) !!};
    let alertMessage = {!! json_encode(\Illuminate\Support\Facades\Session::get('message')) !!};
    let alerter = toastr[alertType];
    alerter ? alerter(alertMessage) : toastr.error("toastr alert-type " + alertType + " is unknown");
    @endif
</script>
@stack('js')
</body>
</html>

