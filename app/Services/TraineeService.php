<?php

namespace App\Services;


use App\Helpers\Classes\AuthHelper;
use App\Helpers\Classes\DatatableHelper;
use App\Models\LocDistrict;
use App\Models\LocDivision;
use App\Models\LocUpazila;
use App\Models\User;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rule;
use App\Models\BaseModel;
use App\Models\Trainee;
use App\Models\TraineeCourseEnroll;
use App\Models\TraineeFamilyMemberInfo;
use App\Models\TraineeRegistration;
use Yajra\DataTables\Facades\DataTables;

class TraineeService
{
    public function validateAcceptNowAll(Request $request): Validator
    {
        $rules = [
            'trainee_ids' => ['bail', 'required', 'array', 'min:1'],
        ];
        return \Illuminate\Support\Facades\Validator::make($request->all(), $rules);
    }

    public function validateRejectNowAll(Request $request): Validator
    {
        $rules = [
            'trainee_ids' => ['bail', 'required', 'array', 'min:1'],
        ];
        return \Illuminate\Support\Facades\Validator::make($request->all(), $rules);
    }


    public function getListDataForDatatable(Request $request): JsonResponse
    {
        /** @var User $authUser */
        $authUser = AuthHelper::getAuthUser();

        $trainees = Trainee::select([
            DB::raw('max(trainees.id) AS id'),
            DB::raw('max(trainees.name) AS name'),
            DB::raw('max(institutes.title) AS institute_title'),
            DB::raw('max(institutes.id) AS institute_id'),
        ]);
        $trainees->leftJoin('trainee_course_enrolls', 'trainees.id', '=', 'trainee_course_enrolls.trainee_id');
        $trainees->leftJoin('courses', 'courses.id', '=', 'trainee_course_enrolls.course_id');
        $trainees->leftJoin('institutes', 'institutes.id', '=', 'courses.institute_id');
        $trainees->groupBy('trainees.id');

        if ($authUser->isUserBelongsToInstitute()) {
            $trainees->where(['institutes.id' => $authUser->institute_id]);
        }

        $traineeName = $request->input('trainee_name');

        if ($traineeName) {
            $trainees->where('trainees.name', 'LIKE', '%' . $traineeName . '%');
        }

        return DataTables::eloquent($trainees)
            ->addColumn('action', DatatableHelper::getActionButtonBlock(static function (Trainee $trainee) {
                $str = '';
                $str .= '<a href="' . route('frontend.trainee-registrations.show', $trainee->id) . '" class="btn btn-outline-info btn-sm"> <i class="fas fa-eye"></i> ' . __('generic.read_button_label') . ' </a>';
                /*$str .= '<a href="' . route('admin.trainees.certificate.course', $trainee->id) . '" class="btn btn-outline-warning btn-sm"> <i class="fas fa-user-graduate"></i> ' . __('View Certificate') . ' </a>';*/
                return $str;
            }))
            ->rawColumns(['action'])
            ->toJson();
    }


    public function addToTraineeAcceptedList(array $traineeAcceptListNowIds): bool
    {
        foreach ($traineeAcceptListNowIds as $traineeAcceptListNowId) {

            /** @var TraineeRegistration $traineeCourseEnroll */
            $traineeCourseEnroll = TraineeCourseEnroll::where('trainee_id', $traineeAcceptListNowId)->first();

            $trainee = Trainee::findOrFail($traineeAcceptListNowId);

            $data = [
                'enroll_status' => TraineeCourseEnroll::ENROLL_STATUS_ACCEPT,
            ];

            if ($traineeCourseEnroll->update($data)) {
                if (!empty($trainee->mobile)) {
                    try {
                        $link = route('frontend.trainee-enrolled-courses');
                        $traineeName = strtoupper($trainee->name);
                        $messageBody = "Dear $traineeName, Your course enrolment is accepted. Please payment within 72 hours. visit " . $link . " for payment";
                        $smsResponse = sms()->send($trainee->mobile, $messageBody);
                        if (!$smsResponse->is_successful()) {
                            sms()->send($trainee->mobile, $messageBody);
                        }
                    } catch (\Throwable $exception) {
                        Log::debug($exception->getMessage());
                    }
                };

                if (!empty($trainee->email)) {
                    $link = route('frontend.trainee-enrolled-courses');
                    $traineeEmailAddress = $trainee->email;
                    $mailMsg = "Congratulations! Your application has been accepted, Please pay now within 72 hours.<p>Payment Link: $link </p>";
                    $mailSubject = "Congratulations! Your application has been accepted";
                    $traineeName = $trainee->name;
                    try {
                        Mail::to($traineeEmailAddress)->send(new \App\Mail\TraineeApplicationAcceptMail($mailSubject, $trainee->access_key, $mailMsg, $traineeName));
                    } catch (\Throwable $exception) {
                        Log::debug($exception->getMessage());
                    }
                };
            }


        }
        return true;
    }

    public function rejectTraineeAll(array $traineeAcceptListNowId): bool
    {
        foreach ($traineeAcceptListNowId as $traineeAcceptListNowIds) {

            /** @var TraineeRegistration $traineeCourseEnroll */
            $traineeCourseEnroll = TraineeCourseEnroll::where('trainee_id', $traineeAcceptListNowIds)->first();
            $traineeCourseEnroll->update([
                'enroll_status' => TraineeCourseEnroll::ENROLL_STATUS_REJECT,
            ]);
            $traineeCourseEnroll->save();
        }
        return true;
    }

    public static function isDate($value): bool
    {
        if (!$value) {
            return false;
        }

        try {
            return boolval(new \DateTime($value));
        } catch (\Exception $e) {
            return false;
        }
    }

    public function traineeImportDataValidate(array $data, $row_number): Validator
    {
        $row_number = $row_number + 1;
        $messages = [
            'required' => "The :attribute in row " . $row_number . " is required",
            'string' => 'The :attribute in row ' . $row_number . ' must be text format',
            'numeric' => 'The :attribute in row ' . $row_number . ' must be numeric format',
            'unique' => "The :attribute in row " . $row_number . " is already taken(trainee)",
            "in" => "The :attribute in row " . $row_number . " is either HAVE OR HAVE NO ",
            "mobile.regex" => "The :attribute in row " . $row_number . " is not valid format as like 01XXXXXXXXXXX",
            "member_mobile.regex" => "The :attribute in row " . $row_number . " is not valid format as like 01XXXXXXXXXXX"
        ];

        $rules = [
            "access_key" => [
                "required",
                "unique:trainees,access_key"
            ],
            "name" => [
                "nullable",
                "string"
            ],
            "mobile" => [
                "required",
                BaseModel::MOBILE_REGEX
            ],
            "email" => [
                "required",
                "unique:trainees,email"
            ],
            "present_address_division_id" => [
                "required",
                function ($attr, $value, $fails) use ($row_number) {
                    $locDivision = LocDivision::where('id', $value)->first();
                    if ($locDivision) {
                        return true;
                    } else {
                        $locDivision = LocDivision::all()->pluck('title')->toArray();
                        $fails("The present address division name in row " . $row_number . " will be " . implode(', ', $locDivision));
                    }
                },
                "numeric"
            ],
            "present_address_district_id" => [
                "required",
                function ($attr, $value, $fails) use ($row_number, $data) {
                    $locDistrict = LocDistrict::where('id', $value)->first();
                    if ($locDistrict) {
                        return true;
                    } else {
                        $locDistrict = LocDistrict::where('loc_division_id', $data['present_address_division_id'])->pluck('title')->toArray();
                        $fails("The present address district name in row " . $row_number . " will be " . implode(', ', $locDistrict ?? []));
                    }
                },
                "numeric"
            ],
            "present_address_upazila_id" => [
                "required",
                function ($attr, $value, $fails) use ($row_number, $data) {
                    $locUpazila = LocUpazila::where('id', $value)->first();
                    if ($locUpazila) {
                        return true;
                    } else {
                        $locUpazila = LocUpazila::where('loc_district_id', $data['present_address_district_id'])->pluck('title')->toArray();
                        $fails("The present address upazila name in row " . $row_number . " will be " . implode(', ', $locUpazila ?? []));
                    }
                },
                "numeric"
            ],
            "present_address_house_address" => [
                "required",
                "array"
            ],
            "permanent_address_division_id" => [
                "required",
                function ($attr, $value, $fails) use ($row_number) {
                    $locDivision = LocDivision::where('id', $value)->first();
                    if ($locDivision) {
                        return true;
                    } else {
                        $locDivision = LocDivision::all()->pluck('title')->toArray();
                        $fails("The permanent address division name in row " . $row_number . " will be " . implode(', ', $locDivision));
                    }
                },
                "numeric"
            ],
            "permanent_address_district_id" => [
                "required",
                function ($attr, $value, $fails) use ($row_number, $data) {
                    $locDistrict = LocDistrict::where('id', $value)->first();
                    if ($locDistrict) {
                        return true;
                    } else {
                        $locDistrict = LocDistrict::where('loc_division_id', $data['permanent_address_division_id'])->pluck('title')->toArray();
                        $fails("The permanent address district name in row " . $row_number . " will be " . implode(', ', $locDistrict ?? []));
                    }
                },
                "numeric"
            ],
            "permanent_address_upazila_id" => [
                "required",
                function ($attr, $value, $fails) use ($row_number, $data) {
                    $locUpazila = LocUpazila::where('id', $value)->first();
                    if ($locUpazila) {
                        return true;
                    } else {
                        $locUpazila = LocUpazila::where('loc_district_id', $data['permanent_address_district_id'])->pluck('title')->toArray();
                        $fails("The permanent address upazila name in row " . $row_number . " will be " . implode(', ', $locUpazila ?? []));
                    }
                },
                "numeric"
            ],
            "permanent_address_house_address" => [
                "required",
                "array"
            ],
            "ethnic_group" => [
                "nullable",
                Rule::in(Trainee::ETHNIC_GROUP_YES, Trainee::ETHNIC_GROUP_NO)
            ],
            "trainee_registration_no" => [
                "nullable"
            ],
            "recommended_by_organization" => [
                "nullable",
                "numeric",
            ],
            "recommended_org_name" => [
                "nullable",
                "string"
            ],
            "current_employment_status" => [
                "nullable",
                Rule::in([Trainee::CURRENT_EMPLOYMENT_STATUS_YES, Trainee::CURRENT_EMPLOYMENT_STATUS_NO])
            ],
            "year_of_experience" => [
                "nullable",
                "numeric"
            ],
            "personal_monthly_income" => [
                "nullable",
                "numeric"
            ],
            "have_family_own_house" => [
                "nullable",
                //Rule::in([Trainee::HAVE_NO_FAMILY_OWN_HOUSE, Trainee::HAVE_FAMILY_OWN_HOUSE]),
                Rule::in([0, 1, 2, 3]),
            ],
            "have_family_own_land" => [
                "nullable",
                //Rule::in([Trainee::HAVE_FAMILY_OWN_LAND, Trainee::HAVE_NO_FAMILY_OWN_LAND]),
                Rule::in([0, 1, 2, 3]),
            ],
            "number_of_siblings" => [
                "nullable",
                "numeric"
            ],
            "student_signature_pic" => [
                "nullable",
                "string"
            ],
            "student_pic" => [
                "nullable",
                "string"
            ]
        ];
        return \Illuminate\Support\Facades\Validator::make($data, $rules, $messages);

    }

    public function traineeAcademicInfoImportDataValidate(array $data, $row_number): Validator
    {
        $row_number = $row_number + 1;
        $messages = [
            'required' => "The :attribute in row " . $row_number . " is required",
            'string' => 'The :attribute in row ' . $row_number . ' must be text format',
            'numeric' => 'The :attribute in row ' . $row_number . ' must be numeric format',
            'unique' => "The :attribute of in row " . $row_number . " is already taken",
            "in" => "The :attribute in row " . $row_number . " is not within :fields",
            "mobile.regex" => "The :attribute in row " . $row_number . " is not valid format as like 1XXXXXXXXXXX",
            "member_mobile.regex" => "The :attribute in row " . $row_number . " is not valid format as like 01XXXXXXXXXXX",
            "lte" => "The :attribute in row " . $row_number . " must be less than or equal :value.",
            "gte" => "The :attribute in row " . $row_number . " must be greater than or equal :value."
        ];

        $rules = [
            "examination" => [
                "required",
                "numeric"
            ],
            "examination_name" => [
                "required",
                "string"
            ],
            "board" => [
                "nullable",
                "integer"
            ],
            "institute" => [
                "nullable",
                "string"
            ],
            "roll_no" => [
                "nullable"
            ],
            "reg_no" => [
                "nullable"
            ],
            "result" => [
                "required",
                "numeric"
            ],
            "grade" => [
                Rule::requiredIf(function () use ($data) {
                    return !empty($data['result']) && in_array($data['result'], [Trainee::EXAMINATION_RESULT_GPA_OUT_OF_FIVE, Trainee::EXAMINATION_RESULT_GPA_OUT_OF_FIVE]);
                }),
                "nullable",
                "numeric",
                "lte:5",
                "gt:0"
            ],
            "group" => [
                Rule::requiredIf(function () use ($data) {
                    return in_array($data['examination'], [Trainee::EXAMINATION_SSC, Trainee::EXAMINATION_HSC]);
                }),
                "nullable",
                "numeric"
            ],
            "passing_year" => [
                "nullable"
            ],
            "subject" => [
                "nullable"
            ],
            "course_duration" => [
                "nullable",
                "numeric"
            ]
        ];
        return \Illuminate\Support\Facades\Validator::make($data, $rules, $messages);

    }

    public function traineeFamilyInfoImportDataValidate(array $data, $row_number): Validator
    {
        $row_number = $row_number + 1;
        $messages = [
            'required' => "The :attribute in row " . $row_number . " is required",
            'string' => 'The :attribute in row ' . $row_number . ' must be text format',
            'numeric' => 'The :attribute in row ' . $row_number . ' must be numeric format',
            'unique' => "The :attribute in row " . $row_number . " is already taken",
            "in" => "The :attribute in row " . $row_number . " is not within :fields",
            "mobile.regex" => "The :attribute in row " . $row_number . " is not valid format as like 1XXXXXXXXXXX",
            "member_mobile.regex" => "The :attribute in row " . $row_number . " is not valid format as like 1XXXXXXXXXXX"
        ];
        $rules = [
            "member_name" => [
                "nullable",
                "string"
            ],
            "mobile" => [
                "required",
                BaseModel::MOBILE_REGEX
            ],
            "educational_qualification" => [
                "nullable"
            ],
            "relation_with_trainee" => [
                "required",
                "string"
            ],
            "is_guardian" => [
                "nullable",
                "numeric",
                function ($attr, $value, $fails) use ($data, $row_number) {
                    if ($value == TraineeFamilyMemberInfo::GUARDIAN_OTHER && !$data['is_guardian_data_exist']) {
                        $fails("Guardian information is required for row " . $row_number);
                    }
                }
            ],
            "personal_monthly_income" => [
                "nullable",
                "numeric"
            ],
            "gender" => [
                "nullable",
                "numeric",
                Rule::in([Trainee::GENDER_MALE, Trainee::GENDER_FEMALE, Trainee::GENDER_OTHERS])
            ],
            "marital_status" => [
                "nullable",
                "numeric",
                Rule::in([Trainee::IS_Marit_YES, Trainee::IS_Marit_NO])
            ],
            "main_occupation" => [
                "nullable",
                "string"
            ],
            "other_occupations" => [
                "nullable",
                "string"
            ],
            "physical_disabilities" => [
                "nullable",
                //"string"
                Rule::in([0, 1, 2, 3]),
            ],
            "disable_status" => [
                "nullable",
                "numeric",
                Rule::in([
                    Trainee::IS_DISABLE_YES,
                    Trainee::IS_DISABLE_NO
                ])
            ],
            "freedom_fighter_status" => [
                "nullable",
                "numeric",
                Rule::in([Trainee::IS_FREEDOM_NO, Trainee::IS_FREEDOM_YES])
            ],
            "nid" => [
                "nullable"
            ],
            "birth_certificate_no" => [
                "nullable"
            ],
            "passport_number" => [
                "nullable"
            ],
            "religion" => [
                "nullable",
                "numeric"
            ],
            "nationality" => [
                "nullable",
                "string"
            ],
            "date_of_birth" => [
                "nullable",
                'date_format:Y-m-d'
            ],
        ];
        return \Illuminate\Support\Facades\Validator::make($data, $rules, $messages);

    }

    public function getListForAcceptListDatatable($request): JsonResponse
    {
        /** @var User $authUser */
        $authUser = AuthHelper::getAuthUser();

        /** @var TraineeCourseEnroll $enrolledCourse */
        $enrolledCourse = TraineeCourseEnroll::select([
            'trainee_id',
            'trainees.id as id',
            'trainees.name',
            'trainees.mobile',
            DB::raw('DATE_FORMAT(trainee_course_enrolls.created_at,"%d %b, %Y %h:%i %p") AS application_date'),
            'trainees.updated_at',
            'courses.id as courses.id',
            'institutes.title as institute_name',
            'branches.title as branches.title',
            'training_centers.title as training_center_title',
            'courses.title as courses.title',
            'trainee_course_enrolls.id as trainee_course_enroll_id',
            'trainee_course_enrolls.enroll_status',
            'trainee_course_enrolls.payment_status',
            'trainee_batches.id as trainee_batch_id',
            'batches.title as batch_title',
            'trainee_batches.trainee_course_enroll_id as trainee_batch_trainee_course_enroll_id',
        ]);
        $enrolledCourse->join('trainees', 'trainees.id', '=', 'trainee_course_enrolls.trainee_id');
        $enrolledCourse->join('courses', 'courses.id', '=', 'trainee_course_enrolls.course_id');
        $enrolledCourse->join('institutes', 'institutes.id', '=', 'courses.institute_id');
        $enrolledCourse->join('batches', 'batches.id', '=', 'trainee_course_enrolls.batch_id');
        $enrolledCourse->leftJoin('branches', 'branches.id', '=', 'batches.branch_id');
        $enrolledCourse->leftJoin('training_centers', 'training_centers.id', '=', 'batches.training_center_id');
        $enrolledCourse->leftJoin('trainee_batches', 'trainee_batches.trainee_course_enroll_id', '=', 'trainee_course_enrolls.id');
        $enrolledCourse->where('trainee_course_enrolls.enroll_status', '=', TraineeCourseEnroll::ENROLL_STATUS_ACCEPT);

        if ($authUser->isUserBelongsToInstitute()) {
            $enrolledCourse->where('institutes.id', $authUser->institute_id);
        }

        $instituteId = $request->input('institute_id');
        $branchId = $request->input('branch_id');
        $trainingCenterId = $request->input('training_center_id');
        $courseId = $request->input('course_id');
        $applicationDate = $request->input('application_date');


        if ($instituteId) {
            $enrolledCourse->where('courses.institute_id', $instituteId);
        }
        if ($branchId) {
            $enrolledCourse->where('trainee_course_enrolls.branch_id', $branchId);
        }
        if ($trainingCenterId) {
            $enrolledCourse->where('trainee_course_enrolls.training_center_id', $trainingCenterId);
        }
        if ($courseId) {
            $enrolledCourse->where('courses.course_id', $courseId);
        }

        if ($applicationDate) {
            $enrolledCourse->whereDate('trainees.created_at', Carbon::parse($applicationDate)->format('Y-m-d'));
        }

        return DataTables::eloquent($enrolledCourse)
            ->addColumn('action', DatatableHelper::getActionButtonBlock(static function (TraineeCourseEnroll $traineeCourseEnroll) {
                $str = '';
                $str .= '<a href="' . route('frontend.trainee-registrations.show', $traineeCourseEnroll->trainee_id) . '" class="btn btn-outline-info btn-sm"> <i class="fas fa-eye"></i> ' . __('generic.read_button_label') . ' </a>';
                if ($traineeCourseEnroll->payment_status == TraineeCourseEnroll::PAYMENT_STATUS_PAID) {
                    $str .= '<a href="#" data-action="' . route('admin.trainee.add-single-trainee-to-batch', $traineeCourseEnroll->trainee_id) . '"' . ' class="btn btn-outline-success btn-sm accept-to-batch"><i class="fas fa-plus-circle"></i> ' . __('Add to Batch') . ' </a>';
                }

                return $str;
            }))
            ->editColumn('registration_date', function (TraineeCourseEnroll $traineeCourseEnroll) {
                return date('d M Y', strtotime($traineeCourseEnroll->created_at));
            })
            ->addColumn('payment_status', DatatableHelper::getActionButtonBlock(static function (TraineeCourseEnroll $traineeCourseEnroll) {
                $str = '';
                $str .= '<span style="width:70px" ' . '" class="badge badge-' . ($traineeCourseEnroll->payment_status ? "success payment-paid" : "danger payment-unpaid") . '">' . ($traineeCourseEnroll->payment_status ? "Paid" : "Unpaid") . ' </span>';
                return $str;
            }))
            ->addColumn('paid_or_unpaid', static function (TraineeCourseEnroll $traineeCourseEnroll) {
                return $traineeCourseEnroll->payment_status;
            })
            ->addColumn('enroll_status_check', static function (TraineeCourseEnroll $traineeCourseEnroll) {
                return $traineeCourseEnroll->enroll_status;
            })
            ->rawColumns(['action', 'enroll_status', 'payment_status', 'paid_or_unpaid', 'enroll_status_check'])
            ->toJson();
    }
}
