<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Models\Country;
use App\Models\AddonPackage;

use Illuminate\Http\Request;
use \App\Http\Controllers\Api\BaseController;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use App\Http\Resources\Api\CountryResource;
use App\Http\Resources\Api\AddonPackageResource;


class AddonPackageController extends BaseController
{
    public function package_visa_addon(Request $request)
    {
        try {
            $countries = AddonPackage::get();
            
            if ($countries) {
                $countries = AddonPackageResource::collection($countries);
            }
            return $this->response(1, ["messages" => [], "errors" => [], "data" => $countries]);
        } catch (\Throwable $th) {
            return $this->response(0, ["messages" => array(), 'errors' => [$th->getMessage()]]);
        }
    }
}
