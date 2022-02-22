<?php


namespace App\Services;

use App\Helpers\Classes\AuthHelper;
use App\Helpers\Classes\FileHandler;
use App\Models\Institute;
use App\Models\User;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\RequiredIf;
use Yajra\DataTables\Facades\DataTables;


class InstituteService
{
    public function createSSP(array $data): Institute
    {
        $instituteData = Arr::except($data, ['contact_person_password', 'contact_person_password_confirmation']);
        $instituteData['slug'] = Str::slug($instituteData['title']);

        if (!empty($data['google_map_src'])) {
            $instituteData['google_map_src'] = $this->parseGoogleMapSrc($data['google_map_src']);
        }

        if (!empty($data['logo'])) {
            $filename = FileHandler::storePhoto($data['logo'], 'institute');
            $instituteData['logo'] = 'institute/' . $filename;
        } else {
            unset($data['logo']);
        }

        /** @var Institute $institute */
        $institute = Institute::create($instituteData);

        $userData = [];
        $data = Arr::only($data, ['title', 'contact_person_email', 'contact_person_password']);

        $userData['name'] = $data['title'];
        $userData['email'] = $data['contact_person_email'];
        $userData['institute_id'] = $institute->id;
        $userData['user_type_id'] = User::USER_TYPE_INSTITUTE_USER_CODE;
        $userData['password'] = $data['contact_person_password'];
        $userData['row_status'] = User::ROW_STATUS_INACTIVE;

        $authUser = AuthHelper::getAuthUser();
        if ($authUser && $authUser->isSuperUser()) {
            $userData['row_status'] = User::ROW_STATUS_ACTIVE;
        }

        (new UserService())->createUser($userData);

        return $institute;
    }


    public function validator(Request $request, $id = null): Validator
    {
        $rules = [
            'title' => ['required', 'string', 'max:191'],
            'email' => [
                'required',
                'string',
                'email',
            ],
            'mobile' => ['required', 'string', 'max:191'],
            'address' => ['required', 'string', 'max:191'],
            'office_head_name' => [
                'required', 'string',
            ],
            'office_head_post' => [
                'required', 'string',
            ],
            'contact_person_name' => [
                'required', 'string',
            ],
            'contact_person_post' => [
                'required', 'string',
            ],
            'contact_person_mobile' => [
                'required', 'string',
            ],
            'contact_person_email' => [
                'required', 'email', !$id ? Rule::unique('users', 'email') : 'string',
            ],
            'contact_person_password' => [
                'bail',
                new RequiredIf($id == null),
                'confirmed'
            ],
            'logo' => [
                new RequiredIf($id == null),
                'image',
                'mimes:jpeg,jpg,png,gif',
                'max:500',
            ],
            'description' => ['nullable', 'string'],
            'google_map_src' => ['nullable', 'string'],
        ];

        if (!AuthHelper::getAuthUser()) {
            $rules['logo'] = [
                'nullable',
                'image',
                'mimes:jpeg,jpg,png,gif',
                'max:500',
            ];
        }

        $messages = [
            'logo.dimensions' => 'Please upload 370x70 size of image',
            'logo.max' => 'Please upload maximum 500kb size of image',
        ];

        return \Illuminate\Support\Facades\Validator::make($request->all(), $rules, $messages);
    }

    public function getListDataForDatatable(\Illuminate\Http\Request $request): JsonResponse
    {
        $authUser = AuthHelper::getAuthUser();
        /** @var Builder|Institute $institutes */

        $institutes = Institute::acl()->select([
            'institutes.id as id',
            'institutes.title',
            'institutes.email',
            'institutes.mobile',
            'institutes.office_head_name',
            'institutes.office_head_post',
            'institutes.mobile',
            'institutes.contact_person_name',
            'institutes.contact_person_email',
            'institutes.contact_person_mobile',
            'institutes.contact_person_post',
            'institutes.address',
            'institutes.row_status',
            'institutes.created_at',
            'institutes.updated_at'
        ]);

        return DataTables::eloquent($institutes)
            ->addColumn('action', static function (Institute $institute) use ($authUser) {
                $str = '';
                if ($authUser->can('view', $institute)) {
                    $str .= '<a href="' . route('admin.institutes.show', $institute->id) . '" class="btn btn-outline-info btn-sm"> <i class="fas fa-eye"></i> ' . __('generic.read_button_label') . ' </a>';
                }

                if ($authUser->can('update', $institute)) {
                    $str .= '<a href="' . route('admin.institutes.edit', $institute->id) . '" class="btn btn-outline-warning btn-sm"> <i class="fas fa-edit"></i> ' . __('generic.edit_button_label') . ' </a>';
                }
                if ($authUser->can('delete', $institute)) {
                    $str .= '<a href="#" data-action="' . route('admin.institutes.destroy', $institute->id) . '" class="btn btn-outline-danger btn-sm delete"> <i class="fas fa-trash"></i> ' . __('generic.delete_button_label') . '</a>';
                }

                return $str;
            })
            ->addColumn('row_status', function (Institute $institute) {
                return $institute->getCurrentRowStatus(true);
            })
            ->rawColumns(['action', 'row_status'])
            ->toJson();
    }

    public function updateInstitute(Institute $institute, array $data): Institute
    {
        $data['google_map_src'] = $this->parseGoogleMapSrc($data['google_map_src']);

        if ($institute->logo && !$institute->logoIsDefault() && !empty($data['logo'])) {
            FileHandler::deleteFile($institute->logo);
        }

        if (!empty($data['logo'])) {
            $filename = FileHandler::storePhoto($data['logo'], 'institute');
            $data['logo'] = 'institute/' . $filename;
        } else {
            unset($data['logo']);
        }

        $institute->fill($data);
        $institute->save();

        return $institute;
    }

    public function deleteInstitute(Institute $institute): bool
    {
        return $institute->delete();
    }

    /**
     * @param string|null $googleMapSrc
     * @return string
     */
    public function parseGoogleMapSrc(?string $googleMapSrc): ?string
    {
        if (!empty($googleMapSrc) && preg_match('/src="([^"]+)"/', $googleMapSrc, $match)) {
            $googleMapSrc = $match[1];
        }

        return $googleMapSrc;
    }
}
