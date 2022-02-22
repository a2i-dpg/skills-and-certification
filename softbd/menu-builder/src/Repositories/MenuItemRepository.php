<?php


namespace Softbd\MenuBuilder\Repositories;


use Illuminate\Contracts\Validation\Validator;
use Illuminate\Support\Arr;
use Softbd\MenuBuilder\Models\Menu;
use Softbd\MenuBuilder\Models\MenuItem;

class MenuItemRepository
{
    public function createMenuItem($data)
    {
        $data = $this->prepareParameters($data);
        unset($data['id']);

        $data['order'] = (new \Softbd\MenuBuilder\Models\MenuItem)->highestOrderMenuItem();

        /** @var MenuItem $menuItem */
        $menuItem = MenuItem::create($data);
    }

    public function menuItemValidator(array $data): Validator
    {
        $rules = [
            'title' => 'required_without:title_lang_key',
            'title_lang_key' => 'required_without:title',
            'menu_id' => 'required',
            'type' => 'required',
            'url' => 'required_without:route',
            'route' => 'required_without:url',
            'icon_class' => 'nullable|string',
            'permission_key' => 'nullable|string',
            'color' => 'nullable|string',
            'target' => 'nullable|string',
        ];

        return \Illuminate\Support\Facades\Validator::make($data, $rules);
    }

    public function prepareParameters($parameters)
    {
        switch (Arr::get($parameters, 'type')) {
            case 'route':
                $parameters['url'] = null;
                break;
            default:
                $parameters['route'] = null;
                $parameters['parameters'] = '';
                break;
        }

        if (isset($parameters['type'])) {
            unset($parameters['type']);
        }

        return $parameters;
    }

    public function updateMenuItem(MenuItem $menuItem, array $data)
    {
        $data = $this->prepareParameters($data);
        $menuItem->update($data);
    }
}
