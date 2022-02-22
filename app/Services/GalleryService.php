<?php

namespace App\Services;

use App\Helpers\Classes\AuthHelper;
use App\Helpers\Classes\DatatableHelper;
use App\Helpers\Classes\FileHandler;
use App\Models\Gallery;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Yajra\DataTables\Facades\DataTables;


class GalleryService
{
    public function createGallery(array $data)
    {
        $filename = null;
        if ($data['content_type'] == Gallery::CONTENT_TYPE_VIDEO && $data['is_youtube_video']) {
            preg_match("/^(?:http(?:s)?:\/\/)?(?:www\.)?(?:m\.)?(?:youtu\.be\/|youtube\.com\/(?:(?:watch)?\?(?:.*&)?v(?:i)?=|(?:embed|v|vi|user)\/))([^\?&\"'>]+)/", $data['you_tube_video_id'], $matches);
            $filename = $data['you_tube_video_id'];
            $data['you_tube_video_id'] = $matches[1];
        } elseif (!empty($data['content_path'])) {
            $filename = FileHandler::storePhoto($data['content_path'], 'gallery');
            if ($filename) {
                $filename = Gallery::FILE_DIRECTORY . $filename;
            }
        }
        if (!$filename) {
            $data['content_path'] = '';
        } else {
            $data['content_path'] = $filename;
        }

        return Gallery::create($data);
    }

    /**
     * @param Request $request
     * @return Validator
     */
    public function validator(Request $request): Validator
    {
        $contentType = Gallery::CONTENT_TYPES;
        $rules = [
            'gallery_category_id' => ['required', 'int', 'exists:gallery_categories,id'],
            'content_title' => ['required', 'string', 'max:191'],
            'institute_id' => ['required', 'int', 'exists:institutes,id'],
            'content_type' => ['required', 'int', Rule::in(array_keys($contentType))],
            'is_youtube_video' => ['required_if:' . Gallery::CONTENT_TYPES[$request->content_type] . ',Image'],
            'publish_date' => ['date'],
            'archive_date' => ['date', 'after:publish_date'],
        ];

        if ($request->content_type == Gallery::CONTENT_TYPE_IMAGE) {
            $rules['content_path'] = [
                'required_without:id',
                'mimes:jpg,jpeg,bmp,png',

            ];
            if(empty($request->content_path)){
                unset($rules['content_path']);
            }
        } elseif (Gallery::CONTENT_TYPE_VIDEO == $request->content_type && $request->is_youtube_video ==0) {
            $rules['content_path'] = [
                'required_without:id',
                'mimetypes:video/avi,video/mpeg,video/quicktime,video/mp4',
                'max:2048'
            ];
            if(empty($request->content_path)){
                unset($rules['content_path']);
            }
        }
        elseif (Gallery::CONTENT_TYPE_VIDEO == $request->content_type && $request->is_youtube_video ==1) {
            $rules['you_tube_video_id'] = ['required_if:is_youtube_video,1', 'regex:/^(?:https?:\/\/)?(?:www\.)?(?:youtu\.be\/|youtube\.com\/(?:embed\/|v\/|watch\?v=|watch\?.+&v=))((\w|-){11})(?:\S+)?$/'];
        }

        return \Illuminate\Support\Facades\Validator::make($request->all(), $rules);
    }

    /**
     * @param Gallery $gallery
     * @param array $data
     * @return Gallery
     */
    public function updateGallery(Gallery $gallery, array $data): Gallery
    {

        if ($data['content_type'] == Gallery::CONTENT_TYPE_VIDEO && $data['is_youtube_video']) {
            if (!empty($gallery->content_path) && $gallery->content_path !== '') {
                FileHandler::deleteFile($gallery->content_path);
            }
            preg_match("/^(?:http(?:s)?:\/\/)?(?:www\.)?(?:m\.)?(?:youtu\.be\/|youtube\.com\/(?:(?:watch)?\?(?:.*&)?v(?:i)?=|(?:embed|v|vi|user)\/))([^\?&\"'>]+)/", $data['you_tube_video_id'], $matches);
            $data['content_path'] = $data['you_tube_video_id'];
            $data['you_tube_video_id'] = $matches[1];
        } elseif (!empty($data['content_path'])) {
            if (!empty($gallery->content_path) && $gallery->content_path !== '') {
                FileHandler::deleteFile($gallery->content_path);
            }
            $filename = FileHandler::storePhoto($data['content_path'], 'gallery');
            if ($filename) {
                $data['content_path'] = Gallery::FILE_DIRECTORY . $filename;
            }
        }
        $gallery->fill($data);
        $gallery->save();
        return $gallery;
    }


    /**
     * @param Gallery $gallery
     * @return bool
     */
    public function deleteGallery(Gallery $gallery): bool
    {
        return $gallery->delete();
    }


    public function getListDataForDatatable(Request $request): JsonResponse
    {
        $authUser = AuthHelper::getAuthUser();

        /** @var Builder|Gallery $galleries */
        $galleries = Gallery::acl()->select([
            'galleries.id as id',
            'galleries.content_title',
            'galleries.content_type',
            'institutes.title as institute_title',
            'gallery_categories.title as gallery_category_title',
        ]);
        $galleries->join('gallery_categories', 'galleries.gallery_category_id', 'gallery_categories.id');
        $galleries->join('institutes', 'galleries.institute_id', 'institutes.id');

        return DataTables::eloquent($galleries)
            ->addColumn('action', DatatableHelper::getActionButtonBlock(static function (Gallery $gallery) use ($authUser) {
                $str = '';
                if ($authUser->can('view', $gallery)) {
                    $str .= '<a href="' . route('admin.galleries.show', $gallery->id) . '" class="btn btn-outline-info btn-sm"> <i class="fas fa-eye"></i> ' . __('generic.read_button_label') . ' </a>';
                }
                if ($authUser->can('update', $gallery)) {

                    $str .= '<a href="' . route('admin.galleries.edit', $gallery->id) . '" class="btn btn-outline-warning btn-sm"> <i class="fas fa-edit"></i> ' . __('generic.edit_button_label') . ' </a>';
                }
                if ($authUser->can('delete', $gallery)) {
                    $str .= '<a href="#" data-action="' . route('admin.galleries.destroy', $gallery->id) . '" class="btn btn-outline-danger btn-sm delete"> <i class="fas fa-trash"></i> ' . __('generic.delete_button_label') . '</a>';
                }
                return $str;
            }))
            ->editColumn('content_type', static function (Gallery $gallery) {
                return Gallery::CONTENT_TYPES[$gallery->content_type];
            })
            ->rawColumns(['action'])
            ->toJson();
    }
}
