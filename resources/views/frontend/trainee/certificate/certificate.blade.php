@php
    $currentInstitute = app('currentInstitute');
    $layout = 'master::layouts.front-end';
@endphp

@extends($layout)


@section('content')
    <div style="background: #8a5d3b">
        <div>
            <div style=" margin: 0 auto;border-top-left-radius: 150px;border-bottom-right-radius: 150px;background: white;">
                <div style="width: 1200px; margin: 0 auto;">
                    <div style="">
                        <img src="{{asset('assets/logo/certificate_logo.png')}}" style="float:left;     margin-right: 15px;margin-top: 20px;margin-left: 80px;" width="70px" height="70px" alt="">
                        <div style="float:left;">
                            <h2 style="margin-bottom: 5px;margin-top: 2rem;">Bangladesh Industrial Technical Assistance Center (BITAC)</h2>
                            <h3 style="text-align: center; font-weight: 400;margin-top: 0;
        margin-bottom: 5px;">Ministry of Industries</h3>
                            <h3 style="text-align: center; font-weight: 400; margin-top: 5px;">Government of the People's Republic
                                of Bangladesh </h3>
                        </div>
                        <img src="{{asset('assets/logo/certificate_logo.png')}}" style="margin-left: 15px; margin-top: 20px;" width="70px" height="70px" alt="">
                    </div>
                    <div style="clear:both;margin-top: 8rem;">
                        <div style="width: 33%;float: left;">
                            <p style="font-size: 25px;float: left;margin: 0;margin-right: 5px;">No</p>
                            <span style="border: 4px;border-style: none none dotted none;padding-left: 20px;padding-right: 20px;font-size: 20px;">123456789</span>
                        </div>
                        <div style="width: 33%;float: left;text-align: center;">
                            <h2 style=" color: #8a5d3b;margin: 0;font-size: 32px;">CERTIFICATE</h2>
                        </div>
                        <div style="width: 33%;float: left;">
                            <div style="float:right;">
                                <p style="font-size: 25px;float: left;margin: 0;margin-right: 5px;">Date</p>
                                <span style="border: 4px;border-style: none none dotted none;padding-left: 20px;padding-right: 20px;font-size: 20px;">10/10/21</span>
                            </div>
                        </div>
                    </div>
                    <div style="margin-top: 10rem;background: url('{{ asset('assets/logo/certificate_watermark.png') }}');background-repeat: no-repeat;background-position: center;padding-top: 10rem;padding-bottom: 5rem;">
                        <div style="">
                            <p style="float:left; margin: 0;font-size: 28px;font-style: italic;font-weight: bold;">Certified
                                that</p>
                            <div style="width: 63.5rem;float: right;border-bottom: 4px dotted;text-align: center;font-size: 22px;font-style: italic;">
                                <span>John Doe</span></div>
                        </div>
                        <div style="clear: both;padding-top: 25px;">
                            <p style="float:left; margin: 0;font-size: 28px;font-style: italic;font-weight: bold;">son/daughter
                                of </p>
                            <div style="width: 51rem; border-bottom: 4px dotted;text-align: center;font-size: 22px;float: left;font-style: italic;">
                                <span>Mark Doe</span></div>
                            <p style="font-size: 28px;margin: 0;font-style: italic;font-weight: bold;">has attended</p>
                        </div>
                        <div style="clear: both;padding-top: 25px;font-style: italic;">
                            <p style="float:left; margin: 0;font-size: 28px;font-style: italic;font-weight: bold;">the Technical
                                Training Programme on</p>
                            <div style="width: 44rem;float: right;border-bottom: 4px dotted;text-align: center;font-size: 22px;font-style: italic;">
                                <span>BITAC</span></div>
                        </div>
                        <div style="clear: both;padding-top: 25px;font-style: italic;">
                            <p style="float:left; margin: 0;font-size: 28px;font-style: italic;font-weight: bold;">from </p>
                            <div style="width: 25rem; border-bottom: 4px dotted;text-align: center;font-size: 22px;float: left;font-style: italic;">
                                <span>10/10/21</span></div>
                            <p style="font-size: 28px;margin: 0;float: left;font-style: italic;font-weight: bold;">to</p>
                            <div style="width: 25rem; border-bottom: 4px dotted;text-align: center;font-size: 22px;float: left;font-style: italic;">
                                <span>11/10/21</span></div>
                        </div>
                        <div style="clear: both;padding-top: 25px;font-style: italic;">
                            <p style="float:left; margin: 0;font-size: 28px;font-style: italic;font-weight: bold;">We wish him/her
                                every success in life</p>
                        </div>
                    </div>
                    <div style=" clear: both;font-weight: bold; margin-top: 15rem;padding-bottom: 10rem;">
                        <p style="float: left;">COURSE COORDINATOR</p>
                        <p style="float: right;">COURSE DIRECTOR</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
        @endsection

