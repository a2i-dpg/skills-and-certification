<?php

namespace App\Services;

use App\Helpers\Classes\AuthHelper;
use App\Helpers\Classes\DatatableHelper;
use App\Helpers\Classes\FileHandler;
use Illuminate\Validation\Rule;
use App\Models\GalleryCategory;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;


class GalleryCategoryService
{
    public function createGalleryCategory(array $data): GalleryCategory
    {
        if (!empty($data['image'])) {
            $filename = FileHandler::storePhoto($data['image'], 'gallery-category');
            $data['image'] = 'gallery-category/' . $filename;
        } else {
            $data['image'] = GalleryCategory::DEFAULT_IMAGE;
        }

        return GalleryCategory::create($data);
    }

    /**
     * @param Request $request
     * @param null $id
     * @return Validator
     */
    public function validator(Request $request, $id = null): Validator
    {
        $rules = [
            'title' => ['required', 'string', 'max:191'],
            'institute_id' => [
                'required',
                'int',
                'exists:institutes,id'
            ],
            'batch_id' => [
                'nullable',
                'int',
                'exists:batches,id'
            ],
            'image' => [
                'nullable',
                'image',
                'mimes:jpg,bmp,png,jpeg,svg',
                'max:2048',
            ],
            'featured' => [
                'nullable',
            ],
            'row_status' => [
                Rule::requiredIf(function () use ($id) {
                    return !empty($id);
                }),
                'int',
                'exists:row_status,code',
            ],
        ];

        $messages = [
            'image.dimensions'=>"Image size must be 1920x1080px",
        ];
        return \Illuminate\Support\Facades\Validator::make($request->all(), $rules,$messages);
    }

    /**
     * @param GalleryCategory $galleryCategory
     * @param array $data
     * @return GalleryCategory
     */
    public function updateGalleryCategory(GalleryCategory $galleryCategory, array $data): GalleryCategory
    {
        if ($galleryCategory->image && $galleryCategory->logoIsDefault() && !empty($data['image'])) {
            FileHandler::deleteFile($galleryCategory->image);
        }

        if (!empty($data['image'])) {
            $filename = FileHandler::storePhoto($data['image'], 'gallery-category');
            $data['image'] = 'gallery-category/' . $filename;

        } else {
            unset($data['image']);
        }

        if (empty($galleryCategory->image) && empty($data['image'])) {
            $data['image'] = GalleryCategory::DEFAULT_IMAGE;
        }
        $galleryCategory->fill($data);
        $galleryCategory->save();
        return $galleryCategory;
    }


    /**
     * @param GalleryCategory $galleryCategory
     * @return bool
     * @throws \Exception
     */
    public function deleteGalleryCategory(GalleryCategory $galleryCategory): bool
    {
        return $galleryCategory->delete();
    }


    public function getListDataForDatatable(Request $request): JsonResponse
    {
        $authUser = AuthHelper::getAuthUser();

        /** @var Builder|GalleryCategory $galleryCategories */
        $galleryCategories = GalleryCategory::acl()->select([
            'gallery_categories.id',
            'gallery_categories.title',
            'gallery_categories.featured',
            'institutes.title as institute_title',
            'batches.title as batch_title'
        ]);

        $galleryCategories->join('institutes', 'gallery_categories.institute_id', 'institutes.id');
        $galleryCategories->leftJoin('batches', 'gallery_categories.batch_id', 'batches.id');

        if ($authUser->isUserBelongsToInstitute()) {
            $galleryCategories->where('gallery_categories.institute_id', $authUser->institute_id);
        }

        return DataTables::eloquent($galleryCategories)
            ->addColumn('action', DatatableHelper::getActionButtonBlock(static function (GalleryCategory $galleryCategory) use ($authUser) {
                $str = '';
                if ($authUser->can('view', $galleryCategory)) {
                    $str .= '<a href="' . route('admin.gallery-categories.show', $galleryCategory->id) . '" class="btn btn-outline-info btn-sm"> <i class="fas fa-eye"></i> ' . __('generic.read_button_label') . ' </a>';
                }
                if ($authUser->can('update', $galleryCategory)) {

                    $str .= '<a href="' . route('admin.gallery-categories.edit', $galleryCategory->id) . '" class="btn btn-outline-warning btn-sm"> <i class="fas fa-edit"></i> ' . __('generic.edit_button_label') . ' </a>';
                }
                if ($authUser->can('delete', $galleryCategory)) {
                    $str .= '<a href="#" data-action="' . route('admin.gallery-categories.destroy', $galleryCategory->id) . '" class="btn btn-outline-danger btn-sm delete"> <i class="fas fa-trash"></i> ' . __('generic.delete_button_label') . '</a>';
                }
                return $str;
            }))
            ->rawColumns(['action', 'featured'])
            ->toJson();
    }
}
