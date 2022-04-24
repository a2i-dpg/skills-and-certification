@extends('master::layouts.master')

@section('title')
    {{ __($traineeSelfInfo->name) }}
@endsection
@php
    @endphp
@section('content')
    <style>
        youth-profile-pic img {
            margin-left: 14vw;
        }

        .table table {
            font-size: 18px;
        }

        .table td {
            border: 5px solid #f4f6f9;
            padding: 0 !important;
        }

        .table .youth-profile-pic img {
            float: left;
            margin-left: 6vh;
        }

        .custom-small-line-height {
            line-height: 1.2;
        }
    </style>

    <div class="container-fluid">
        <div class="row ">
            <div class="col-md-12 mb-3">
                <div class="card-tools float-right">
                    <a href="#"
                       onclick="history.back();"
                       class="btn btn-sm btn-outline-primary btn-rounded">
                        <i class="fas fa-backward"></i> Back to list
                    </a>
                </div>
            </div>

            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <table class="table">
                            <tr>
                                <td class="border-0"></td>
                                <td class="youth-profile-pic">
                                    <img
                                        src="{{ $traineeSelfInfo->profile_pic ? asset('/storage/' .$traineeSelfInfo->profile_pic) : asset('storage/default_student_pic.jpg')}}"
                                        height="200" width="200" alt="trainee profile pic">
                                </td>
                                <td colspan="3">
                                    <table class="table">
                                        <tbody>
                                        <tr>
                                            <td class="text-bold custom-bg-gradient-info">Trainee Name :</td>
                                            <td class="custom-bg-gradient-info">{{ $traineeSelfInfo->name }}</td>
                                        </tr>
                                        <tr>
                                            <td class="text-bold custom-bg-gradient-info">Father's Name:</td>
                                            <td class="custom-bg-gradient-info">{{ optional($trainee->father)->name }}</td>
                                        </tr>

                                        <tr>
                                            <td class="text-bold custom-bg-gradient-info">Mother's Name:</td>
                                            <td class="custom-bg-gradient-info">{{ optional($trainee->mother)->name }}</td>
                                        </tr>

                                        <tr>
                                            <td class="text-bold custom-bg-gradient-info">Date of Birth:</td>
                                            <td class="custom-bg-gradient-info">{{ optional($traineeSelfInfo)->date_of_birth }}
                                                [YYYY-MM-DD]
                                                {{  \Carbon\Carbon::parse($traineeSelfInfo->date_of_birth)->diff(\Carbon\Carbon::now())->format('%y years, %m months and %d days')}}
                                            </td>
                                        </tr>

                                        <tr>
                                            <td class="text-bold custom-bg-gradient-info">Gender:</td>
                                            <td class="custom-bg-gradient-info">{{ $traineeSelfInfo->getUserGender() ?? '' }}</td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </td>
                            </tr>


                            <tr class="custom-bg-gradient-info">
                                <td class="border-0"></td>
                                <td class="text-bold border-0">Mobile Number:</td>
                                <td class="border-top-0 border-bottom">{{ optional($traineeSelfInfo)->mobile }}</td>

                                <td class="text-bold border-0">Physically Disable:</td>
                                <td class="border-top-0 border-bottom">
                                    {{ $traineeSelfInfo->disable_status == \App\Models\Trainee::IS_DISABLE_YES? 'Yes' : 'No' }}
                                </td>
                            </tr>

                            <tr class="custom-bg-gradient-info">
                                <td class="border-0"></td>

                                <td class="text-bold border-0">Ethnic Minority:</td>
                                <td class="border-top-0 border-bottom">{{ $traineeSelfInfo->ethnic_group == \App\Models\Trainee::ETHNIC_GROUP_YES ? 'Yes' : 'No' }}</td>
                            </tr>
                            <tr>
                                <td class="border-0"></td>
                                <td colspan="4" class="custom-bg-gradient-info text-bold lead text-center">Academic
                                    Qualification's
                                </td>
                            </tr>
                            <tr>
                                <table class="table table-bordered custom-bg-gradient-info">
                                    <tr>
                                        <th class="text-center">{{ __('generic.examination_name')}}</th>
                                        <th class="text-center">{{ __('generic.institute')}}</th>
                                        <th class="text-center">{{ __('generic.subject')}}</th>
                                        <th class="text-center">{{ __('generic.result')}}</th>
                                        <th class="text-center">{{ __('generic.passing_year')}}</th>
                                        <th class="text-center">{{ __('generic.roll_no')}}</th>
                                        <th class="text-center">{{ __('generic.reg_no')}}</th>
                                    </tr>

                                    @if(count($traineeAcademicQualifications) > 0)
                                        @foreach($traineeAcademicQualifications as $key => $academicQualification)
                                            <tr>
                                                

                                                <td class="text-center">
                                                    {{ ($academicQualification->examination_name) ? $academicQualification->examination_name : '' }}
                                                </td>

                                                <td class="text-center">
                                                    {{ ($academicQualification->institute) ? $academicQualification->institute : '' }}
                                                </td>

                                                

                                                <td class="text-center">
                                                    {{ ($academicQualification->subject) ? $academicQualification->subject : '' }}
                                                </td>

                                                <td class="text-center">
                                                    {{ ($academicQualification->result) ? $academicQualification->result : '' }}
                                                </td>


                                                <td class="text-center">
                                                    {{ ($academicQualification->passing_year) ? $academicQualification->passing_year : '' }}
                                                </td>

                                                <td class="text-center">
                                                    {{ $academicQualification->roll_no ?? ''}}
                                                </td>

                                                <td class="text-center">
                                                    {{ $academicQualification->reg_no ?? ''}}
                                                </td>

                                            </tr>
                                        @endforeach
                                    @endif

                                    <tr>
                                        <td colspan="8"></td>
                                    </tr>
                                    <tr>
                                        <td colspan="8"></td>
                                    </tr>
                                    <tr>
                                        <td colspan="8"></td>
                                    </tr>
                                    <tr>
                                        <td colspan="8"></td>
                                    </tr>

                                    <tr>
                                        <td class="border-0"></td>
                                        <td colspan="7" class="custom-bg-gradient-info text-bold lead">Guardian Information
                                        </td>
                                    </tr>

                                    @forelse($guardians as $guardian)
                                        <tr>
                                            <td class="border-0"></td>
                                            <td class="text-bold">Name:</td>
                                            <td>{{ $guardian->name  }}</td>

                                            <td class="text-bold">Relation with Trainee:</td>
                                            <td>{{ $guardian->getGuardian() }}</td>
                                        </tr>

                                        <tr>
                                            <td class="border-0"></td>
                                            <td class="text-bold">Mobile Number:</td>
                                            <td>{{ $guardian->mobile }}</td>
                                        </tr>

                                    @empty
                                        <tr>
                                            <td class="border-0"></td>
                                            <td colspan="7" class="text-bold">Not found!</td>
                                        </tr>
                                    @endforelse

                                    <tr>
                                        <td colspan="8"></td>
                                    </tr>
                                    <tr>
                                        <td colspan="8"></td>
                                    </tr>
                                    <tr>
                                        <td colspan="8"></td>
                                    </tr>
                                    <tr>
                                        <td colspan="8"></td>
                                    </tr>
                                </table>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>

@endsection
