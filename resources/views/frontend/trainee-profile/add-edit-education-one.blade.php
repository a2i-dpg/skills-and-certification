@extends('master::layouts.front-end')

@section('title')
{{$siteSettingInfo->site_title}} :: {{ 'Trainee education' }}
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <form class="row edit-add-form" method="post"
                      enctype="multipart/form-data"
                      action="{{route('frontend.trainee-education-info.store')}}">
                    @csrf

                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header  academic-qualifications">
                                <h3 class="card-title font-weight-bold"><i
                                        class="fa fa-address-book"> </i> {{ __('generic.academic_qualification')}}
                                </h3>
                                <div class="card-tools">
                                    <a href="{{route('frontend.trainee')}}"
                                       class="btn btn-sm btn-outline-primary btn-rounded">
                                        <i class="fas fa-backward"></i> {{ __('generic.back_to_profile') }}
                                    </a>
                                </div>
                            </div>
                            <div class="card-body row">
                                @if (count($academicQualifications) == 0)
                                <!-- Start -->
                                <div class="col-md-6 academic-qualification-jsc mb-2">
                                    <div class="card col-md-12" style="height: 100%;">
                                        
                                        <div class="card-body jsc_collapse hide">
                                            
                                            <div class="form-row form-group mt-2">
                                                <label for="examination_name"
                                                       class="col-md-4 col-form-label">{{ __('generic.examination_name')}} </label>
                                                <div class="col-md-8">
                                                    <input type="text"
                                                           name="academicQualification[education1][examination_name]"
                                                           id="examination_name" class="form-control"
                                                           value="" placeholder="{{ __('generic.examination_name')}}">
                                                </div>
                                                <div class="col-md-4"></div>
                                            </div>

                                            <div class="form-row form-group mt-2">
                                                <label for="institute"
                                                       class="col-md-4 col-form-label">{{ __('generic.institute')}}</label>
                                                <div class="col-md-8">
                                                    <input type="text"
                                                           name="academicQualification[education1][institute]"
                                                           id="institute" class="form-control"
                                                           value="" placeholder="{{ __('generic.institute')}}">
                                                </div>
                                                <div class="col-md-4"></div>
                                            </div>

                                            <div class="form-row form-group mt-2">
                                                <label for="roll_no"
                                                       class="col-md-4 col-form-label">{{ __('generic.roll_no')}}</label>
                                                <div class="col-md-8">
                                                    <input type="text"
                                                           name="academicQualification[education1][roll_no]"
                                                           id="roll_no" class="form-control"
                                                           value="" placeholder="{{ __('generic.roll_no')}}">
                                                </div>
                                                <div class="col-md-4"></div>
                                            </div>

                                            <div class="form-row form-group mt-2">
                                                <label for="reg_no"
                                                       class="col-md-4 col-form-label">{{ __('generic.reg_no')}}</label>
                                                <div class="col-md-8">
                                                    <input type="text"
                                                           name="academicQualification[education1][reg_no]"
                                                           id="reg_no" class="form-control"
                                                           value="" placeholder="{{ __('generic.reg_no')}}">
                                                </div>
                                                <div class="col-md-4"></div>
                                            </div>

                                            <div class="form-row form-group mt-2">
                                                <label for="subject"
                                                       class="col-md-4 col-form-label">{{ __('generic.subject')}}</label>
                                                <div class="col-md-8">
                                                    <input type="text"
                                                           name="academicQualification[education1][subject]"
                                                           id="subject" class="form-control"
                                                           value="" placeholder="{{ __('generic.subject')}}">
                                                           <span class="text-info">{{__('frontend.trainee.like_subject')}}</span>
                                                </div>
                                                <div class="col-md-4"></div>
                                            </div>

                                            <div class="form-row form-group mt-2">
                                                <label for="result"
                                                       class="col-md-4 col-form-label">{{ __('generic.result')}}</label>
                                                <div class="col-md-8">
                                                    <input type="text"
                                                           name="academicQualification[education1][result]"
                                                           id="result" class="form-control"
                                                           value="" placeholder="{{ __('generic.result')}}">
                                                           <span class="text-info">{{__('frontend.trainee.like_result')}}</span>
                                                </div>
                                                <div class="col-md-4"></div>
                                            </div>

                                            <div class="form-row form-group mt-2">
                                                <label for="passing_year"
                                                       class="col-md-4 col-form-label">{{ __('generic.passing_year')}}</label>
                                                <div class="col-md-8">
                                                    <input type="text"
                                                           name="academicQualification[education1][passing_year]"
                                                           id="passing_year" class="form-control"
                                                           value="" placeholder="{{ __('generic.passing_year')}}">
                                                </div>
                                                <div class="col-md-4"></div>
                                            </div>
                                            
                                        </div>
                                    </div>
                                </div>
                                <!-- Start -->    
                                @else
                                @foreach ($academicQualifications as $key=> $academicQualification)
                                <input type="hidden" name="academicQualification[education{{$key+1}}][id]" value="{{$academicQualification->id}}">
                                <!-- Start -->
                                <div class="col-md-6 academic-qualification-jsc mb-2">
                                    <div class="card col-md-12" style="height: 100%;">
                                        
                                        <div class="card-body jsc_collapse hide">
                                            
                                            <div class="form-row form-group mt-2">
                                                <label for="examination_name"
                                                       class="col-md-4 col-form-label">{{ __('generic.examination_name')}}</label>
                                                <div class="col-md-8">
                                                    <input type="text"
                                                           name="academicQualification[education{{$key+1}}][examination_name]"
                                                           id="examination_name" class="form-control"
                                                           value="{{$academicQualification->examination_name}}" placeholder="{{ __('generic.examination_name')}}">
                                                </div>
                                                <div class="col-md-4"></div>
                                            </div>

                                            <div class="form-row form-group mt-2">
                                                <label for="institute"
                                                       class="col-md-4 col-form-label">{{ __('generic.institute')}}</label>
                                                <div class="col-md-8">
                                                    <input type="text"
                                                           name="academicQualification[education{{$key+1}}][institute]"
                                                           id="institute" class="form-control"
                                                           value="{{$academicQualification->institute}}" placeholder="{{ __('generic.institute')}}">
                                                </div>
                                                <div class="col-md-4"></div>
                                            </div>

                                            <div class="form-row form-group mt-2">
                                                <label for="roll_no"
                                                       class="col-md-4 col-form-label">{{ __('generic.roll_no')}}</label>
                                                <div class="col-md-8">
                                                    <input type="text"
                                                           name="academicQualification[education{{$key+1}}][roll_no]"
                                                           id="roll_no" class="form-control"
                                                           value="{{$academicQualification->roll_no}}" placeholder="{{ __('generic.roll_no')}}">
                                                </div>
                                                <div class="col-md-4"></div>
                                            </div>

                                            <div class="form-row form-group mt-2">
                                                <label for="reg_no"
                                                       class="col-md-4 col-form-label">{{ __('generic.reg_no')}}</label>
                                                <div class="col-md-8">
                                                    <input type="text"
                                                           name="academicQualification[education{{$key+1}}][reg_no]"
                                                           id="reg_no" class="form-control"
                                                           value="{{$academicQualification->reg_no}}" placeholder="{{ __('generic.reg_no')}}">
                                                </div>
                                                <div class="col-md-4"></div>
                                            </div>

                                            

                                            <div class="form-row form-group mt-2">
                                                <label for="subject"
                                                       class="col-md-4 col-form-label">{{ __('generic.subject')}} </label>
                                                <div class="col-md-8">
                                                    <input type="text"
                                                           name="academicQualification[education{{$key+1}}][subject]"
                                                           id="subject" class="form-control"
                                                           value="{{$academicQualification->subject}}" placeholder="{{ __('generic.subject')}}">
                                                           <span class="text-info">{{__('frontend.trainee.like_subject')}}</span>
                                                </div>
                                                <div class="col-md-4"></div>
                                            </div>

                                            <div class="form-row form-group mt-2">
                                                <label for="result"
                                                       class="col-md-4 col-form-label">{{ __('generic.result')}} </label>
                                                <div class="col-md-8">
                                                    <input type="text"
                                                           name="academicQualification[education{{$key+1}}][result]"
                                                           id="result" class="form-control"
                                                           value="{{$academicQualification->result}}" placeholder="{{ __('generic.result')}}">
                                                           <span class="text-info">{{__('frontend.trainee.like_result')}}</span>
                                                </div>
                                                <div class="col-md-4"></div>
                                            </div>

                                            <div class="form-row form-group mt-2">
                                                <label for="passing_year"
                                                       class="col-md-4 col-form-label">{{ __('generic.passing_year')}} </label>
                                                <div class="col-md-8">
                                                    <input type="text"
                                                           name="academicQualification[education{{$key+1}}][passing_year]"
                                                           id="passing_year" class="form-control"
                                                           value="{{$academicQualification->passing_year}}" placeholder="{{ __('generic.passing_year')}}">
                                                           
                                                </div>
                                                <div class="col-md-4"></div>
                                            </div>

                                            
                                            <div class="form-row form-group mt-0">
                                                <div class="col-md-9">
                                                </div>
                                                <div class="col-md-3">
                                                    <a style="margin-left: 98%;" title="Delete This Section" class="btn btn-danger btn-sm" href="{{route('frontend.trainee-education-info.single-delete',[$academicQualification->id, $academicQualification->trainee_id])}} "> <i class="fa fa-trash" aria-hidden="true"></i> </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- Start -->
                                @endforeach
                                @endif

                                <div class="row col-md-12 container1"></div>

                            </div>

                            
                            <div class="row">

                                <div class="col-md-10"></div>
                                <div class="col-md-2">
                                    <button class="btn btn-info add_form_field" style="width: 100%">{{__('frontend.trainee.add_more')}} <i class="fa fa-plus" aria-hidden="true"></i></button>
                                </div>
                            </div>
                        </div>
                    </div>
                    

                    <div class="col-sm-12 text-center pb-3">
                        <button type="submit"
                                class="btn btn-success btn-md" style="width:100%">{{ __('generic.save') }}</button>
                    </div>
                </form>
            </div><!-- /.card-body -->
            <div class="overlay" style="display: none">
                <i class="fas fa-2x fa-sync-alt fa-spin"></i>
            </div>
        </div>
    </div>


@endsection

@push('js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/lodash.js/4.17.21/lodash.js"></script>

    <x-generic-validation-error-toastr></x-generic-validation-error-toastr>

    <script>
        $(document).ready(function(){
            let max_fields      = 30;
            let wrapper         = $(".container1");
            let add_button      = $(".add_form_field"); 

            @if (count($academicQualifications) > 0)
            let x = {{count($academicQualifications)}}; 
            @else
            let x = 1;
            @endif

            
            $(add_button).click(function (event) {
                event.preventDefault();
                if(x < max_fields){
                    x++;

                    $(wrapper).append(
                                '<div class="col-md-6 col-sm-offset-3 academic-qualification-jsc mb-2">'+
                                    '<div class="card col-md-12" style="height: 100%;">'+
                                        '<div class="card-body jsc_collapse hide">'+

                                            '<div class="form-row form-group mt-2">'+
                                                '<label for="examination_name" class="col-md-4 col-form-label">{{ __('generic.examination_name')}}</label>'+
                                                '<div class="col-md-8">'+
                                                    '<input type="text" name="academicQualification[education'+ x +'][examination_name]" id="examination_name" class="form-control" value="" placeholder="{{ __('generic.examination_name')}}">'+
                                                '</div>'+
                                                '<div class="col-md-4"></div>'+
                                            '</div>'+

                                            '<div class="form-row form-group mt-2">'+
                                                '<label for="institute" class="col-md-4 col-form-label">{{ __('generic.institute')}}</label>'+
                                                '<div class="col-md-8">'+
                                                    '<input type="text" name="academicQualification[education'+ x +'][institute]" id="institute" class="form-control" value="" placeholder="{{ __('generic.institute')}}">'+
                                                '</div>'+
                                                '<div class="col-md-4"></div>'+
                                            '</div>'+

                                            '<div class="form-row form-group mt-2">'+
                                                '<label for="roll_no" class="col-md-4 col-form-label">{{ __('generic.roll_no')}}</label>'+
                                                '<div class="col-md-8">'+
                                                    '<input type="text" name="academicQualification[education'+ x +'][roll_no]" id="roll_no" class="form-control" value="" placeholder="{{ __('generic.roll_no')}}">'+
                                                '</div>'+
                                                '<div class="col-md-4"></div>'+
                                            '</div>'+

                                            '<div class="form-row form-group mt-2">'+
                                                '<label for="reg_no" class="col-md-4 col-form-label">{{ __('generic.reg_no')}}</label>'+
                                                '<div class="col-md-8">'+
                                                    '<input type="text" name="academicQualification[education'+ x +'][reg_no]" id="reg_no" class="form-control" value="" placeholder="{{ __('generic.reg_no')}}">'+
                                                '</div>'+
                                                '<div class="col-md-4"></div>'+
                                            '</div>'+

                                            '<div class="form-row form-group mt-2">'+
                                                '<label for="subject" class="col-md-4 col-form-label">{{ __('generic.subject')}}</label>'+
                                                '<div class="col-md-8">'+
                                                    '<input type="text" name="academicQualification[education'+ x +'][subject]" id="subject" class="form-control" value="" placeholder="{{ __('generic.subject')}}">'+
                                                    '<span class="text-info">{{__('frontend.trainee.like_subject')}}</span>'+
                                                '</div>'+
                                                '<div class="col-md-4"></div>'+
                                            '</div>'+

                                            '<div class="form-row form-group mt-2">'+
                                                '<label for="result" class="col-md-4 col-form-label">{{ __('generic.result')}}</label>'+
                                                '<div class="col-md-8">'+
                                                    '<input type="text" name="academicQualification[education'+ x +'][result]" id="result" class="form-control" value="" placeholder="{{ __('generic.result')}}">'+
                                                    '<span class="text-info">{{__('frontend.trainee.like_result')}}</span>'+
                                                '</div>'+
                                                '<div class="col-md-4"></div>'+
                                            '</div>'+

                                            '<div class="form-row form-group mt-2">'+
                                                '<label for="passing_year" class="col-md-4 col-form-label">{{ __('generic.passing_year')}}</label>'+
                                                '<div class="col-md-8">'+
                                                    '<input type="text" name="academicQualification[education'+ x +'][passing_year]" id="passing_year" class="form-control" value="" placeholder="{{ __('generic.passing_year')}}">'+
                                                '</div>'+
                                                '<div class="col-md-4"></div>'+
                                            '</div>'+

                                        '</div>'+
                                        '<button title="Remove This Section" id="delete'+ x +'"class="delete btn btn-warning btn-sm" style="margin-left: 90%;"> <i class="fa fa-minus-circle" aria-hidden="true"></i></button>'+
                                    '</div>'+
                                '</div>'
                            );
                }else{
                    alert('You Reached the limits')
                }
            });

            $(wrapper).on("click",".delete", function(e){ 
                e.preventDefault(); 
                $(this).parent('div').parent('div').remove();x--;
            });
        });
    </script>
@endpush



