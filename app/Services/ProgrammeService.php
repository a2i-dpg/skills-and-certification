<?php

namespace App\Services;

use App\Helpers\Classes\AuthHelper;
use App\Helpers\Classes\DatatableHelper;
use App\Helpers\Classes\FileHandler;
use App\Models\Programme;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Validation\Rule;

class ProgrammeService
{

    public function createProgramme(array $data): Programme
    {
        if (!empty($data['logo'])) {
            $filename = FileHandler::storePhoto($data['logo'], 'programme');
            $data['logo'] = 'programme/' . $filename;
        } else {
            $data['logo'] = Programme::DEFAULT_LOGO;
        }
        return Programme::create($data);
    }

    public function updateProgramme(Programme $programme, array $data)
    {
        if ($programme->logo && !$programme->logoIsDefault() && !empty($data['logo'])) {
            FileHandler::deleteFile($programme->logo);
        }
        if (!empty($data['logo'])) {
            $filename = FileHandler::storePhoto($data['logo'], 'programme');
            $data['logo'] = 'programme/' . $filename;
        }

        $programme->update($data);
    }

    public function deleteProgramme(Programme $programme): void
    {
        $programme->delete();
    }


    public function validator(Request $request, $id = null): Validator
    {
        $rules = [
            'title' => 'required|string|max:191',
            'institute_id' => 'required|int',
            'code' => [
                'required',
                'string',
                'max:191',
                'unique:programmes,code,' . $id,
                Rule::unique('programmes')->ignore($id),
            ],
            'description' => ['nullable', 'string','max:5000'],
            'logo' => [
                'nullable',
                'file',
                'mimes:jpg,bmp,png,jpeg,svg',
                'dimensions:width=80,height=80',
            ],
        ];
        return \Illuminate\Support\Facades\Validator::make($request->all(), $rules);
    }

    public function programmeGetDataTable(Request $request): JsonResponse
    {
        $authUser = AuthHelper::getAuthUser();
        $programmes = Programme::acl()->select(
            [
                'programmes.id as id',
                'programmes.title',
                'institutes.title as institute_title',
                'programmes.code',
                'programmes.logo',
                'programmes.row_status',
                'programmes.created_at',
                'programmes.updated_at',
            ]
        )
            ->join('institutes', 'programmes.institute_id', '=', 'institutes.id');
        return DataTables::eloquent($programmes)
            ->addColumn('action', DatatableHelper::getActionButtonBlock(static function (Programme $programme) use ($authUser) {
                $str = '';
                if ($authUser->can('view', $programme)) {
                    $str .= '<a href="' . route('admin.programmes.show', $programme->id) . '" class="btn btn-outline-info btn-sm"> <i class="fas fa-eye"></i> ' . __('generic.read_button_label') . '</a>';
                }
                if ($authUser->can('update', $programme)) {
                    $str .= '<a href="' . route('admin.programmes.edit', $programme->id) . '" class="btn btn-outline-warning btn-sm"> <i class="fas fa-edit"></i> ' . __('generic.edit_button_label') . '</a>';
                }
                if ($authUser->can('delete', $programme)) {
                    $str .= '<a href="#" data-action="' . route('admin.programmes.destroy', $programme->id) . '" class="btn btn-outline-danger btn-sm delete"> <i class="fas fa-trash"></i> ' . __('generic.delete_button_label') . '</a>';
                }

                return $str;
            }))
            ->editColumn('logo', static function (Programme $programme) {
                return '<img src="' . asset('storage/' . $programme->logo) . '"  style="width: 100px"/>';
            })
            ->rawColumns(['action', 'logo'])
            ->toJson();
    }

}
