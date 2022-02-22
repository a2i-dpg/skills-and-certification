<?php


namespace App\Services;


use App\Helpers\Classes\AuthHelper;
use App\Helpers\Classes\FileHandler;
use App\Models\Video;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Arr;
use Illuminate\Validation\Rule;
use Yajra\DataTables\Facades\DataTables;

class VideoService
{
    protected function getYoutubeVideoKey($url): string
    {
        if(strlen($url) > 11)
        {
            if (preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $url, $match))
            {
                return $match[1];
            }
            else {
                return '';
            }
        }

        return $url;
    }

    public function createVideo(array $data)
    {
        if (!empty($data['uploaded_video_path'])) {
            $filename = FileHandler::storePhoto($data['uploaded_video_path'], Video::VIDEO_FOLDER_NAME);
            $data['uploaded_video_path'] = Video::VIDEO_FOLDER_NAME .'/'. $filename;
        } else {
            $data['youtube_video_id'] = $this->getYoutubeVideoKey($data['youtube_video_url']);
        }

        $video = Video::create($data);
        $tag_data = Arr::only($data, ['institute_id']);
        $tag_data['tag'] = $data['title'];
        return $video->tags()->create($tag_data);
    }

    public function updateVideo(array $data, Video $video)
    {
        if (!empty($data['uploaded_video_path'])) {
            $filename = FileHandler::storePhoto($data['uploaded_video_path'], Video::VIDEO_FOLDER_NAME);
            $data['uploaded_video_path'] = Video::VIDEO_FOLDER_NAME .'/'. $filename;
        }  else {
            $data['youtube_video_id'] = $this->getYoutubeVideoKey($data['youtube_video_url']);
        }

        $tag_data = Arr::only($data, ['institute_id']);
        $tag_data['tag'] = $data['title'];
        $video->tags()->update($tag_data);
        return $video->update($data);
    }

    public function validator($request, $id = null): Validator
    {
        $rules = [
            'title' => [
                'required',
                'string',
                'max:191',
            ],

            'description' => [
                'nullable',
                'string',
                'max:5000',
            ],
            'video_type' => [
                'required',
                'int',
                Rule::in(array_keys(Video::getVideoTypesArray()))
            ],
            'youtube_video_id' => [
                'nullable',
                'string',
                'max: 20',
                'required_if: video_type,' .Video::VIDEO_TYPE_YOUTUBE_VIDEO,
            ],
            'youtube_video_url' => [
                'nullable',
                'string',
                'max: 191',
            ],
            'uploaded_video_path' => [
                'nullable',
                'required_if:video_type,'.Video::VIDEO_TYPE_UPLOADED_VIDEO,
                'mimetypes:video/avi,video/mpeg,video/quicktime,video/mp4',
                'max:2048',
            ],

            'institute_id' => [
                'required',
                'int',
                'exists:institutes,id',
            ],
            'video_category_id' => [
                'nullable',
                'int',
                'exists:video_categories,id',
            ],
            'row_status' =>[
                'nullable',
                'int',
                'exists:row_status,code',
            ],
        ];

        return \Illuminate\Support\Facades\Validator::make($request->all(), $rules);
    }

    public function getListDataForDatatable(\Illuminate\Http\Request $request): JsonResponse
    {
        $authUser = AuthHelper::getAuthUser();
        /** @var Builder|Video $videos */

        $videos = Video::acl()->select([
            'videos.id as id',
            'videos.title',
            'videos.description',
            'videos.uploaded_video_path',
            'videos.video_type',
            'videos.youtube_video_id',
            'videos.youtube_video_url',
            'videos.row_status',
            'videos.created_at',
            'videos.updated_at',
            'institutes.title as institute_title',
            'video_categories.title as video_category_title',
        ]);

        $videos->join('institutes', 'videos.institute_id', 'institutes.id');
        $videos->leftJoin('video_categories', 'videos.video_category_id', 'video_categories.id');

        return DataTables::eloquent($videos)
            ->addColumn('action', static function (Video $video) use ($authUser) {
                $str = '';
                if ($authUser->can('view', $video)) {
                    $str .= '<a href="' . route('admin.videos.show', $video->id) . '" class="btn btn-outline-info btn-sm"> <i class="fas fa-eye"></i> ' . __('generic.read_button_label') . ' </a>';
                }

                if ($authUser->can('update', $video)) {
                    $str .= '<a href="' . route('admin.videos.edit', $video->id) . '" class="btn btn-outline-warning btn-sm"> <i class="fas fa-edit"></i> ' . __('generic.edit_button_label') . ' </a>';
                }
                if ($authUser->can('delete', $video)) {
                    $str .= '<a href="#" data-action="' . route('admin.videos.destroy', $video->id) . '" class="btn btn-outline-danger btn-sm delete"> <i class="fas fa-trash"></i> ' . __('generic.delete_button_label') . '</a>';
                }

                return $str;
            })
            ->editColumn('row_status', function (Video $video) {
                return $video->getCurrentRowStatus(true);
            })
            ->rawColumns(['action', 'row_status'])
            ->toJson();
    }


}
