<?php


namespace App\Services;

use App\Helpers\Classes\AuthHelper;
use App\Helpers\Classes\FileHandler;
use App\Models\Header;
use App\Models\Slider;
use App\Models\User;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Yajra\DataTables\Facades\DataTables;

class HeaderService
{
    public function createHeader(array $data): Header
    {
        return Header::create($data);
    }

    public function validator($request): Validator
    {
        $rules = [
            'title' => [
                'required',
                'string',
                'max:191',
            ],
            'url' => [
                'nullable',
                'string',
                'max:191',
            ],
            'route' => [
                'nullable',
                'string',
                'max:191',
            ],
            'target' => [
                'nullable',
                'string',
                'max:191',
            ],
            'order' => [
                'nullable',
                'int',
            ],
            'institute_id' => [
                'nullable',
                'int',
                'exists:institutes,id',
            ],
            'row_status' => [
                'nullable',
                'int',
            ],
        ];

        return \Illuminate\Support\Facades\Validator::make($request->all(), $rules);
    }

    /**
     * @param Header $header
     * @param array $data
     * @return Header
     */
    public function updateHeader(Header $header, array $data): Header
    {
        $header->fill($data);
        $header->save();
        return $header;
    }

    public function getListDataForDatatable(\Illuminate\Http\Request $request): JsonResponse
    {
        /** @var User $authUser */
        $authUser = AuthHelper::getAuthUser();

        /** @var Header $headers */
        $headers = Header::select([
            'headers.id as id',
            'headers.title',
            'headers.url',
            'headers.target',
            'headers.order',
            'headers.route',
            'headers.created_at',
            'headers.updated_at',
            'headers.institute_id',
            'institutes.title as institute_title',
            'headers.row_status'
        ]);

        if ($authUser->isInstituteLevelUser()) {
            $headers->acl();
        }

        $headers->leftJoin('institutes', 'headers.institute_id', '=', 'institutes.id');

        return DataTables::eloquent($headers)
            ->addColumn('action', static function (Header $header) use ($authUser) {
                $str = '';
                if ($authUser->can('view', $header)) {
                    $str .= '<a href="' . route('admin.headers.show', $header->id) . '" class="btn btn-outline-info btn-sm"> <i class="fas fa-eye"></i> ' . __('generic.read_button_label') . ' </a>';
                }

                if ($authUser->can('update', $header)) {
                    $str .= '<a href="' . route('admin.headers.edit', $header->id) . '" class="btn btn-outline-warning btn-sm"> <i class="fas fa-edit"></i> ' . __('generic.edit_button_label') . ' </a>';
                }
                if ($authUser->can('delete', $header)) {
                    $str .= '<a href="#" data-action="' . route('admin.headers.destroy', $header->id) . '" class="btn btn-outline-danger btn-sm delete"> <i class="fas fa-trash"></i> ' . __('generic.delete_button_label') . '</a>';
                }

                return $str;
            })
            ->rawColumns(['action'])
            ->toJson();
    }
}
