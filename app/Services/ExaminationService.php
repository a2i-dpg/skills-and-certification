<?php

namespace App\Services;

use App\Helpers\Classes\AuthHelper;
use App\Helpers\Classes\DatatableHelper;
use App\Models\BaseModel;
use App\Models\Examination;
use App\Models\User;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class ExaminationService
{
    public function createExamination(array $data): Examination
    {
        return Examination::create($data);
    }

    public function updateExamination(Examination $examination, array $data): Examination
    {
        $examination->fill($data);
        $examination->save();
        return $examination;
    }

    /**
     * @throws \Exception
     */
    public function deleteExamination(Examination $examination): bool
    {
        return $examination->update([
            'row_status' => BaseModel::ROW_STATUS_INACTIVE
        ]);
    }

    public function validator(Request $request): Validator
    {
        $rules = [
            'batch_id' => [
                'required',
                'int',
            ],
            'training_center_id' => [
                'required',
                'int',
            ],
            'examination_type_id' => [
                'required',
                'int',
            ],
            'institute_id' => [
                'required',
                'int',
            ],
            'user_id' => [
                'required',
                'int',
            ],
            'total_mark' => ['required'],
            'pass_mark' => ['required'],
            'exam_details' => ['required'],
            'code' => ['required'],
        ];
        return \Illuminate\Support\Facades\Validator::make($request->all(), $rules);
    }


    public function getExaminationLists(): JsonResponse
    {
        $authUser = AuthHelper::getAuthUser();

        $examinations = Examination::with('Batch', 'trainingCenter', 'ExaminationType')->select(
            [
                'examinations.*'
            ]
        );
        $examinations->where(['row_status' => BaseModel::ROW_STATUS_ACTIVE]);

        if ($authUser->isUserBelongsToInstitute()) {
            $examinations->where('examinations.institute_id', '=', $authUser->institute_id);
        }

        return DataTables::eloquent($examinations)
            ->editColumn('status', function (Examination $examination) use ($authUser) {
                $status = $examination->getCurrentExaminationStatus();
                if ($examination->status === Examination::EXAMINATION_STATUS_NOT_PUBLISH) {
                    return '<p class="badge badge-warning">' . $status . '</p>';
                } elseif ($examination->status == Examination::EXAMINATION_STATUS_PUBLISH) {
                    return '<p class="badge badge-success">' . $status . '</p>';
                } else {
                    return '<p class="badge badge-info">' . $status . '</p>';
                }
            })
            ->addColumn('action', DatatableHelper::getActionButtonBlock(static function (Examination $examination) use ($authUser) {

                $str = self::getExaminationStatusChangeButton($examination, $authUser) ?? '';

                if ($authUser->can('view', $examination)) {
                    $str .= '<a href="' . route('admin.examinations.show', $examination->id) . '" class="btn btn-outline-info btn-sm"> <i class="fas fa-eye"></i> ' . __('generic.read_button_label') . '</a>';
                }
                if ($authUser->can('update', $examination)) {
                    $str .= '<a href="' . route('admin.examinations.edit', $examination->id) . '" class="btn btn-outline-warning btn-sm"> <i class="fas fa-edit"></i> ' . __('generic.edit_button_label') . '</a>';
                }
                if ($authUser->can('delete', $examination)) {
                    $str .= '<a href="#" data-action="' . route('admin.examinations.destroy', $examination->id) . '" class="btn btn-outline-danger btn-sm delete"> <i class="fas fa-trash"></i> ' . __('generic.delete_button_label') . '</a>';
                }
                if ($authUser->can('result', $examination) && ($examination->status == Examination::EXAMINATION_STATUS_COMPLETE)) {
                    $str .= '<a href="' . route('admin.examination-result.batch', $examination->id) . '" class="btn btn-outline-success btn-sm"> <i class="fas fa-award"></i> ' . __('admin.examination_result.result') . '</a>';
                }

                return $str;
            }))
            ->rawColumns(['status', 'action'])
            ->toJson();
    }

    public static function getExaminationStatusChangeButton(Examination $examination, User $authUser): string
    {
        if ($authUser->can('status', $examination) && $examination->status === Examination::EXAMINATION_STATUS_NOT_PUBLISH) {
            return '<a href="#" data-action="' . $examination->id . '" data-toggle="modal" data-target="#examStatus" class="btn btn-outline-warning btn-sm examination_status"> <i class="fas fa-thermometer-three-quarters"></i>Publish</a>';
        } elseif ($authUser->can('status', $examination) && $examination->status === Examination::EXAMINATION_STATUS_PUBLISH) {
            return '<a href="#" data-action="' . $examination->id . '" data-toggle="modal" data-target="#examStatus" class="btn btn-outline-success btn-sm examination_status"> <i class="fas fa-thermometer-three-quarters"></i>Complete</a>';
        }

        return '';
    }
}
