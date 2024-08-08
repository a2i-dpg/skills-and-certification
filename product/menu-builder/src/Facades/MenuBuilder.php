<?php
namespace Softbd\MenuBuilder\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * Class Logger
 * @package MenuBuilder\Facades
 * @method static saveMenu()
 * @method static createMenuFile(string $menuName)
 * @method static updateMenuName(string $oldMenuName, string $newMenuName)
 */
class MenuBuilder extends Facade {
    protected static function getFacadeAccessor()
    {
        return 'menu-builder';
    }
}
