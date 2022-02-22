<?php

namespace App\Services;

use App\Helpers\Classes\AuthHelper;
use App\Helpers\Classes\DatatableHelper;
use App\Models\Branch;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class BranchService
{
    /**
     * @param array $data
     * @return Branch
     */
    public function createBranch(array $data): Branch
    {
        $data['google_map_src'] = $this->parseGoogleMapSrc($data['google_map_src']);
        return Branch::create($data);
    }

    public function updateBranch(Branch $branch, array $data): Branch
    {
        $data['google_map_src'] = $this->parseGoogleMapSrc($data['google_map_src']);
        $branch->fill($data);
        $branch->save();
        return $branch;
    }

    /**
     * @throws \Exception
     */
    public function deleteBranch(Branch $branch): bool
    {
        return $branch->delete();
    }

    /**
     * @param Request $request
     * @return Validator
     */
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
                'int',
                'exists:institutes,id',
            ],
            'address' => ['nullable', 'string', 'max:191'],
            'google_map_src' => ['nullable', 'string'],
        ];
        return \Illuminate\Support\Facades\Validator::make($request->all(), $rules);
    }

    public function getBranchLists(Request $request): JsonResponse
    {
        $authUser = AuthHelper::getAuthUser();
        /** @var Builder|Branch $branches */
        $branches = Branch::acl()->select(
            [
                'branches.id as id',
                'branches.title',
                'institutes.title as institute_title',
                'branches.row_status',
                'branches.created_at',
                'branches.updated_at',
            ]
        );
        $branches->leftJoin('institutes', 'branches.institute_id', '=', 'institutes.id');

        return DataTables::eloquent($branches)
            ->addColumn('action', DatatableHelper::getActionButtonBlock(static function (Branch $branch) use ($authUser) {
                $str = '';
                if ($authUser->can('view', $branch)) {
                    $str .= '<a href="' . route('admin.branches.show', $branch->id) . '" class="btn btn-outline-info btn-sm"> <i class="fas fa-eye"></i> ' . __('generic.read_button_label') . '</a>';
                }
                if ($authUser->can('update', $branch)) {
                    $str .= '<a href="' . route('admin.branches.edit', $branch->id) . '" class="btn btn-outline-warning btn-sm"> <i class="fas fa-edit"></i> ' . __('generic.edit_button_label') . '</a>';
                }
                if ($authUser->can('delete', $branch)) {
                    $str .= '<a href="#" data-action="' . route('admin.branches.destroy', $branch->id) . '" class="btn btn-outline-danger btn-sm delete"> <i class="fas fa-trash"></i> ' . __('generic.delete_button_label') . '</a>';
                }

                return $str;
            }))
            ->rawColumns(['action'])
            ->toJson();
    }

    /**
     * @param string|null $googleMapSrc
     * @return string
     */
    public function parseGoogleMapSrc(?string $googleMapSrc): ?string
    {
        if (!empty($googleMapSrc) && preg_match('/src="([^"]+)"/', $googleMapSrc, $match)) {
            $googleMapSrc = $match[1];
        }

        return $googleMapSrc;
    }
}
