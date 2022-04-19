<?php

namespace App\Services;

use App\Helpers\Classes\AuthHelper;
use App\Helpers\Classes\DatatableHelper;
use App\Helpers\Classes\FileHandler;
use Illuminate\Validation\Rule;
use App\Models\CertificateTemplate;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;


class CertificateTemplateService
{
    public function createCertificateTemplate(array $data): CertificateTemplate
    {
        if (!empty($data['image'])) {
            $filename = FileHandler::storePhoto($data['image'], 'certificate-template');
            $data['image'] = 'certificate-template/' . $filename;
        } else {
            $data['image'] = CertificateTemplate::DEFAULT_IMAGE;
        }

        return CertificateTemplate::create($data);
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
            'image' => [
                'nullable',
                'image',
                'mimes:jpg,bmp,png,jpeg,svg',
                'max:2048',
            ],
            // 'row_status' => [
            //     Rule::requiredIf(function () use ($id) {
            //         return !empty($id);
            //     }),
            //     'int',
            //     'exists:row_status,code',
            // ],
        ];

        // $messages = [
        //     'image.dimensions'=>"Image size must be 1920x1080px",
        // ];
        return \Illuminate\Support\Facades\Validator::make($request->all(), $rules);
    }

    /**
     * @param CertificateTemplate $certificateTemplate
     * @param array $data
     * @return CertificateTemplate
     */
    public function updateCertificateTemplate(CertificateTemplate $certificateTemplate, array $data): CertificateTemplate
    {
        if ($certificateTemplate->image && !empty($data['image'])) {
            FileHandler::deleteFile($certificateTemplate->image);
        }

        if (!empty($data['image'])) {
            $filename = FileHandler::storePhoto($data['image'], 'certificate-template');
            $data['image'] = 'certificate-template/' . $filename;

        } else {
            unset($data['image']);
        }

        if (empty($certificateTemplate->image) && empty($data['image'])) {
            $data['image'] = CertificateTemplate::DEFAULT_IMAGE;
        }
        $certificateTemplate->fill($data);
        $certificateTemplate->save();
        return $certificateTemplate;
    }


    /**
     * @param CertificateTemplate $certificateTemplate
     * @return bool
     * @throws \Exception
     */
    public function deleteCertificateTemplate(CertificateTemplate $certificateTemplate): bool
    {
        if ($certificateTemplate->image) {
            FileHandler::deleteFile($certificateTemplate->image);
        }
        return $certificateTemplate->delete();
    }


    public function getListDataForDatatable(Request $request): JsonResponse
    {
        $authUser = AuthHelper::getAuthUser();

        /** @var Builder|CertificateTemplate $certificateTemplates */
        $certificateTemplates = CertificateTemplate::select([
            'certificate_templates.id',
            'certificate_templates.title',
            'certificate_templates.image',
            'institutes.title as institute_title'
        ]);

        $certificateTemplates->join('institutes', 'certificate_templates.institute_id', 'institutes.id');
        
        if ($authUser->isUserBelongsToInstitute()) {
            $certificateTemplates->where('certificate_templates.institute_id', $authUser->institute_id);
        }

        //dd($certificateTemplates->get());

        return DataTables::eloquent($certificateTemplates)
            // ->editColumn('image', function (CertificateTemplate $certificateTemplate){
            //     return $str1 = '<a href="#" class="btn btn-outline-info btn-sm"> <i class="fas fa-eye"></i> ' . $certificateTemplate->image . ' </a>';
            // })
            ->addColumn('action', DatatableHelper::getActionButtonBlock(static function (CertificateTemplate $certificateTemplate) use ($authUser) {
                $str = '';
                if ($authUser->can('view', $certificateTemplate)) {
                    $str .= '<a href="' . route('admin.certificate-templates.show', $certificateTemplate->id) . '" class="btn btn-outline-info btn-sm"> <i class="fas fa-eye"></i> ' . __('generic.read_button_label') . ' </a>';
                }
                if ($authUser->can('update', $certificateTemplate)) {
                    $str .= '<a href="' . route('admin.certificate-templates.edit', $certificateTemplate->id) . '" class="btn btn-outline-warning btn-sm"> <i class="fas fa-edit"></i> ' . __('generic.edit_button_label') . ' </a>';
                }
                if ($authUser->can('delete', $certificateTemplate)) {
                    $str .= '<a href="#" data-action="' . route('admin.certificate-templates.destroy', $certificateTemplate->id) . '" class="btn btn-outline-danger btn-sm delete"> <i class="fas fa-trash"></i> ' . __('generic.delete_button_label') . '</a>';
                }
                return $str;
            }))
            ->addColumn('image', DatatableHelper::getActionButtonBlock(static function (CertificateTemplate $certificateTemplate) use ($authUser) {
                return "<a style=\"margin-left: 30%;\" target=\"_blank\" href='".asset('storage/'.$certificateTemplate->image)."'><i class=\"fa fa-eye fa-2x\" aria-hidden=\"true\"></i></a>";
                
            }))
            ->rawColumns(['action', 'image'])
            ->toJson();
    }
}
