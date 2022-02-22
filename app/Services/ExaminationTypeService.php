<?php

namespace App\Services;

use App\Helpers\Classes\AuthHelper;
use App\Helpers\Classes\DatatableHelper;
use App\Models\ExaminationType;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class ExaminationTypeService
{
    public function createExaminationType(array $data): ExaminationType
    {
        return ExaminationType::create($data);
    }

    public function updateExaminationType(ExaminationType $examinationType, array $data): ExaminationType
    {
        $examinationType->fill($data);
        $examinationType->save();
        return $examinationType;
    }

    /**
     * @throws \Exception
     */
    public function deleteExaminationType(ExaminationType $examinationType): bool
    {
        return $examinationType->delete();
    }

    public function validator(Request $request): Validator
    {
        $rules = [
            'title' => [
                'required',
                'string',
                'max:191',
            ],
            'institute_id' => [
                'required',
                'int'
            ]
        ];
        return \Illuminate\Support\Facades\Validator::make($request->all(), $rules);
    }

    public function getExaminationTypeLists(Request $request): JsonResponse
    {
        $authUser = AuthHelper::getAuthUser();
        /** @var Builder|ExaminationType $examinationTypes */
        $examinationTypes = ExaminationType::select(
            [
                'examination_types.id as id',
                'examination_types.title as ExaminationType',
                'institutes.title as Institute',
                'examination_types.created_at',
                'examination_types.updated_at',
            ]
        );
        $examinationTypes->join('institutes','examination_types.institute_id','=', 'institutes.id');
        if($authUser->isUserBelongsToInstitute()) {
            $examinationTypes->where('examination_types.institute_id', $authUser->institute_id);
            return DataTables::eloquent($examinationTypes)
                ->addColumn('action', DatatableHelper::getActionButtonBlock(static function (ExaminationType $examinationType) use ($authUser) {
                    $str = '';
                    if ($authUser->can('view', $examinationType)) {
                        $str .= '<a href="' . route('admin.examination-types.show', $examinationType->id) . '" class="btn btn-outline-info btn-sm"> <i class="fas fa-eye"></i> ' . __('generic.read_button_label') . '</a>';
                    }
                    if ($authUser->can('update', $examinationType)) {
                        $str .= '<a href="' . route('admin.examination-types.edit', $examinationType->id) . '" class="btn btn-outline-warning btn-sm"> <i class="fas fa-edit"></i> ' . __('generic.edit_button_label') . '</a>';
                    }
                    if ($authUser->can('delete', $examinationType)) {
                        $str .= '<a href="#" data-action="' . route('admin.examination-types.destroy', $examinationType->id) . '" class="btn btn-outline-danger btn-sm delete"> <i class="fas fa-trash"></i> ' . __('generic.delete_button_label') . '</a>';
                    }

                    return $str;
                }))
                ->rawColumns(['action'])
                ->toJson();
        }
        return DataTables::eloquent($examinationTypes)
            ->addColumn('action', DatatableHelper::getActionButtonBlock(static function (ExaminationType $examinationType) use ($authUser) {
                $str = '';
                if ($authUser->can('view', $examinationType)) {
                    $str .= '<a href="' . route('admin.examination-types.show', $examinationType->id) . '" class="btn btn-outline-info btn-sm"> <i class="fas fa-eye"></i> ' . __('generic.read_button_label') . '</a>';
                }
                if ($authUser->can('update', $examinationType)) {
                    $str .= '<a href="' . route('admin.examination-types.edit', $examinationType->id) . '" class="btn btn-outline-warning btn-sm"> <i class="fas fa-edit"></i> ' . __('generic.edit_button_label') . '</a>';
                }
                if ($authUser->can('delete', $examinationType)) {
                    $str .= '<a href="#" data-action="' . route('admin.examination-types.destroy', $examinationType->id) . '" class="btn btn-outline-danger btn-sm delete"> <i class="fas fa-trash"></i> ' . __('generic.delete_button_label') . '</a>';
                }

                return $str;
            }))
            ->rawColumns(['action'])
            ->toJson();

    }
}
