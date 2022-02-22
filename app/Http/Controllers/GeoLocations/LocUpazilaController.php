<?php

namespace App\Http\Controllers\GeoLocations;

use App\Helpers\Classes\AuthHelper;
use App\Helpers\Classes\DatatableHelper;
use App\Http\Controllers\BaseController;
use App\Models\LocUpazila;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\Facades\DataTables;

class LocUpazilaController extends BaseController
{
    private const VIEW_PATH = 'backend.geo-locations.loc-upazilas.';

    public function __construct()
    {
        $this->authorizeResource(LocUpazila::class);
    }

    /**
     * @return View
     */
    public function index(): View
    {
        return view(self::VIEW_PATH . 'browse');
    }

    /**
     * @return View
     */
    public function create(): View
    {
        $locUpazila = new LocUpazila();

        return view(self::VIEW_PATH . 'ajax.edit-add', compact('locUpazila'));
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): JsonResponse
    {
        $validatedData = $this->validator($request)->validate();

        try {
            LocUpazila::create($validatedData);
        } catch (\Throwable $exception) {
            Log::debug($exception->getMessage());
            return response()->json(['message' => __('generic.something_wrong_try_again'), 'alert-type' => 'error']);
        }

        return response()->json(['message' => __('generic.object_created_successfully', ['object' => 'Upazila']), 'alert-type' => 'success']);

    }

    /**
     * @param LocUpazila $locUpazila
     * @return View
     */
    public function show(LocUpazila $locUpazila): View
    {
        return view(self::VIEW_PATH . 'ajax.read', compact('locUpazila'));
    }

    /**
     * @param LocUpazila $locUpazila
     * @return View
     */
    public function edit(LocUpazila $locUpazila): View
    {
        return view(self::VIEW_PATH . 'ajax.edit-add', compact('locUpazila'));
    }

    public function update(Request $request, LocUpazila $locUpazila): JsonResponse
    {
        $validatedData = $this->validator($request)->validate();

        try {
            $locUpazila->update($validatedData);
        } catch (\Throwable $exception) {
            Log::debug($exception->getMessage());
            return response()->json(['message' => __('generic.something_wrong_try_again'), 'alert-type' => 'error']);
        }

        return response()->json(['message' => __('generic.object_updated_successfully', ['object' => 'Upazila']), 'alert-type' => 'success']);
    }

    /**
     * @param LocUpazila $locUpazila
     * @return RedirectResponse
     */
    public function destroy(LocUpazila $locUpazila): RedirectResponse
    {
        try {
            $locUpazila->delete();
        } catch (\Throwable $exception) {
            Log::debug($exception->getMessage());
            return back()->with([
                'message' => __('generic.something_wrong_try_again'),
                'alert-type' => 'error'
            ]);
        }

        return back()->with([
            'message' => __('generic.object_deleted_successfully', ['object' => 'Upazila']),
            'alert-type' => 'success'
        ]);
    }

    /**
     * @param Request $request
     * @return Validator
     */
    public function validator(Request $request): Validator
    {
        return \Illuminate\Support\Facades\Validator::make($request->all(), [
            'title' => 'required|max:191',
            'bbs_code' => 'required|max:2',
            'loc_division_id' => 'required|exists:loc_divisions,id',
            'loc_district_id' => 'required|exists:loc_districts,id',
            'division_bbs_code' => 'nullable',
            'status' => 'nullable',
            'created_by' => 'nullable',
            'updated_by' => 'nullable',
        ]);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function getDatatable(Request $request): JsonResponse
    {
        $authUser = AuthHelper::getAuthUser();

        /** @var Builder $locDivisions */
        $locUpazilas = LocUpazila::select([
            'loc_upazilas.id as id',
            'loc_upazilas.title',
            'loc_upazilas.bbs_code',
            'loc_upazilas.created_at',
            'loc_upazilas.updated_at',
            'loc_divisions.title as loc_divisions.title',
            'loc_districts.title as loc_districts.title'
        ]);

        /** relations */
        $locUpazilas->join('loc_divisions', 'loc_upazilas.loc_division_id', '=', 'loc_divisions.id');
        $locUpazilas->join('loc_districts', 'loc_upazilas.loc_district_id', '=', 'loc_districts.id');

        return DataTables::eloquent($locUpazilas)
            ->addColumn('action', DatatableHelper::getActionButtonBlock(static function (LocUpazila $locUpazila) use($authUser){
                $str = '';

                if ($authUser->can('view', $locUpazila)) {
                    $str .= '<a href="#" data-url="' . route('admin.loc-upazilas.show', $locUpazila->id) . '" class="btn btn-outline-info btn-sm dt-view"> <i class="fas fa-eye"></i> ' . __('generic.read_button_label') . '</a>';
                }
                if ($authUser->can('update', $locUpazila)) {
                    $str .= '<a href="#" data-url="' . route('admin.loc-upazilas.edit', $locUpazila->id) . '" class="btn btn-outline-warning btn-sm dt-edit"> <i class="fas fa-edit"></i> ' . __('generic.edit_button_label') . ' </a>';
                }
                if ($authUser->can('delete', $locUpazila)) {
                    $str .= '<a href="#" data-action="' . route('admin.loc-upazilas.destroy', $locUpazila->id) . '" class="btn btn-outline-danger btn-sm delete"> <i class="fas fa-trash"></i> ' . __('generic.delete_button_label') . '</a>';
                }

                return $str;
            }))
            ->rawColumns(['action'])
            ->toJson();
    }
}
