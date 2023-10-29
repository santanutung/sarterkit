
<?php
use Carbon\Carbon;
if (!function_exists('addon_packages_name')) {

    /**
     * description
     *
     * @param
     * @return
     */
    function addon_packages_name($addon_packages)
    {
        $addon_package_name = "";
        if ($addon_packages) {

            $addon_packages =   json_decode($addon_packages);

            if (is_array($addon_packages) && !empty($addon_packages)) {
                foreach ($addon_packages as $addon_package_id) {
                    $addon_package = \App\Models\AddonPackage::where('id', $addon_package_id)->first();
                    if ($addon_package) {
                        $addon_package_name =    $addon_package_name .  " " . $addon_package->name . ", " ;
                    }

                    
                }
            }
        }
        // $addon_package_name=    trim($addon_package_name);
        $addon_package_name = rtrim($addon_package_name, ', ');
        return $addon_package_name;
    }
}

if (!function_exists('convertYmdToMdy')) {
    function convertYmdToMdy($date)
    {
        if ($date) {
            // return Carbon::createFromFormat('Y-m-d H:i:s', $date)->format('F d, Y'); //"October 26, 2023"
            return Carbon::createFromFormat('Y-m-d H:i:s', $date)->format('D, j M'); //"FRI, 29 Aug"
        } else {
            return "";
        }
    }
}