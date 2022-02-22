<?php

namespace App\Helpers\Classes;

use Illuminate\Database\Eloquent\Model;

class DatatableHelper
{
    public static function getActionButtonBlock(callable $callback): \Closure
    {
        return function (Model $model) use ($callback) {
            $hasAnyButton = false;
            $str = '';
            $str .= '<div class="btn-group btn-group-sm" role="group">';

            $callbackString = $callback($model);

            if ($callbackString) {
                $hasAnyButton = true;
                $str .= $callbackString;
            }
            $str .= '</div>';

            return $hasAnyButton ? $str : '<span />';
        };
    }
}
