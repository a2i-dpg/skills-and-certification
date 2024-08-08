<?php


namespace Softbd\MenuBuilder\Repositories;


use Illuminate\Contracts\Validation\Validator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Softbd\MenuBuilder\Models\Menu;

class MenuRepository
{
    public function getMenuInstance(int $id): Menu
    {
        return Menu::findOrfail($id);
    }


    /**
     * @param array $data
     * @return bool
     */
    public function createMenu(array $data): Model
    {
        $menu = new Menu();
        $menu->fill($data);
        $menu->save();
        return $menu;
    }

    /**
     * @param Model $menu
     * @param array $data
     * @return Model
     */
    public function updateMenu(Model $menu, array $data): Model
    {
        $menu->fill($data);

        $menu->save();

        return $menu;
    }

    /**
     * @return Collection
     */
    public function getAllMenu(): Collection
    {
        return Menu::all();
    }

    public function validator(Request $request): Validator
    {
        return \Illuminate\Support\Facades\Validator::make($request->all(), [
            'name' => 'required|max:191'
        ]);
    }
}