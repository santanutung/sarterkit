<?php



if (!function_exists('DummyFunction')) {

    /**
     * description
     *
     * @param
     * @return
     */
    function menu($name)
    {
        $menu = \App\Models\Menu::where('name',$name)->first();
        return $menu->menuItems()->with('childs')->get();
    }
}
