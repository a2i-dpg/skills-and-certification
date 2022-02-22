<?php

namespace App\Services;

use App\Helpers\Classes\AuthHelper;
use App\Helpers\Classes\DatatableHelper;
use App\Models\TraineeFeedback;
use App\Models\TrainerFeedback;
use App\Models\VisitorFeedback;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class VisitorFeedbackService
{
    public function createVisitorFeedback(array $data): VisitorFeedback
    {
        return VisitorFeedback::create($data);
    }

    public function deleteVisitorFeedback(VisitorFeedback $visitorFeedback): void
    {
        $visitorFeedback->delete();
    }

    public function validator(Request $request): \Illuminate\Contracts\Validation\Validator
    {
        $rules = [
            'institute_id' => [
                'required',
                'exists:institutes,id',
            ],
            'form_type' => [
                'required',
                \Illuminate\Validation\Rule::in([VisitorFeedback::FORM_TYPE_FEEDBACK, VisitorFeedback::FORM_TYPE_CONTACT]),
            ],
            'name' => [
                'required',
                'string',
                'max:191',
            ],
            'mobile' => [
                'required',
                'string',
                'regex:/(^((?:\+88|88)?(01[3-9]\d{8}))$)|(^((\x{09EE}\x{09EE})|(\+\x{09EE}\x{09EE}))?[\x{09E6}-\x{09EF}]{11})$/u',
                'min:11',
                'max:17'
            ],
            'email' => [
                'required',
                'email',
            ],
            'address' => [
                'nullable',
                'string',
                'max:2000'
            ],
            'comment' => [
                'required',
                'string',
                'max:5000'
            ],
        ];

        $messages = [
            "mobile.regex" => "invalid mobile number",
        ];

        return \Illuminate\Support\Facades\Validator::make($request->all(), $rules, $messages);
    }

    public function getVisitorFeedbackLists(Request $request): JsonResponse
    {
        $authUser = AuthHelper::getAuthUser();
        /** @var Builder|VisitorFeedback $visitorFeedbacks */
        $visitorFeedback = VisitorFeedback::acl()->select(
            [
                'visitor_feedback.id as id',
                'institutes.title as institute_title',
                'visitor_feedback.name',
                'visitor_feedback.mobile',
                'visitor_feedback.email',
                'visitor_feedback.form_type',
                'visitor_feedback.read_at',
                'visitor_feedback.created_at',
                'visitor_feedback.row_status',
            ]
        );
        $visitorFeedback->join('institutes', 'visitor_feedback.institute_id', '=', 'institutes.id');
        $visitorFeedback->orderBy('id', 'DESC');

        $formType = $request->input('form_type');
        if (!empty($formType)) {
            $visitorFeedback->where('visitor_feedback.form_type', '=', $formType);
        }
        return DataTables::eloquent($visitorFeedback)
            ->addColumn('action', DatatableHelper::getActionButtonBlock(static function (VisitorFeedback $visitorFeedback) use ($authUser) {
                $str = '';
                if ($authUser->can('view', $visitorFeedback)) {
                    $str .= '<a href="' . route('admin.visitor-feedback.show', $visitorFeedback->id) . '" class="btn btn-outline-info btn-sm"> <i class="fas fa-eye"></i>  ' . __('generic.read_button_label') . '</a>';
                }
                if ($authUser->can('delete', $visitorFeedback)) {
                    $str .= '<a href="#" data-action="' . route('admin.visitor-feedback.destroy', $visitorFeedback->id) . '" class="btn btn-outline-danger btn-sm delete"> <i class="fas fa-trash"></i> ' . __('generic.delete_button_label') . '</a>';
                }
                return $str;
            }))
            ->editColumn('read_at', function (VisitorFeedback $visitorFeedback) {
                $str = '';
                $str .= '<a href="#" data-action="' . route('admin.branches.destroy', $visitorFeedback->id) . '" class="badge badge-' . ($visitorFeedback->read_at ? 'success' : 'danger') . '">' . ($visitorFeedback->read_at ? 'Read' : 'Unread') . '</a>';
                return $str;
            })
            ->editColumn('form_type', function (VisitorFeedback $visitorFeedback) {
                return $visitorFeedback->form_type == VisitorFeedback::FORM_TYPE_FEEDBACK ? 'Feedback' : 'Contact';
            })
            ->editColumn('created_at', function (VisitorFeedback $visitorFeedback) {
                return Date('d M, Y h:i:s', strtotime($visitorFeedback['created_at']));
            })
            ->rawColumns(['action', 'read_at', 'form_type', 'created_at'])
            ->toJson();
    }


    public function getTraineeFeedbackDatatable(Request $request): JsonResponse
    {
        /** @var $authUser $authUser */
        $authUser = AuthHelper::getAuthUser();

        $traineeFeedback = TraineeFeedback::select([
            'trainee_feedbacks.id',
            'batches.title as batch_title',
            'trainees.name as trainee_name',
            'users.name as trainer_name',
            'trainee_feedbacks.feedback',
            'institutes.title as institute_title',
            'courses.title as course_title',
            'training_centers.title as training_center_title'
        ]);

        if ($authUser->isUserBelongsToInstitute()) {
            $traineeFeedback->acl();
        } else {
            $traineeFeedback->join('batches', 'trainee_feedbacks.batch_id', '=', 'batches.id');
        }

        $traineeFeedback->join('institutes', 'batches.institute_id', '=', 'institutes.id');
        $traineeFeedback->join('courses', 'batches.course_id', '=', 'courses.id');
        $traineeFeedback->leftJoin('branches', 'batches.branch_id', '=', 'branches.id');
        $traineeFeedback->join('training_centers', 'batches.training_center_id', '=', 'training_centers.id');
        $traineeFeedback->join('users', 'trainee_feedbacks.user_id', '=', 'users.id');
        $traineeFeedback->join('trainees', 'trainee_feedbacks.trainee_id', '=', 'trainees.id');

        return DataTables::eloquent($traineeFeedback)->toJson();
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function getTrainerFeedbackDatatable(Request $request): JsonResponse
    {
        /** @var $authUser $authUser */
        $authUser = AuthHelper::getAuthUser();

        $trainerFeedback = TrainerFeedback::select([
            'trainer_feedbacks.id',
            'batches.title as batch_title',
            'trainees.name as trainee_name',
            'users.name as trainer_name',
            'trainer_feedbacks.feedback',
            'institutes.title as institute_title',
            'courses.title as course_title',
            'training_centers.title as training_center_title'
        ]);

        if ($authUser->isUserBelongsToInstitute()) {
            $trainerFeedback->acl();
        } else {
            $trainerFeedback->join('batches', 'trainer_feedbacks.batch_id', '=', 'batches.id');
        }

        $trainerFeedback->join('institutes', 'batches.institute_id', '=', 'institutes.id');
        $trainerFeedback->join('courses', 'batches.course_id', '=', 'courses.id');
        $trainerFeedback->leftJoin('branches', 'batches.branch_id', '=', 'branches.id');
        $trainerFeedback->join('training_centers', 'batches.training_center_id', '=', 'training_centers.id');
        $trainerFeedback->join('users', 'trainer_feedbacks.user_id', '=', 'users.id');
        $trainerFeedback->join('trainees', 'trainer_feedbacks.trainee_id', '=', 'trainees.id');

        return DataTables::eloquent($trainerFeedback)->toJson();
    }
}
