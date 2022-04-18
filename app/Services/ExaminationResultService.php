<?php

namespace App\Services;

use App\Helpers\Classes\AuthHelper;
use App\Helpers\Classes\DatatableHelper;
use App\Models\Examination;
use App\Models\ExaminationResult;
use FontLib\TrueType\Collection;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class ExaminationResultService
{
    public function createExaminationResult(array $data): ExaminationResult
    {
        return ExaminationResult::create($data);
    }

    public function createBatchResult(array $data): RedirectResponse
    {
       foreach ($data as $results) {
           ExaminationResult::insert($results);
       }
        return back() ;
    }

    public function updateExaminationResult(ExaminationResult $examinationResult, array $data): ExaminationResult
    {
        $examinationResult->fill($data);
        $examinationResult->save();
        return $examinationResult;

    }

    public function updateBatchResult(array $data): RedirectResponse
    {
        $results = collect($data);
        foreach ($results['result'] as $result){
            ExaminationResult::where('id',$result['examination_result_id'])
              ->update([
                  'achieved_marks' => $result['achieved_marks'],
                  'feedback' => $result['feedback']
              ]);
        }

        return back();
    }


    /**
     * @throws \Exception
     */
    public function deleteExaminationResult(ExaminationResult $examinationResult): bool
    {
        return $examinationResult->delete();
    }

    public function validator(Request $request): Validator
    {
        $rules = [
            'achieved_marks' => [
                'required'
            ],
            'feedback' => [
                'required',
                'string',
                'max:191',
            ],
            'trainee_id' => [
                'required','int'
            ],
            'examination_id' => [
                'required','int'
            ],
        ];
        return \Illuminate\Support\Facades\Validator::make($request->all(), $rules);
    }

    public function getExaminationResultLists(Request $request): JsonResponse
    {
        $authUser = AuthHelper::getAuthUser();
        /** @var Builder|ExaminationResult $examinationResults */
        $examinationResults = ExaminationResult::with('user','trainee','examination','batch','trainingCenter')->select(
            [
                'examination_results.*'
            ]
        );
        $examinationResults->leftJoin('examinations', 'examinations.institute_id','examination_results.examination_id');
        $examinationResults->where('examinations.institute_id', $authUser->institute_id);
        return DataTables::eloquent($examinationResults)
            ->addColumn('action', DatatableHelper::getActionButtonBlock(static function (ExaminationResult $examinationResult) use ($authUser) {
                $str = '';
                if ($authUser->can('view', $examinationResult)) {
                    $str .= '<a href="' . route('admin.examination-results.show', $examinationResult->id) . '" class="btn btn-outline-info btn-sm"> <i class="fas fa-eye"></i> ' . __('generic.read_button_label') . '</a>';
                }
                if ($authUser->can('update', $examinationResult)) {
                    $str .= '<a href="' . route('admin.examination-results.edit', $examinationResult->id) . '" class="btn btn-outline-warning btn-sm"> <i class="fas fa-edit"></i> ' . __('generic.edit_button_label') . '</a>';
                }
                if ($authUser->can('delete', $examinationResult)) {
                    $str .= '<a href="#" data-action="' . route('admin.examination-results.destroy', $examinationResult->id) . '" class="btn btn-outline-danger btn-sm delete"> <i class="fas fa-trash"></i> ' . __('generic.delete_button_label') . '</a>';
                }

                return $str;
            }))
            ->rawColumns(['action'])
            ->toJson();
    }


    public function resultValidator(Request $request): Validator
    {
        $rules = [
            'result.*.examination_id' => [
                'required','int'
            ],
            'result.*.course_id' => [
                'required','int'
            ],
            'result.*.batch_id' => [
                'required','int'
            ],
            'result.*.trainee_id' => [
                'required','int'
            ],
            'result.*.achieved_marks' => [
                'required'
            ],
            'result.*.feedback' => [
                'required',
                'string',
                'max:191',
            ]
        ];
        return \Illuminate\Support\Facades\Validator::make($request->all(), $rules);
    }

    public function updateResultValidator(Request $request): Validator
    {
        $rules = [
            'result.*.examination_result_id' => [
                'required','int'
            ],
            'result.*.examination_id' => [
                'required','int'
            ],
            'result.*.course_id' => [
                'required','int'
            ],
            'result.*.batch_id' => [
                'required','int'
            ],
            'result.*.trainee_id' => [
                'required','int'
            ],
            'result.*.achieved_marks' => [
                'required'
            ],
            'result.*.feedback' => [
                'required',
                'string',
                'max:191',
            ]
        ];
        return \Illuminate\Support\Facades\Validator::make($request->all(), $rules);
    }

}
