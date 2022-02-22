<?php

namespace App\Http\Controllers;

use App\Models\Slider;
use App\Services\SliderService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class SliderController extends Controller
{
    const VIEW_PATH = 'backend.sliders.';
    protected SliderService $sliderService;

    public function __construct(SliderService $sliderService)
    {
        $this->sliderService = $sliderService;
        $this->authorizeResource(Slider::class);
    }

    /**
     * @return View
     */
    public function index(): View
    {
        return \view(self::VIEW_PATH . 'browse');
    }

    /**
     * @return View
     */
    public function create(): View
    {
        $slider = new Slider();
        return \view(self::VIEW_PATH . 'edit-add', compact('slider'));
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     * @throws ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $sliderValidatedData = $this->sliderService->validator($request)->validate();

        try {
            $this->sliderService->createSlider($sliderValidatedData);
        } catch (\Throwable $exception) {
            Log::debug($exception->getMessage());
            return back()->with([
                'message' => __('generic.something_wrong_try_again'),
                'alert-type' => 'error'
            ])->withInput();
        }

        return redirect()->route('admin.sliders.index')->with([
            'message' => __('generic.object_created_successfully', ['object' => 'Slider']),
            'alert-type' => 'success'
        ]);
    }

    /**
     * @param Slider $slider
     * @return View
     */
    public function edit(Slider $slider): View
    {
        return view(self::VIEW_PATH .'edit-add')->with([
            'slider' => $slider,
        ]);
    }

    /**
     * @param Slider $slider
     * @return View
     */
    public function show(Slider $slider): View
    {
        return \view(self::VIEW_PATH . 'read', compact('slider'));
    }


    /**
     * @param Slider $slider
     * @param Request $request
     * @return RedirectResponse
     * @throws ValidationException
     */
    public function update(Slider $slider, Request $request)
    {

        $sliderValidatedData = $this->sliderService->validator($request)->validate();

        try {
            $this->sliderService->updateSlider($slider, $sliderValidatedData);
        } catch (\Throwable $exception) {
            Log::debug($exception->getMessage());
            return back()->with([
                'message' => __('generic.something_wrong_try_again'),
                'alert-type' => 'error'
            ])->withInput();
        }

        return redirect()->route('admin.sliders.index')->with([
            'message' => __('generic.object_updated_successfully', ['object' => 'Slider']),
            'alert-type' => 'success'
        ]);
    }


    /**
     * @param Slider $slider
     * @return RedirectResponse
     */
    public function destroy(Slider $slider): RedirectResponse
    {
        try {
            $slider->delete();
        } catch (\Throwable $exception) {
            Log::debug($exception->getMessage());
            return back()->with([
                'message' => __('generic.something_wrong_try_again'),
                'alert-type' => 'error'
            ]);
        }

        return back()->with([
            'message' => __('generic.object_deleted_successfully', ['object' => 'Slider']),
            'alert-type' => 'success'
        ]);
    }


    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function getDatatable(Request $request): JsonResponse
    {
        return $this->sliderService->getListDataForDatatable($request);
    }
}
