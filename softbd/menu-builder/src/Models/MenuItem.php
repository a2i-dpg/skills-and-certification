<?php

namespace Softbd\MenuBuilder\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schema;


/**
 * App\Models\MenuItem
 *
 * @property int $id
 * @property int|null $menu_id
 * @property string $title
 * @property string|null $title_lang_key
 * @property string|null $permission_key
 * @property string $url
 * @property string $target
 * @property string|null $icon_class
 * @property string|null $color
 * @property int|null $parent_id
 * @property int $order
 * @property string|null $route
 * @property string|null $parameters
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Collection|\Softbd\MenuBuilder\Models\MenuItem[] $children
 * @property-read int|null $children_count
 * @property-read \Softbd\MenuBuilder\Models\Menu|null $menu
 * @method static Builder|\Softbd\MenuBuilder\Models\MenuItem newModelQuery()
 * @method static Builder|\Softbd\MenuBuilder\Models\MenuItem newQuery()
 * @method static Builder|\Softbd\MenuBuilder\Models\MenuItem query()
 * @method static Builder|\Softbd\MenuBuilder\Models\MenuItem selectAllExcept($exceptColumns)
 */
class MenuItem extends Model
{
    protected $table = 'menu_items';

    protected $guarded = [];

    public static function boot()
    {
        parent::boot();

        static::created(static function (self $model) {
            $model->menu->removeMenuFromCache();
        });

        static::saved(static function (self $model) {
            $model->menu->removeMenuFromCache();
        });

        static::deleted(static function (self $model) {
            $model->menu->removeMenuFromCache();
        });
    }

    public function scopeSelectAllExcept(Builder $query, array $exceptColumns = []): Builder
    {
        $allColumns = Schema::getColumnListing($this->getTable());
        $getColumns = array_diff($allColumns, $exceptColumns);
        return $query->select($getColumns);
    }

    public function children()
    {
        return $this->hasMany(MenuItem::class, 'parent_id')
            ->with('children');
    }

    public function menu(): BelongsTo
    {
        return $this->belongsTo(Menu::class);
    }

    public function link($absolute = false)
    {
        return $this->prepareLink($absolute, $this->route, $this->parameters, $this->url);
    }

    public function translatorLink($translator, $absolute = false)
    {
        return $this->prepareLink($absolute, $translator->route, $translator->parameters, $translator->url);
    }

    protected function prepareLink($absolute, $route, $parameters, $url)
    {
        if (is_null($parameters)) {
            $parameters = [];
        }

        if (is_string($parameters)) {
            $parameters = json_decode($parameters, true);
        } elseif (is_object($parameters)) {
            $parameters = json_decode(json_encode($parameters), true);
        }

        if (!is_null($route)) {
            if (!Route::has($route)) {
                return '#';
            }

            return route($route, $parameters, $absolute);
        }

        if ($absolute) {
            return url($url);
        }

        return $url;
    }

    public function getParametersAttribute()
    {
        return json_decode($this->attributes['parameters']);
    }

    public function setParametersAttribute($value)
    {
        if (is_array($value)) {
            $value = json_encode($value);
        }

        $this->attributes['parameters'] = $value;
    }

    public function setUrlAttribute($value)
    {
        if (is_null($value)) {
            $value = '';
        }

        $this->attributes['url'] = $value;
    }

    /**
     * Return the Highest Order Menu Item.
     *
     * @param null $parent (Optional) Parent id. Default null
     *
     * @return int Order number
     */
    public function highestOrderMenuItem($parent = null): int
    {
        $order = 1;

        $item = self::where('parent_id', '=', $parent)
            ->orderBy('order', 'DESC')
            ->first();

        if (!is_null($item)) {
            $order = ((int)($item->order)) + 1;
        }

        return $order;
    }

    /**
     * @param string $currentUrl
     * @return bool
     */
    public function anyChildrenBrowseCurrentUrl(string $currentUrl): bool
    {
        /** @var self $children */
        $children = $this->children;

        foreach ($children as $child) {
            if (url($child->link()) == $currentUrl) {
                return true;
            }
        }

        return false;
    }
}
