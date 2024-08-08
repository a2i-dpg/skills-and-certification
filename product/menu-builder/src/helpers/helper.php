<?php

if (!function_exists('menu')) {
    function menu($menuName, $type = null, array $options = [])
    {
        return (new \Softbd\MenuBuilder\Models\Menu())->display($menuName, $type, $options);
    }
}
