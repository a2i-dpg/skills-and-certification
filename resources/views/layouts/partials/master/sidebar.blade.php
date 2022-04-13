@php
    $currentInstitute = app('currentInstitute');
@endphp
<!-- Main Sidebar Container -->
{{--elevation-4--}}
<aside class="main-sidebar sidebar-blue-dark nav-compact nav-flat nav-child-indent text-sm">
    <a href="{{url('/admin')}}" class="brand-link bg-white logo-switch pb-4">
        <span class="logo-xl" style="font-size: 10px;margin: 10px 0px;font-weight: bolder;color: blue;">
            {{($siteSettingInfo->site_title) ?? env('APP_NAME')}}
        </span>
        <span class="logo-xs" style="font-size: 10px;margin: 10px 0px;font-weight: bolder;color: blue;">
            {{($siteSettingInfo->site_title) ?? env('APP_NAME')}}
        </span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar Menu -->
        <nav class="mt-2">
            {!! menu('admin_menu', 'admin-lte', ['icon' => true]) !!}
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>
