<?php

namespace App\Services;

use App\Helpers\Classes\AuthHelper;
use App\Helpers\Classes\DatatableHelper;
use App\Helpers\Classes\FileHandler;
use App\Models\StaticPage;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class StaticPageService
{
    public function createStaticPage(array $data): StaticPage
    {
        return StaticPage::create($data);
    }

    public function validator(Request $request, $id = null): Validator
    {
        $rules = [
            'title' => ['required', 'string', 'max:191'],
            'institute_id' => ['bail', 'nullable', 'int', 'exists:institutes,id'],
            'page_id' => [
                'required',
                'string',
                'max:191',
                'regex:/^[a-zA-Z0-9-_]/',
                static function ($attribute, $value, $fail) use ($request, $id) {
                    $result = StaticPage::where('institute_id', $request->input('institute_id'))
                        ->where('page_id', $value);
                    if (!empty($id)) {
                        $result->where('id', '!=', $id);
                    }
                    if ($result->count()) {
                        $fail(':attribute is not Unique');
                    }
                }
            ],
            'page_contents' => [
                'required',
                'string',
                'max:5000'
            ],
        ];

        return \Illuminate\Support\Facades\Validator::make($request->all(), $rules);

    }

    public function getListDataForDatatable(\Illuminate\Http\Request $request): JsonResponse
    {
        $authUser = AuthHelper::getAuthUser();
        /** @var Builder|StaticPage $staticPage */

        $staticPage = StaticPage::acl()->select([
            'static_pages.id as id',
            'static_pages.title',
            'static_pages.institute_id',
            'static_pages.page_id',
            'static_pages.page_contents',
            'institutes.title as institute_title',
            'static_pages.created_by',
            'static_pages.created_at',
            'static_pages.updated_at'
        ]);
        $staticPage->leftJoin('institutes', 'static_pages.institute_id', 'institutes.id');

        return DataTables::eloquent($staticPage)
            ->addColumn('action', DatatableHelper::getActionButtonBlock(static function (StaticPage $staticPage) use ($authUser) {
                $str = '';
                if ($authUser->can('view', $staticPage)) {
                    $str .= '<a href="' . route('admin.static-page.show', $staticPage->id) . '" class="btn btn-outline-info btn-sm"> <i class="fas fa-eye"></i> ' . __('generic.read_button_label') . ' </a>';
                }

                if ($authUser->can('update', $staticPage)) {
                    $str .= '<a href="' . route('admin.static-page.edit', $staticPage->id) . '" class="btn btn-outline-warning btn-sm"> <i class="fas fa-edit"></i> ' . __('generic.edit_button_label') . ' </a>';
                }
                if ($authUser->can('delete', $staticPage)) {
                    $str .= '<a href="#" data-action="' . route('admin.static-page.destroy', $staticPage->id) . '" class="btn btn-outline-danger btn-sm delete"> <i class="fas fa-trash"></i> ' . __('generic.delete_button_label') . '</a>';
                }

                return $str;
            }))
            ->addColumn('institute_title', static function (StaticPage $staticPage) {
                $str = '';
                if(empty($staticPage->institute_title)){
                    $str .= '<p> System Admin </p>';
                }else{
                    $str .= '<p>'. $staticPage->institute_title .'</p>';
                }
                return $str;
            })
            ->rawColumns(['action','institute_title'])
            ->toJson();
    }

    public function updateStaticPage(StaticPage $staticPage, array $postData): bool
    {
        return $staticPage->update($postData);
    }

    public function deleteStaticPage(StaticPage $staticPage): bool
    {
        return $staticPage->delete();
    }

    public function staticPageImage($data): object
    {
        $filename = null;

        if (!empty($data['file'])) {
            $filename = FileHandler::storePhoto($data['file'], 'staticPageImage');
        }
        $data['location'] = '/staticPageImage/' . $filename;

        return $data;
    }
}
