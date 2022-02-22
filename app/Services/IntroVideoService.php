<?php


namespace App\Services;

use App\Helpers\Classes\AuthHelper;
use App\Models\IntroVideo;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Yajra\DataTables\Facades\DataTables;

class IntroVideoService
{
    protected function getYoutubeVideoKey($url): string
    {
        if (strlen($url) > 11) {
            if (preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $url, $match)) {
                return $match[1];
            } else {
                return '';
            }
        }

        return $url;
    }

    public function createIntroVideo(array $data): IntroVideo
    {
        $data['youtube_video_id'] = $this->getYoutubeVideoKey($data['youtube_video_url']);

        return IntroVideo::create($data);
    }

    public function updateIntroVideo(array $data, IntroVideo $introVideo): bool
    {
        $data['youtube_video_id'] = $this->getYoutubeVideoKey($data['youtube_video_url']);

        return $introVideo->update($data);
    }

    public function validator($request, $id = null): Validator
    {
        $rules = [
            'youtube_video_url' => [
                'required',
                'string',
                'max: 191',
            ],

            'institute_id' => [
                'nullable',
                'int',
                'exists:institutes,id',
                'unique:intro_videos,institute_id,' . $id
            ],
        ];

        $customMsg = [
            'institute_id.unique' => 'The Intro video has already been added for this institute.',
        ];

        return \Illuminate\Support\Facades\Validator::make($request->all(), $rules, $customMsg);
    }

    public function getListDataForDatatable(\Illuminate\Http\Request $request): JsonResponse
    {
        $authUser = AuthHelper::getAuthUser();
        /** @var Builder|IntroVideo $introVideos */

        $introVideos = IntroVideo::acl()->select([
            'intro_videos.id as id',
            'intro_videos.youtube_video_id',
            'intro_videos.youtube_video_url',
            'intro_videos.row_status',
            'intro_videos.created_at',
            'intro_videos.updated_at',
            'institutes.title as institute_title',
        ]);

        $introVideos->leftJoin('institutes', 'intro_videos.institute_id', 'institutes.id');

        return DataTables::eloquent($introVideos)
            ->addColumn('action', static function (IntroVideo $introVideos) use ($authUser) {
                $str = '';
                if ($authUser->can('view', $introVideos)) {
                    $str .= '<a href="' . route('admin.intro-videos.show', $introVideos->id) . '" class="btn btn-outline-info btn-sm"> <i class="fas fa-eye"></i> ' . __('generic.read_button_label') . ' </a>';
                }

                if ($authUser->can('update', $introVideos)) {
                    $str .= '<a href="' . route('admin.intro-videos.edit', $introVideos->id) . '" class="btn btn-outline-warning btn-sm"> <i class="fas fa-edit"></i> ' . __('generic.edit_button_label') . ' </a>';
                }
                if ($authUser->can('delete', $introVideos)) {
                    $str .= '<a href="#" data-action="' . route('admin.intro-videos.destroy', $introVideos->id) . '" class="btn btn-outline-danger btn-sm delete"> <i class="fas fa-trash"></i> ' . __('generic.delete_button_label') . '</a>';
                }

                return $str;
            })
            ->editColumn('row_status', function (IntroVideo $introVideos) {
                return $introVideos->getCurrentRowStatus(true);
            })
            ->rawColumns(['action', 'row_status'])
            ->toJson();
    }


}
