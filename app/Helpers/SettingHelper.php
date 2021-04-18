<?php

if (!function_exists('DummyFunction')) {

    /**
     * description
     *
     * @param
     * @return
     */
    function setting($name,$defult=null)
    {
        return \App\Models\Setting::getByName($name, $defult);
    }
}
