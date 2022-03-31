<?php


namespace App\Services;


use App\Helpers\Classes\AuthHelper;
use App\Helpers\Classes\FileHandler;
use App\Models\IntroVideo;
use App\Models\QuestionAnswer;
use App\Models\Video;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Arr;
use Illuminate\Validation\Rule;
use Yajra\DataTables\Facades\DataTables;

class QuestionAnswerService
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

    public function createQuestionAnswer(array $data): QuestionAnswer
    {
        return QuestionAnswer::create($data);
    }

    public function updateIntroVideo(array $data, QuestionAnswer $questionAnswer): bool
    {
        return $questionAnswer->update($data);
    }

    public function validator($request, $id = null): Validator
    {
        $rules = [
            'question' => [
                'required',
                'string',
            ],
            'answer' => [
                'required',
                'string',
            ],
            'institute_id' => [
                //'required',
                'int',
                'exists:institutes,id',
            ],
        ];

        return \Illuminate\Support\Facades\Validator::make($request->all(), $rules);
    }

    public function getListDataForDatatable(\Illuminate\Http\Request $request): JsonResponse
    {
        $authUser = AuthHelper::getAuthUser();
        /** @var Builder|QuestionAnswer $questionAnswers */

        $questionAnswers = QuestionAnswer::acl()->select([
            'question_answers.id as id',
            'question_answers.question',
            'question_answers.answer',
            'question_answers.row_status',
            'question_answers.created_at',
            'question_answers.updated_at',
            'institutes.title as institute_title',
        ]);

        $questionAnswers->leftjoin('institutes', 'question_answers.institute_id', 'institutes.id');
        $questionAnswers->orderBy('id','ASC');

        return DataTables::eloquent($questionAnswers)
            ->addColumn('action', static function (QuestionAnswer $questionAnswer) use ($authUser) {
                $str = '';
                if ($authUser->can('view', $questionAnswer)) {
                    $str .= '<a href="' . route('admin.question-answers.show', $questionAnswer->id) . '" class="btn btn-outline-info btn-sm"> <i class="fas fa-eye"></i> ' . __('generic.read_button_label') . ' </a>';
                }

                if ($authUser->can('update', $questionAnswer)) {
                    $str .= '<a href="' . route('admin.question-answers.edit', $questionAnswer->id) . '" class="btn btn-outline-warning btn-sm"> <i class="fas fa-edit"></i> ' . __('generic.edit_button_label') . ' </a>';
                }
                if ($authUser->can('delete', $questionAnswer)) {
                    $str .= '<a href="#" data-action="' . route('admin.question-answers.destroy', $questionAnswer->id) . '" class="btn btn-outline-danger btn-sm delete"> <i class="fas fa-trash"></i> ' . __('generic.delete_button_label') . '</a>';
                }

                return $str;
            })
            ->addColumn('institute_title', static function (QuestionAnswer $questionAnswer) {
                $str = '';
                if(empty($questionAnswer->institute_title)){
                    $str .= '<p> System Admin </p>';
                }else{
                    $str .= '<p>'. $questionAnswer->institute_title .'</p>';
                }
                return $str;
            })
            ->editColumn('row_status', function (QuestionAnswer $questionAnswer) {
                return $questionAnswer->getCurrentRowStatus(true);
            })

            ->rawColumns(['action','institute_title','row_status'])
            ->toJson();
    }


}
