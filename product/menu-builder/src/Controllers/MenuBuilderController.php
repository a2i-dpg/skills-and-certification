<?php

namespace Softbd\MenuBuilder\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Softbd\MenuBuilder\Models\Menu;
use Softbd\MenuBuilder\Models\MenuItem;
use Softbd\MenuBuilder\Repositories\MenuBuilderRepository;
use Softbd\MenuBuilder\Repositories\MenuRepository;

class MenuBuilderController
{
    private const VIEW_PATH = 'menu-builder::';

    private MenuBuilderRepository $menuBuilderRepo;

    public function __construct()
    {
        $this->menuBuilderRepo = new MenuBuilderRepository();
    }

    public function builder($id)
    {
        $menu = (new MenuRepository())->getMenuInstance($id);
        return view(self::VIEW_PATH . 'menu-builder', compact('menu'));
    }

    public function orderItem(Request $request)
    {
        $menuItemOrder = json_decode($request->input('order'));

        $this->menuBuilderRepo->orderMenu($menuItemOrder, null);
    }

    public function exportAllMenu(): \Illuminate\Http\RedirectResponse
    {
        try {
            $menus = $this->menuBuilderRepo->exportMenuToLocalJson();
        } catch (\Exception $e) {
            Log::debug($e->getMessage());
            Log::debug($e->getTraceAsString());
            return back()->with([
                'message' => __('Something went wrong. Please try again.'),
                'alert-type' => 'error'
            ]);
        }

        return back()->with([
            'message' => __('Menu Exported successfully.'),
            'alert-type' => 'success'
        ]);
    }

    public function importAllMenu(): \Illuminate\Http\RedirectResponse
    {
        DB::beginTransaction();
        try {
            DB::statement('SET FOREIGN_KEY_CHECKS=0;');
            $this->menuBuilderRepo->clearAllMenuCache();
            $this->menuBuilderRepo->deleteAllMenu();

            ['menus' => $menus, 'menuItems' => $menuItems] = $this->menuBuilderRepo->getLocalJsonMenus();

            if (count($menus)) {
                Menu::insert($menus);
            }

            if (count($menuItems)) {
                MenuItem::insert($menuItems);
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::debug($e->getMessage());
            Log::debug($e->getTraceAsString());
            return back()->with([
                'message' => __('Something went wrong. Please try again.'),
                'alert-type' => 'error'
            ]);
        } finally {
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        }

        return back()->with([
            'message' => __('Menu Imported successfully.'),
            'alert-type' => 'success'
        ]);
    }
}
