<?php

namespace Softbd\MenuBuilder\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Softbd\MenuBuilder\Models\MenuItem;
use Softbd\MenuBuilder\Repositories\MenuItemRepository;

class MenuItemController
{
    private const VIEW_PATH = 'menu-builder::menu-items.';

    private $menuItemRepo;

    public function __construct()
    {
        $this->menuItemRepo = new MenuItemRepository();
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->all();

        $this->menuItemRepo->menuItemValidator($data)->validate();

        try {
            $this->menuItemRepo->createMenuItem($data);
        } catch (\Throwable $exception) {
            Log::debug($exception->getMessage());
            return back()->with([
                'message' => __('Something went wrong. Please try again.'),
                'alert-type' => 'error'
            ]);
        }

        return redirect()
            ->route('menu-builder.menus.builder', $data['menu_id'])
            ->with([
                'message' => __('Menu item created successfully.'),
                'alert-type' => 'success',
            ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $data = $request->except('id');

        $this->menuItemRepo->menuItemValidator($data)->validate();

        $menuItem = MenuItem::findOrFail($request->input('id', -1));

        try {
            $this->menuItemRepo->updateMenuItem($menuItem, $data);
        } catch (\Throwable $exception) {
            Log::debug($exception->getMessage());
            return back()->with([
                'message' => __('Something went wrong. Please try again.'),
                'alert-type' => 'error'
            ]);
        }

        return redirect()
            ->route('menu-builder.menus.builder', $data['menu_id'])
            ->with([
                'message' => __('Menu item updated successfully.'),
                'alert-type' => 'success',
            ]);
    }


    public function destroy($menu_id, $id): RedirectResponse
    {
        $item = MenuItem::findOrFail($id);

        try {
            $item->delete();
        } catch (\Throwable $exception) {
            Log::debug($exception->getMessage());
            return back()->with([
                'message' => __('Something went wrong. Please try again.'),
                'alert-type' => 'error'
            ]);
        }

        return redirect()
            ->route('menu-builder.menus.builder', $menu_id)
            ->with([
                'message' => __('Menu item deleted successfully'),
                'alert-type' => 'success',
            ]);
    }
}