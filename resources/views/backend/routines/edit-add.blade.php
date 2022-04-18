@php
    $edit = !empty($routine->id);
    /** @var \App\Models\User $authUser */
    $authUser = \App\Helpers\Classes\AuthHelper::getAuthUser();
@endphp

@extends('master::layouts.master')

@section('title')
    {{ $edit? __('admin.routine.list')  .' :: '. __('admin.common.edit') : __('admin.routine.list')  .' :: '.  __('admin.common.add')  }}
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header text-primary custom-bg-gradient-info">
                        <h3 class="card-title font-weight-bold">{{ $edit? __('admin.common.edit'):__('admin.common.add') }}</h3>
                        <div class="card-tools">
                            <a href="{{route('admin.routines.index')}}"
                               class="btn btn-sm btn-outline-primary btn-rounded">
                                <i class="fas fa-backward"></i> {{__('admin.common.back')}}
                            </a>
                        </div>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <form
                            action="{{$edit ? route('admin.routines.update', $routine->id) : route('admin.routines.store')}}"
                            method="POST" class="row edit-add-form">
                            @csrf
                            @if($edit)
                                @method('put')
                            @endif

                            @if($authUser->isUserBelongsToInstitute())
                                <input type="hidden" id="institute_id" name="institute_id"
                                       value="{{$authUser->institute_id}}">
                            @else
                                <div class="form-group col-md-6">
                                    <label for="institute_id">{{ __('admin.examination.institute_title') }} <span
                                            style="color: red"> * </span></label>
                                    <select class="form-control select2-ajax-wizard"
                                            name="institute_id"
                                            id="institute_id"
                                            data-model="{{base64_encode(\App\Models\Institute::class)}}"
                                            data-label-fields="{title}"
                                            @if($edit)
                                            data-preselected-option="{{json_encode(['text' => $routine->institute->title, 'id' => $routine->institute_id])}}"
                                            @endif
                                            data-placeholder="{{ __('admin.examination.institute_title') }}"
                                    >
                                    </select>
                                </div>
                            @endif

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="title">{{__('admin.routine.date')}} <span
                                            style="color: red">*</span></label>
                                    <input type="text" class="flat-date flat-date-custom-bg form-control" id="date"
                                           name="date"
                                           value="{{ $edit ? $routine->date : old('date') }}"
                                           placeholder="{{__('admin.routine.date')}}">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="training_center_id">
                                        {{__('admin.routine.training_center')}}
                                        <span class="required" style="color: red">*</span>
                                    </label>

                                    <select class="form-control select2-ajax-wizard"
                                            name="training_center_id"
                                            id="training_center_id"
                                            data-model="{{base64_encode(App\Models\TrainingCenter::class)}}"
                                            data-label-fields="{title}"
                                            data-dependent-fields="#batch_id"
                                            data-filters="{{json_encode(['institute_id' => $authUser->institute_id])}}"
                                            @if($edit)
                                            data-preselected-option="{{json_encode(['text' =>  $routine->trainingCenter->title, 'id' =>  $routine->training_center_id])}}"
                                            @endif
                                            data-placeholder="{{ __('generic.select_placeholder') }}"
                                    >
                                    </select>
                                </div>
                            </div>


                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="batch_id">
                                        {{__('admin.routine.batch_title')}}
                                        <span class="required">*</span>
                                    </label>

                                    <select class="form-control select2-ajax-wizard"
                                            name="batch_id"
                                            id="batch_id"
                                            data-model="{{base64_encode(App\Models\Batch::class)}}"
                                            data-label-fields="{title}"
                                            data-depend-on="training_center_id"
                                            data-filters="{{json_encode(['institute_id' => $authUser->institute_id, 'batch_status' => [App\Models\Batch::BATCH_STATUS_COMPLETE, 'type' => 'not-equal']])}}"
                                            @if($edit)
                                            data-preselected-option="{{json_encode(['text' =>  $routine->batch->title, 'id' =>  $routine->batch_id])}}"
                                            @endif
                                            data-placeholder="{{ __('generic.select_placeholder') }}"
                                    >
                                    </select>

                                </div>
                            </div>

                            <div class="col-sm-12 daily-routines mt-5">
                                <div class="card">
                                    <div class="card-header custom-bg-gradient-info">
                                        <h3 class="card-title text-primary font-weight-bold">{{__('admin.daily_routine.day_routine')}}</h3>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-12 daily-routine-contents"></div>
                                            <div class="col-md-12">
                                                <button type="button"
                                                        class="btn btn-primary add-more-routine-btn float-right">
                                                    <i class="fa fa-plus-circle fa-fw"></i></button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-sm-12 text-right">
                                <button type="submit"
                                        class="btn btn-success float-right">{{ $edit ? __('admin.common.update') : __('admin.common.add') }}</button>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('utils.delete-confirm-modal')

@endsection

@push('css')
    <style>
        .flat-date-custom-bg, .flat-time-custom-bg {
            background-color: #fafdff !important;
        }

        .has-error {
            position: relative;
            padding: 0px 0 12px 0;
        }

        #training_center_id-error {
            position: absolute;
            left: 6px;
            bottom: -9px;
        }

        #institute_id-error, #application_form_type_id-error, #course_id-error, #start_date-error, #end_date-error, #start_time-error, #end_time-error {
            position: absolute;
            left: 6px;
            bottom: -9px;
        }

        .select2-container--default .select2-selection--single {
            background-color: #fafdff;
            border: 2px solid #ddf1ff;
            border-radius: 4px;
        }

        .select2-container .select2-selection--single {
            height: 36px;
            margin-top: 2px;
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 26px;
            position: absolute;
            top: 6px;
            right: 1px;
            width: 20px;
        }


    </style>
@endpush

@push('js')
    <x-generic-validation-error-toastr></x-generic-validation-error-toastr>

    <script>
        const EDIT = !!'{{$edit}}';
        let SL = 0;

        const editAddForm = $('.edit-add-form');
        editAddForm.validate({
            rules: {
                total_mark: {
                    required: true,
                },
                date: {
                    required: true,
                },
                routine_type_id: {
                    required: true
                },
                batch_id: {
                    required: true
                },
                training_center_id: {
                    required: true
                },
            },
            submitHandler: function (htmlForm) {
                $('.overlay').show();
                htmlForm.submit();
            }
        });

        if (!EDIT) {
            $('#start_time').change(function () {
                if ($(this).val() != "") {
                    $(this).valid();
                }
            });
            $('#end_time').change(function () {
                if ($(this).val() != "") {
                    $(this).valid();
                }
            });
        }


        function addRow(data = {}) {
            let dailyRoutine = _.template($('#daily-routines').html());
            let dailyRoutineContentElm = $(".daily-routine-contents");
            dailyRoutineContentElm.append(dailyRoutine({sl: SL, data: data, edit: EDIT}))

            $.validator.addMethod(
                'greaterThan',
                function (value, element, params) {
                    var val = new Date('1/1/2000' + ' ' + value);
                    var par = new Date('1/1/2000' + ' ' + $(params).val());
                    if (!/Invalid|NaN/.test(new Date(val))) {
                        return new Date(val) > new Date(par);
                    }
                    return isNaN(val) && isNaN(par) || (Number(val) > Number(par))

                }, 'End time should be grater than the Start time.'
            );

            for (let i = 0; i <= SL; i++) {
                $.validator.addClassRules("start_time" + i, {
                    required: true,
                });
                $.validator.addClassRules("end_time" + i, {
                    required: true,
                    greaterThan: '.start_time' + i,
                });
            }

            $.validator.addClassRules("class", {
                required: true,
            });
            $.validator.addClassRules("user_id", {
                required: true,
            });

            SL++;
        }

        function deleteRow(slNo) {
            let dailyRoutineELm = $("#daily-routine-no" + slNo);
            if (dailyRoutineELm.find('.delete_status').length) {
                dailyRoutineELm.find('.delete_status').val(1);
                dailyRoutineELm.hide();
            } else {
                dailyRoutineELm.remove();
            }
        }

        $(document).ready(function () {
            @if($edit && $routine->routineSlots->count())
            @foreach($routine->routineSlots as $routine)
            addRow(@json($routine));
            @endforeach
            @else
            addRow();
            @endif

            $(document).on('click', '.add-more-routine-btn', function () {
                addRow();
                $('.select20').select2();
            });
        });



    </script>

    <script>
        $(function () {
            $('.select20').select2();
        })
    </script>
    <script type="text/template" id="daily-routines">
        <div class="card" id="daily-routine-no<%=sl%>">
            <div class="card-header d-flex justify-content-between">
                <h5 class="daily-routine-class<%=sl%>"></h5>
                <div class="card-tools">
                    <button type="button"
                            onclick="deleteRow(<%=sl%>)"
                            class="btn btn-warning less-session-btn float-right mr-2"><i
                            class="fa fa-minus-circle fa-fw"></i></button>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <% if(edit && data.id) { %>
                    <input type="hidden" id="daily_routine_<%=data.id%>" name="daily_routines[<%=sl%>][id]"
                           value="<%=data.id%>">
                    <input type="hidden" name="daily_routines[<%=sl%>][delete]" class="delete_status" value="0"/>
                    <% } %>


                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="trainer_id">
                                {{__('admin.daily_routine.trainer')}}
                                <span class="required">*</span>
                            </label>

                            <select class="form-control select20 user_id"
                                    name="daily_routines[<%=sl%>][user_id]"
                                    id="daily_routines[<%=sl%>][user_id]"
                            >
                                <option value="">{{__('admin.daily_routine.select')}}</option>
                                @foreach($trainers as $trainer)
                                    <option value="{{$trainer->id}}" <%=edit && (data.user_id == {{$trainer->id}}) ?
                                    'selected': ''%>>{{$trainer->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="class">{{__('admin.daily_routine.class')}} <span
                                    style="color: red"> * </span></label>
                            <input type="text"
                                   class="form-control class"
                                   name="daily_routines[<%=sl%>][class]"
                                   placeholder="{{__('admin.daily_routine.class')}}"
                                   value="<%=edit ? data.class : ''%>"
                            >
                        </div>
                    </div>

                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="start_time">{{__('admin.daily_routine.start_time')}} <span
                                    style="color: red">*</span></label>
                            <input type="time"
                                   class="flat-time flat-time-custom-bg form-control start_time start_time<%=sl%> "
                                   name="daily_routines[<%=sl%>][start_time]"
                                   id="daily_routines[<%=sl%>][start_time]"
                                   value="<%=edit ? data.start_time : ''%>">
                        </div>
                    </div>

                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="end_time">{{__('admin.daily_routine.end_time')}} <span
                                    style="color: red">*</span></label>
                            <input type="time"
                                   class="flat-time flat-time-custom-bg form-control end_time end_time<%=sl%> "
                                   name="daily_routines[<%=sl%>][end_time]"
                                   id="daily_routines[<%=sl%>][end_time]"
                                   value="<%=edit ? data.end_time : ''%>">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </script>
@endpush


