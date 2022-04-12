@php
    $currentInstitute = app('currentInstitute');
    $layout = 'master::layouts.front-end';
@endphp
@extends($layout)

@section('title')
{{$siteSettingInfo->site_title}} :: {{ $staticContent->title }}
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card pt-5 pb-5">
                    <div class="container">
                        <div class="row">
                            <div class="col-md-12">
                                {!! $staticContent->page_contents !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
