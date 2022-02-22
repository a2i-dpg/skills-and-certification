<?php


namespace App\Services;

use App\Helpers\Classes\AuthHelper;
use App\Helpers\Classes\FileHandler;
use App\Models\Slider;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Yajra\DataTables\Facades\DataTables;

class SliderService
{
    public function createSlider(array $data): Slider
    {
        if (!empty($data['slider'])) {
            $filename = FileHandler::storePhoto($data['slider'], Slider::SLIDER_PIC_FOLDER_NAME);
            $data['slider'] = Slider::SLIDER_PIC_FOLDER_NAME . '/' . $filename;
        }
        return Slider::create($data);
    }

    public function validator($request): Validator
    {
        $rules = [
            'title' => [
                'required',
                'string',
                'max:191',
            ],
            'sub_title' => [
                'required',
                'string',
                'max:191',
            ],
            'link' => [
                'nullable',
                'requiredIf:is_button_available,' . Slider::IS_BUTTON_AVAILABLE_YES,
                'string',
                'max:191',
            ],
            'is_button_available' => [
                'nullable',
                'int',
            ],
            'button_text' => [
                'nullable',
                'requiredIf:is_button_available,' . Slider::IS_BUTTON_AVAILABLE_YES,
                'nullable',
                'string',
                'max:191'
            ],

            'institute_id' => [
                'required',
                'int',
                'exists:institutes,id',
            ],
            'row_status' => [
                'nullable',
                'int',
            ],
            'slider' => [
                'nullable',
                'image',
                'max:2048',
                'mimes:jpeg,jpg,png',
                'dimensions:max_width=1920,max_height=1080',
            ]
        ];

        return \Illuminate\Support\Facades\Validator::make($request->all(), $rules);
    }

    /**
     * @throws \Exception
     */
    public function updateSlider(Slider $slider, array $data): Slider
    {

        if (!empty($data['slider'])) {
            if (!empty($slider->slider)) {
                FileHandler::deleteFile($slider->slider);
            }

            $filename = FileHandler::storePhoto($data['slider'], Slider::SLIDER_PIC_FOLDER_NAME);
            $data['slider'] = Slider::SLIDER_PIC_FOLDER_NAME . '/' . $filename;
        }

        $slider->fill($data);
        $slider->save();
        return $slider;
    }

    public function getListDataForDatatable(\Illuminate\Http\Request $request): JsonResponse
    {
        $authUser = AuthHelper::getAuthUser();
        /** @var Builder|Slider $slider */

        $slider = Slider::acl()->select([
            'sliders.id as id',
            'sliders.title',
            'sliders.sub_title',
            'sliders.link',
            'sliders.button_text',
            'sliders.slider as slider',
            'sliders.created_at',
            'sliders.updated_at',
            'sliders.institute_id',
            'institutes.title as institute_title'
        ]);

        $slider->join('institutes', 'sliders.institute_id', '=', 'institutes.id');
        $slider->acl();

        return DataTables::eloquent($slider)
            ->addColumn('action', static function (Slider $slider) use ($authUser) {
                $str = '';
                if ($authUser->can('view', $slider)) {
                    $str .= '<a href="' . route('admin.sliders.show', $slider->id) . '" class="btn btn-outline-info btn-sm"> <i class="fas fa-eye"></i> ' . __('generic.read_button_label') . ' </a>';
                }

                if ($authUser->can('update', $slider)) {
                    $str .= '<a href="' . route('admin.sliders.edit', $slider->id) . '" class="btn btn-outline-warning btn-sm"> <i class="fas fa-edit"></i> ' . __('generic.edit_button_label') . ' </a>';
                }
                if ($authUser->can('delete', $slider)) {
                    $str .= '<a href="#" data-action="' . route('admin.sliders.destroy', $slider->id) . '" class="btn btn-outline-danger btn-sm delete"> <i class="fas fa-trash"></i> ' . __('generic.delete_button_label') . '</a>';
                }

                return $str;
            })
            ->editColumn('slider', function (Slider $slider) {
                return '<img class="img-fluid" src="' . asset('storage/' . $slider->slider) . '" alt="slider Image"  width="100">';
            })
            ->rawColumns(['action', 'slider'])
            ->toJson();
    }
}
