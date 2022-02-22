<?php

namespace Softbd\MenuBuilder\Models;

use App\Helpers\Classes\AuthHelper;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Str;


/**
 * App\Models\Menu
 *
 * @property int $id
 * @property string $name
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Collection|\Softbd\MenuBuilder\Models\MenuItem[] $items
 * @property-read int|null $items_count
 * @property-read Collection|\Softbd\MenuBuilder\Models\MenuItem[] $parent_items
 * @property-read int|null $parent_items_count
 */
class Menu extends Model
{
    const CACHE_KEY_PREFIX = 'admin_menu_';

    protected $table = 'menus';

    protected $guarded = [];

    public static function boot()
    {
        parent::boot();

        static::saved(
            static function (Menu $model) {
                $model->removeMenuFromCache();
            }
        );

        static::deleted(
            static function (Menu $model) {
                $model->removeMenuFromCache();
            }
        );
    }

    public function scopeSelectAllExcept(Builder $query, array $exceptColumns = []): Builder
    {
        $allColumns = Schema::getColumnListing($this->getTable());
        $getColumns = array_diff($allColumns, $exceptColumns);
        return $query->select($getColumns);
    }


    public function items()
    {
        return $this->hasMany(MenuItem::class);
    }

    public function parent_items()
    {
        return $this->hasMany(MenuItem::class, 'menu_id')
            ->whereNull('parent_id');
    }

    /**
     * Display menu.
     *
     * @param string $menuName
     * @param string|null $type
     * @param array $options
     *
     * @return string
     */
    public static function display($menuName, $type = null, array $options = [])
    {
        /** GET THE MENU - sort collection in blade */
        $menu = Cache::rememberForever(
            self::CACHE_KEY_PREFIX . $menuName,
            static function () use ($menuName) {
                return static::where('name', '=', $menuName)
                    ->with(
                        [
                            'parent_items.children' => static function ($q) {
                                $q->orderBy('order');
                            }
                        ]
                    )
                    ->first();
            }
        );

        /** Check for Menu Existence */
        if (!isset($menu)) {
            return false;
        }

        /** Convert options array into object */
        $options = (object)$options;

        $items = $menu->parent_items->sortBy('order');

        $items = static::processItems($items);

        if ($type === 'admin') {
            $type = 'menu-builder::menu-' . $type;
        } elseif ($type === 'builder') {
            $type = 'menu-builder::menu.menu-builder-editor';
        } elseif ($type === 'admin-lte') {
            $type = 'menu-builder::menu.admin-lte-sidebar-menu';
        } else {
            if (is_null($type)) {
                $type = 'menu-builder::menu.default';
            } else {
                return $items;
            }
        }

        if ($type === '_json') {
            return $items;
        }


        return new HtmlString(
            View::make($type, ['items' => $items, 'options' => $options])->render()
        );
    }

    public function removeMenuFromCache()
    {
        Cache::forget(self::CACHE_KEY_PREFIX . $this->name);
    }

    private static function processItems($items)
    {
        $items = $items->transform(
            static function ($item) {
                /** Translate title */
                if (strlen($item->title_lang_key)) {
                    $item->title = __($item->title_lang_key);
                }

                /** Resolve URL/Route */
                $item->href = $item->link(true);

                if ($item->href == url()->current() && $item->href != '') {
                    /** The current URL is exactly the URL of the menu-item */
                    $item->active = true;
                } elseif (Str::startsWith(url()->current(), Str::finish($item->href, '/'))) {
                    /** The current URL is "below" the menu-item URL. For example "admin/posts/1/edit" => "admin/posts" */
                    $item->active = true;
                }
                if ($item->children->count() > 0) {
                    /** Exclude sub-menus */
                    $item->active = false;
                }

                if ($item->children->count() > 0) {
                    $item->setRelation('children', static::processItems($item->children));

                    if (!$item->children->where('active', true)->isEmpty()) {
                        $item->active = true;
                    }
                }

                return $item;
            }
        );

        /** Filter items by permission */
        $items = $items->filter(static function ($item) {
            $authUser = AuthHelper::getAuthUser();
            return !$item->children->isEmpty() || ($authUser && $authUser->can('visit_this_menu_item', $item));
        });

        $items = $items->filter(
            static function ($item) {
                /** Filter out empty menu-items */
                if ($item->url == '' && $item->route == '' && $item->children->count() == 0) {
                    return false;
                }
                //NOTE: added in blade.
//                if ($item->children->isEmpty() && $item->url == '#') {
//                    return false;
//                }
                return true;
            }
        );

        return $items->values();
    }
}
