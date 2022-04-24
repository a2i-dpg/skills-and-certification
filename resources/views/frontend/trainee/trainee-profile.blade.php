@php
    $currentInstitute = app('currentInstitute');
    $layout = 'master::layouts.front-end';
@endphp
@extends($layout)

@section('title')
{{$siteSettingInfo->site_title}} :: {{__('generic.youth_profile')}}
@endsection

@push('css')
    <style>
        .profile-info-p {
            line-height: normal;
        }
    </style>

@endpush

@section('content')
    <div class="container-fluid">
        <div class="row trainee-profile justify-content-center" id="trainee-profile">

            <div class="col-md-10 mt-2 personal-info-section">
                <div class="card">
                    <div class="card-header">
                        <div class="card-title">
                            <h4 class="font-weight-bolder">Personal Information</h4>
                        </div>

                        <div class="card-tools">
                            <a href="{{route('frontend.edit-personal-info')}}"
                               class="btn btn-sm btn-primary btn-rounded">
                                <i class="fas fa-plus-circle"></i> {{__('admin.common.edit')}}
                            </a>
                        </div>

                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-2">
                                <img class="img-circle"
                                     src="{{ $trainee->profile_pic ? asset('storage/'. $trainee->profile_pic ) : "http://via.placeholder.com/640x360"}}"
                                     height="100" width="100" alt="">
                            </div>
                            <div class="col-md-8 col-offset-md-1">
                                <h5>{{ $trainee->name }}</h5>
                                <div class="text-muted">
                                    <p class="profile-info-p"> {{ __('generic.email') }}: {{ $trainee->email }}</p>
                                    <p class="profile-info-p">{{ __('generic.mobile') }}: {{ $trainee->mobile }}</p>
                                    <p class="profile-info-p">{{ __('generic.address') }}:
                                         {{ optional($trainee)->address }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-10 education-info-section">
                <div class="card">
                    <div class="card-header">
                        <div class="card-title">
                            <h4 class="font-weight-bolder">Education</h4>
                        </div>
                        <div class="card-tools">
                            <a href="{{route('frontend.add-edit-education', ['id' => $trainee->user_id])}}"
                               class="btn btn-sm btn-primary btn-rounded">
                                <i class="fas fa-plus-circle"></i> {{__('admin.common.add')}}
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        @forelse($academicQualificationns as $academicQualification)
                            <div class="row">
                                <div class="col-md-2 my-3">
                                    <i class="fas fa-book-reader" style="font-size: xxx-large"></i>
                                </div>
                                <div class="col-md-3">
                                    <div class="row my-3">
                                        @if ($academicQualification->examination_name)
                                            <div class="col-md-12">
                                                <h5 class="font-weight-bolder">  {{ $academicQualification->examination_name}}</h5>
                                            </div>
                                        @endif

                                        @if ($academicQualification->result)
                                            <div class="col-md-12">
                                                <span class="font-weight-bold">{{ __('generic.result')}}: </span> {{ $academicQualification->result }}
                                            </div>
                                        @endif

                                        @if ($academicQualification->institute)
                                            <div class="col-md-12">
                                                <span class="font-weight-bold">{{ __('generic.institute')}}: </span> {{ $academicQualification->institute }}
                                            </div>
                                        @endif

                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="row my-3">
                                        <div class="col-md-12">
                                            <h5 class="font-weight-bolder" style="visibility: hidden;">  {{ $academicQualification->examination_name}}</h5>
                                        </div>

                                        @if ($academicQualification->subject)
                                            <div class="col-md-12">
                                                <span class="font-weight-bold">{{ __('generic.subject')}}: </span> {{ $academicQualification->subject }}
                                            </div>
                                        @endif

                                        {{-- @if ($academicQualification->group)
                                            <div class="col-md-12">
                                                <span class="font-weight-bold">Subject: </span> {{ $academicQualification->group }}
                                            </div>
                                        @endif --}}

                                        @if ($academicQualification->passing_year)
                                            <div class="col-md-12">
                                                <span class="font-weight-bold">{{ __('generic.passing_year')}}: </span> {{ $academicQualification->passing_year }}
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="row my-3">
                                        <div class="col-md-12">
                                            <h5 class="font-weight-bolder" style="visibility: hidden;">  {{ $academicQualification->examination_name}}</h5>
                                        </div>

                                        @if ($academicQualification->roll_no)
                                            <div class="col-md-12">
                                                <span class="font-weight-bold">{{ __('generic.roll_no')}}: </span> {{ $academicQualification->roll_no }}
                                            </div>
                                        @endif

                                        @if ($academicQualification->reg_no)
                                            <div class="col-md-12">
                                                <span class="font-weight-bold">{{ __('generic.reg_no')}}: </span> {{ $academicQualification->reg_no }}
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @empty
                            <p>Not found</p>
                        @endforelse
                    </div>
{{--                     
                    <div class="card-body">
                        @forelse($academicQualifications as $academicQualification)
                            <div class="row">
                                <div class="col-md-2">
                                    @if($academicQualification->examination >= 3 && $academicQualification->examination <= 4)
                                        <i class="fas fa-graduation-cap" style="font-size: xxx-large"></i>
                                    @else
                                        <i class="fas fa-book-reader" style="font-size: xxx-large"></i>
                                    @endif
                                </div>
                                <div class="col-md-10">
                                    <div class="row my-3">
                                        <div class="col-md-12">
                                            @switch($academicQualification->examination)
                                                @case(\App\Models\TraineeAcademicQualification::EXAMINATION_JSC)
                                                <h5
                                                    class="font-weight-bolder">  {{ $academicQualification->getExamination() .'/'. $academicQualification->getJSCExaminationName() }}</h5>
                                                @break
                                                @case(\App\Models\TraineeAcademicQualification::EXAMINATION_SSC)
                                                <h5 class="font-weight-bolder">
                                                    {{ $academicQualification->getExamination() .'/'. $academicQualification->getSSCExaminationName() }}
                                                </h5>
                                                @break
                                                @case(\App\Models\TraineeAcademicQualification::EXAMINATION_HSC)
                                                <h5 class="font-weight-bolder">
                                                    {{ $academicQualification->getExamination() .'/'. $academicQualification->getHSCExaminationName() }}</h5>
                                                @break
                                                @case(\App\Models\TraineeAcademicQualification::EXAMINATION_GRADUATION)
                                                <h5 class="font-weight-bolder">
                                                    {{ $academicQualification->getExamination() .'/'. $academicQualification->getGraduationExaminationName() }}</h5>
                                                @break
                                                @case(\App\Models\TraineeAcademicQualification::EXAMINATION_MASTERS)
                                                <h5 class="font-weight-bolder">
                                                    {{ $academicQualification->getExamination() .'/'. $academicQualification->getMastersExaminationName() }}</h5>
                                                @break
                                            @endswitch
                                        </div>

                                        <div class="col-md-12">
                                            <span class="font-weight-bold">Result: </span>
                                            {{ $academicQualification->grade == null ? $academicQualification->getExaminationResult() : 'GPA '.$academicQualification->grade . ($academicQualification->getExaminationResult() ? '/'.$academicQualification->getExaminationResult():'') }}
                                        </div>

                                        <div class="col-md-12">
                                            <div class="row">
                                                <div class="col-md-3">

                                                    @if($academicQualification->examination == \App\Models\TraineeAcademicQualification::EXAMINATION_JSC || $academicQualification->examination == \App\Models\TraineeAcademicQualification::EXAMINATION_SSC || $academicQualification->examination == \App\Models\TraineeAcademicQualification::EXAMINATION_HSC)
                                                        <span
                                                            class="font-weight-bold">Board: </span>{{ $academicQualification->getExaminationTakingBoard() }}
                                                    @else
                                                        <span
                                                            class="font-weight-bold">Institute: </span>{{ $academicQualification->getCurrentUniversity() }}
                                                    @endif
                                                </div>

                                                <div class="col-md-3">
                                                    <span class="font-weight-bold">Passing Year: </span>
                                                    {{ $academicQualification->passing_year }}
                                                </div>

                                                <div class="col-md-3">

                                                    @if($academicQualification->getExaminationGroup())
                                                        <span
                                                            class="font-weight-bold">Group: </span>{{$academicQualification->getExaminationGroup()}}
                                                    @elseif($academicQualification->subject)
                                                        <span
                                                            class="font-weight-bold">Subject: </span>{{$academicQualification->subject ?? 'N/A'}}
                                                    @else
                                                    @endif

                                                </div>
                                            </div>

                                        </div>

                                    </div>
                                </div>
                            </div>
                        @empty
                            <p>Not found</p>
                        @endforelse
                    </div>--}}
                </div> 
                
            </div>

            <div class="col-md-10 guardian-info-section">
                <div class="card">
                    <div class="card-header">
                        <div class="card-title">
                            <h4 class="font-weight-bolder">Guardian</h4>
                        </div>
                        <div class="card-tools">
                            <a href="{{route('frontend.add-guardian-info')}}"
                               class="btn btn-sm btn-primary btn-rounded">
                                <i class="fas fa-plus-circle"></i> {{__('admin.common.add')}}
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        @forelse($guardians as $guardian)
                            <div class="row my-2">
                                <div class="col-md-2">
                                    <i class="fa fa-user" style="font-size: xxx-large"></i>
                                </div>
                                <div class="col-md-8">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <span
                                                class="font-weight-bold">{{ __('generic.name') }}:</span> {{ $guardian->name }}
                                        </div>
                                        <div class="col-md-12">
                                            <span
                                                class="font-weight-bold">{{ __('generic.mobile') }}:</span> {{ $guardian->mobile }}
                                        </div>
                                        <div class="col-md-12">
                                            <span
                                                class="font-weight-bold">{{ __('generic.gender') }}:</span> {{ $guardian->getUserGender() }}
                                            <span
                                                class="ml-2 font-weight-bold">{{ __('generic.relation') }}:</span> {{ $guardian->getGuardian()}}
                                        </div>

                                    </div>
                                </div>

                                <div class="col-md-2">
                                    <div class="card-tools float-right">
                                        <a href="{{route('frontend.add-guardian-info', ['id' => $guardian->id])}}"
                                           class="btn btn-sm btn-primary btn-rounded">
                                            <i class="fas fa-plus-circle"></i> {{__('admin.common.edit')}}
                                        </a>
                                    </div>
                                </div>

                            </div>
                        @empty
                            <p>Not found</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script src="{{ asset('jsfiles/html2canvas.min.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.40/pdfmake.min.js"></script>
    <script type="text/javascript">
        function getClippedRegion(image, x, y, width, height) {
            let canvas = document.createElement("canvas"), ctx = canvas.getContext("2d");
            canvas.width = width;
            canvas.height = height;
            ctx.drawImage(image, x, y, width, height, 0, 0, width, height);

            return {
                image: canvas.toDataURL(),
                width: 500
            };
        }

        function Export() {
            $('#downloadPDF').hide();
            $('meta').attr('name', 'viewport').attr('initial-scal', '1.0');
            html2canvas($("#trainee-profile")[0], {
                onrendered: function (canvas) {
                    let splitAt = 775;
                    let images = [];
                    let y = 0;
                    while (canvas.height > y) {
                        images.push(getClippedRegion(canvas, 0, y, canvas.width, splitAt));
                        y += splitAt;
                    }

                    let docDefinition = {
                        content: images,
                        pageSize: {width: 580, height: 850},
                    };
                    pdfMake.createPdf(docDefinition).download("trainee-profile.pdf");

                    setTimeout(function () {
                        window.location.reload(true);
                    }, 5000);
                }
            });
        }
    </script>

@endpush


