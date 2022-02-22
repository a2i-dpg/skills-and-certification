<?php

namespace App\Http\Controllers\GeoLocations;

use App\Helpers\Classes\AuthHelper;
use App\Helpers\Classes\DatatableHelper;
use App\Http\Controllers\BaseController;
use App\Models\LocDistrict;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\Facades\DataTables;

class LocDistrictController extends BaseController
{
    private const VIEW_PATH = 'backend.geo-locations.loc-districts.';


    public function __construct()
    {
        $this->authorizeResource(LocDistrict::class);
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
        $locDistrict = new LocDistrict();

        return view(self::VIEW_PATH . 'ajax.edit-add', compact('locDistrict'));
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
            LocDistrict::create($validatedData);
        } catch (\Throwable $exception) {
            Log::debug($exception->getMessage());
            return response()->json(['message' => __('generic.something_wrong_try_again'), 'alert-type' => 'error']);
        }

        return response()->json(['message' => __('generic.object_created_successfully', ['object' => 'District']), 'alert-type' => 'success']);

    }

    /**
     * @param LocDistrict $locDistrict
     * @return View
     */
    public function show(LocDistrict $locDistrict): View
    {
        return view(self::VIEW_PATH . 'ajax.read', compact('locDistrict'));
    }

    /**
     * @param LocDistrict $locDistrict
     * @return View
     */
    public function edit(LocDistrict $locDistrict): View
    {
        return view(self::VIEW_PATH . 'ajax.edit-add', compact('locDistrict'));
    }

    /**
     * @param Request $request
     * @param LocDistrict $locDistrict
     * @return JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function update(Request $request, LocDistrict $locDistrict): JsonResponse
    {
        $validatedData = $this->validator($request)->validate();

        try {
            $locDistrict->update($validatedData);
        } catch (\Throwable $exception) {
            Log::debug($exception->getMessage());
            return response()->json(['message' => __('generic.something_wrong_try_again'), 'alert-type' => 'error']);
        }

        return response()->json(['message' => __('generic.object_updated_successfully', ['object' => 'District']), 'alert-type' => 'success']);
    }

    /**
     * @param LocDistrict $locDistrict
     * @return RedirectResponse
     */
    public function destroy(LocDistrict $locDistrict): RedirectResponse
    {
        try {
            $locDistrict->delete();
        } catch (\Throwable $exception) {
            Log::debug($exception->getMessage());
            return back()->with([
                'message' => __('generic.something_wrong_try_again'),
                'alert-type' => 'error'
            ]);
        }

        return back()->with([
            'message' => __('generic.object_deleted_successfully', ['object' => 'District']),
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
            'loc_division_id'=> 'required|exists:loc_divisions,id',
            'division_bbs_code'=> 'nullable',
            'status'=> 'nullable',
            'created_by' => 'nullable',
            'updated_by' => 'nullable'
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
        $locDistricts = LocDistrict::select([
            'loc_districts.id',
            'loc_districts.title',
            'loc_districts.bbs_code',
            'loc_districts.created_at',
            'loc_districts.updated_at',
            'loc_divisions.title as loc_divisions.title',
        ]);

        /** relations */
        $locDistricts->join('loc_divisions', 'loc_districts.loc_division_id', '=', 'loc_divisions.id');

        return DataTables::eloquent($locDistricts)
            ->addColumn('action', DatatableHelper::getActionButtonBlock(static function (LocDistrict $locDistrict) use($authUser) {
                $str = '';
                if ($authUser->can('view', $locDistrict)) {
                    $str .= '<a href="#" data-url="' . route('admin.loc-districts.show', $locDistrict->id) . '" class="btn btn-outline-info btn-sm dt-view"> <i class="fas fa-eye"></i> ' . __('generic.read_button_label') . '</a>';
                }
                if ($authUser->can('update', $locDistrict)) {
                    $str .= '<a href="#" data-url="' . route('admin.loc-districts.edit', $locDistrict->id) . '" class="btn btn-outline-warning btn-sm dt-edit"> <i class="fas fa-edit"></i> ' . __('generic.edit_button_label') . ' </a>';
                }
                if ($authUser->can('delete', $locDistrict)) {
                    $str .= '<a href="#" data-action="' . route('admin.loc-districts.destroy', $locDistrict->id) . '" class="btn btn-outline-danger btn-sm delete"> <i class="fas fa-trash"></i> ' . __('generic.delete_button_label') . '</a>';
                }

                return $str;
            }))
            ->rawColumns(['action'])
            ->toJson();
    }
}
