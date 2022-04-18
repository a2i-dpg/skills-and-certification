<?php

namespace App\Services;

use App\Helpers\Classes\AuthHelper;
use App\Helpers\Classes\DatatableHelper;
use App\Models\Batch;
use App\Models\User;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class BatchService
{
    public function createBatch(array $data): Batch
    {
        return Batch::create($data);
    }

    public function updateBatch(Batch $batch, array $data): Batch
    {
        $batch->fill($data);
        $batch->save();
        return $batch;
    }

    public function deleteBatch(Batch $batch): ?bool
    {
        return $batch->delete();
    }

    public function validator(Request $request, $id = null): Validator
    {
        $rules = [
            'title' => 'required|string|max:191',
            'code' => 'required|string|max: 191|unique:batches,code,' . $id,
            'institute_id' => 'required|int|exists:institutes,id',
            'course_id' => 'required|int|exists:courses,id',
            'branch_id' => 'nullable|int|exists:branches,id',
            'training_center_id' => 'required|int|exists:training_centers,id',
            'application_start_date' => [
                'required'
            ],
            'application_end_date' => [
                'required'
            ],
            'batch_start_date' => [
                'required'
            ],
            'batch_end_date' => [
                'required'
            ],
        ];

        return \Illuminate\Support\Facades\Validator::make($request->all(), $rules);
    }

    public function getBatchLists(Request $request): JsonResponse
    {
        /** @var User $authUser */
        $authUser = AuthHelper::getAuthUser();

        /** @var Builder|Batch $batches */
        $batches = Batch::acl()->select(
            [
                'batches.id as id',
                'batches.title',
                'courses.title as courses.title',
                'institutes.title as institute_title',
                'batches.row_status',
                'batches.batch_status',
                'batches.created_at',
                'batches.updated_at',
            ]
        );
        $batches->join('courses', 'batches.course_id', '=', 'courses.id');
        $batches->join('institutes', 'batches.institute_id', '=', 'institutes.id');

        return DataTables::eloquent($batches)
            ->addColumn('action', DatatableHelper::getActionButtonBlock(static function (Batch $batch) use ($authUser) {
                $str = '';
                if ($authUser->can('view', $batch)) {
                    $str .= '<a href="' . route('admin.batches.show', $batch->id) . '" class="btn btn-outline-info btn-sm"> <i class="fas fa-eye"></i> ' . __('generic.read_button_label') . '</a>';
                }
                if ($authUser->can('viewBachTrainee', $batch)) {
                    $str .= '<a href="' . route('admin.batches.trainees', $batch->id) . '" class="btn btn-outline-success btn-sm"> <i class="fas fa-users"></i> ' . __('Trainee List') . '</a>';
                }
                if ($authUser->can('viewBachTrainerMapping', $batch)) {
                    $str .= '<a href="' . route('admin.batches.trainer-mapping', $batch->id) . '" class="btn btn-outline-success btn-sm"> <i class="fas fa-users"></i> ' . __('Trainer Mapping') . '</a>';
                }

                if ($authUser->can('update', $batch)) {
                    $str .= '<a href="' . route('admin.batches.edit', $batch->id) . '" class="btn btn-outline-warning btn-sm"> <i class="fas fa-edit"></i> ' . __('generic.edit_button_label') . '</a>';
                }
                if ($authUser->can('delete', $batch)) {
                    $str .= '<a href="#" data-action="' . route('admin.batches.destroy', $batch->id) . '" class="btn btn-outline-danger btn-sm delete"> <i class="fas fa-trash"></i> ' . __('generic.delete_button_label') . '</a>';
                }
                return $str;
            }))
            ->addColumn('change_batch_status', function (Batch $batch) {
                $str = '';
                $str .= '<div class="btn-group" role="group" aria-label="Basic example">';

                if ($batch->batch_status == Batch::BATCH_STATUS_COMPLETE) {
                    $str .= '<span class="badge badge-success">Completed</span>';
                } elseif ($batch->batch_status == Batch::BATCH_STATUS_OPEN_FOR_REGISTRATION) {
                    $str .= '<a type="button" href="' . route('admin.batch-on-going', $batch->id) . '" class="btn btn-outline-secondary btn-sm ' . ($batch->batch_status == Batch::BATCH_STATUS_ON_GOING ? 'active' : '') . '"> <i class="fas fa-running"></i> ' . __($batch->batch_status == null ? 'Start the Batch' : 'On Going') . '</a>';
                    $str .= '<a type="button" href="' . route('admin.batch-complete', $batch->id) . '" class="btn btn-outline-secondary btn-sm ' . ($batch->batch_status == Batch::BATCH_STATUS_COMPLETE ? ' active ' : '') . '"> <i class="fas fa-check-square"></i> ' . __($batch->batch_status != Batch::BATCH_STATUS_COMPLETE ? 'Complete' : 'Completed') . '</a>';
                } elseif ($batch->batch_status == Batch::BATCH_STATUS_OPEN_FOR_REGISTRATION || $batch->batch_status == Batch::BATCH_STATUS_ON_GOING) {
                    $str .= '<a type="button" href="' . route('admin.batch-complete', $batch->id) . '" class="btn btn-outline-secondary btn-sm ' . ($batch->batch_status == Batch::BATCH_STATUS_COMPLETE ? ' active ' : '') . '"> <i class="fas fa-check-square"></i> ' . __($batch->batch_status != Batch::BATCH_STATUS_COMPLETE ? 'Complete' : 'Completed') . '</a>';
                }

                $str .= '</div>';
                return $str;
            })
            ->addColumn('batch_status', function (Batch $batch) {
                $str = '';
                switch ($batch->batch_status) {
                    case Batch::BATCH_STATUS_OPEN_FOR_REGISTRATION:
                        $str .= '<span class="badge badge-info">Open</span>';
                        break;
                    case Batch::BATCH_STATUS_ON_GOING:
                        $str .= '<span class="badge badge-warning">Ongoing</span>';
                        break;
                    case Batch::BATCH_STATUS_COMPLETE:
                        $str .= '<span class="badge badge-success">Completed</span>';
                        break;
                }

                return $str;
            })
            ->rawColumns(['action', 'change_batch_status', 'batch_status'])
            ->toJson();
    }

    public function changeBatchStatus(Batch $branch, array $data): Batch
    {
        $branch->fill($data);
        $branch->save();
        return $branch;
    }

}
