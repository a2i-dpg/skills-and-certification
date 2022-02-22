<?php

namespace Softbd\MenuBuilder\Repositories;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Softbd\MenuBuilder\Models\Menu;
use Softbd\MenuBuilder\Models\MenuItem;

class MenuBuilderRepository
{
    const EXPORT_IMPORT_MENUS_DISK = 'menu-builder-json-local';

    const EXPORT_IMPORT_MENUS_NAME = 'menus.json';
    const EXPORT_IMPORT_MENU_ITEMS_NAME = 'menu-items.json';

    public function orderMenu(array $menuItems, $parentId)
    {
        foreach ($menuItems as $index => $menuItem) {
            /** @var Model $item */
            $item = MenuItem::findOrFail($menuItem->id);
            $item->order = $index + 1;
            $item->parent_id = $parentId;
            $item->save();

            if (isset($menuItem->children)) {
                $this->orderMenu($menuItem->children, $item->id);
            }
        }
    }

    /**
     * @return bool
     * @throws \Exception
     */
    public function exportMenuToLocalJson(): bool
    {
        $menus = Menu::selectAllExcept(['created_at', 'updated_at'])->get()->toJson(JSON_PRETTY_PRINT);
        $menuItems = MenuItem::selectAllExcept(['created_at', 'updated_at'])->get()->toJson(JSON_PRETTY_PRINT);

        $response = Storage::disk(self::EXPORT_IMPORT_MENUS_DISK)->put(self::EXPORT_IMPORT_MENUS_NAME, $menus);
        $response1 = Storage::disk(self::EXPORT_IMPORT_MENUS_DISK)->put(self::EXPORT_IMPORT_MENU_ITEMS_NAME, $menuItems);

        if (!$response || !$response1) {
            throw new \Exception('Unable to export');
        }

        return true;
    }

    /**
     * @return array
     * @throws \Exception
     */
    public function getLocalJsonMenus(): array
    {
        $response = Storage::disk(self::EXPORT_IMPORT_MENUS_DISK)->get(self::EXPORT_IMPORT_MENUS_NAME);
        $menus = json_decode($response, true);

        $response2 = Storage::disk(self::EXPORT_IMPORT_MENUS_DISK)->get(self::EXPORT_IMPORT_MENU_ITEMS_NAME);
        $menuItems = json_decode($response2, true);

        return compact('menus', 'menuItems');
    }

    public function clearAllMenuCache()
    {
        $menus = Menu::all();

        foreach ($menus as $menu) {
            /** @var Menu $menu */
            $menu->removeMenuFromCache();
        }
    }

    public function deleteAllMenu(): bool
    {
        return Menu::where('id', '!=', null)->delete() &&
        MenuItem::where('id', '!=', null)->delete();
    }
}
