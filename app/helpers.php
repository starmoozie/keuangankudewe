<?php

if (!function_exists('rupiah')) {
    /**
     * Format number to rupiah
     * @param number $amount
     */
    function rupiah($amount): string
    {
        return number_format($amount, 0, ',', '.');
    }
}

if (!function_exists('dateFormat')) {
    /**
     * Format date to Y-m-d
     * @param number $date
     */
    function dateFormat($date): string
    {
        return \Carbon\Carbon::parse($date)->format('Y-m-d');
    }
}

if (!function_exists('rupiahToNumber')) {
    /**
     * number format
     * @param string
     */
    function rupiahToNumber($number_format): string
    {
        return str_replace('.', '', $number_format);
    }
}

if (!function_exists('mappingSidebar')) {
    function mappingSidebar($menu)
    {
        if ($menu->count()) {
            foreach ($menu as $k => $menu_item) {
                $menu_item->children = collect([]);

                foreach ($menu as $i => $menu_subitem) {
                    if ($menu_subitem->parent_id == $menu_item->id) {
                        $menu_item->children->push($menu_subitem);

                        // remove the subitem for the first level
                        $menu = $menu->reject(function ($item) use ($menu_subitem) {
                            return $item->id == $menu_subitem->id;
                        });
                    }
                }
            }
        }

        return $menu;
    }
}
