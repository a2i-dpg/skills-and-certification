<?php

namespace App\Http\Controllers\GeoLocations;

use App\Helpers\Classes\AuthHelper;
use App\Helpers\Classes\DatatableHelper;
use App\Http\Controllers\BaseController;
use App\Models\LocDivision;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\Facades\DataTables;

class LocDivisionController extends BaseController
{
    private const VIEW_PATH = 'backend.geo-locations.loc-divisions.';

    public function __construct()
    {
        $this->authorizeResource(LocDivision::class);
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
        $locDivision = new LocDivision();

        return view(self::VIEW_PATH . 'ajax.edit-add',compact('locDivision'));
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): JsonResponse
    {
        $validateData = $this->validator($request)->validate();

        try {
            LocDivision::create($validateData);
        } catch (\Throwable $exception) {
            Log::debug($exception->getMessage());
            return response()->json(['message' => __('generic.something_wrong_try_again'), 'alert-type' => 'error']);
        }

        return response()->json(['message' => __('generic.object_created_successfully', ['object' => 'Division']), 'alert-type' => 'success']);
    }

    /**
     * @param LocDivision $locDivision
     * @return View
     */
    public function show(LocDivision $locDivision): View
    {
        return view(self::VIEW_PATH . 'ajax.read', compact('locDivision'));
    }

    /**
     * @param LocDivision $locDivision
     * @return View
     */
    public function edit(LocDivision $locDivision): View
    {
        return view(self::VIEW_PATH . 'ajax.edit-add', compact('locDivision'));
    }

    public function update(Request $request, LocDivision $locDivision): JsonResponse
    {
        $validateData = $this->validator($request)->validate();

        try {
            $locDivision->update($validateData);
        } catch (\Throwable $exception) {
            Log::debug($exception->getMessage());
            return response()->json(['message' => __('generic.something_wrong_try_again'), 'alert-type' => 'error']);
        }

        return response()->json(['message' => __('generic.object_updated_successfully', ['object' => 'Division']), 'alert-type' => 'success']);
    }

    /**
     * @param LocDivision $locDivision
     * @return RedirectResponse
     */
    public function destroy(LocDivision $locDivision): RedirectResponse
    {
        try {
            $locDivision->delete();
        } catch (\Throwable $exception) {
            Log::debug($exception->getMessage());
            return back()->with([
                'message' => __('generic.something_wrong_try_again'),
                'alert-type' => 'error'
            ]);
        }

        return back()->with([
            'message' => __('generic.object_deleted_successfully', ['object' => 'Division']),
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
            'status' => 'nullable',
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
        $locDivisions = LocDivision::select([
            'loc_divisions.id as id',
            'loc_divisions.title',
            'loc_divisions.bbs_code',
            'loc_divisions.created_at',
            'loc_divisions.updated_at'
        ]);

        return DataTables::eloquent($locDivisions)
            ->addColumn('action', DatatableHelper::getActionButtonBlock(static function (LocDivision $locDivision) use($authUser) {
                $str = '';

                if ($authUser->can('view', $locDivision)) {
                    $str .= '<a href="#" data-url="' . route('admin.loc-divisions.show', $locDivision->id) . '" class="btn btn-outline-info btn-sm dt-view"> <i class="fas fa-eye"></i> ' . __('generic.read_button_label') . '</a>';
                }
                if ($authUser->can('update', $locDivision)) {
                    $str .= '<a href="#" data-url="' . route('admin.loc-divisions.edit', $locDivision->id) . '" class="btn btn-outline-warning btn-sm dt-edit"> <i class="fas fa-edit"></i> ' . __('generic.edit_button_label') . ' </a>';
                }
                if ($authUser->can('delete', $locDivision)) {
                    $str .= '<a href="#" data-action="' . route('admin.loc-divisions.destroy', $locDivision->id) . '" class="btn btn-outline-danger btn-sm delete"> <i class="fas fa-trash"></i> ' . __('generic.delete_button_label') . '</a>';
                }
                return $str;
            }))
            ->rawColumns(['action'])
            ->toJson();
    }
}
