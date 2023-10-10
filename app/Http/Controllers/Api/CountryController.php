<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Models\Country;
use App\Models\WalletTransaction;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Firebase\Auth\Token\Exception\InvalidToken;
use \App\Http\Controllers\Api\BaseController;
use App\Exceptions\ValidationExceptionApi;
use App\Http\Resources\Api\ProfileResource;
use Laravel\Sanctum\PersonalAccessToken;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use App\Http\Resources\Api\CountryResource;


class CountryController extends BaseController
{
    public function get_countries(Request $request)
    {
        try {
            $countries = Country::get();
            if ($countries) {
                $countries = CountryResource::collection($countries);
            }
            return $this->response(1, ["messages" => [], "errors" => [], "data" => $countries]);
        } catch (\Throwable $th) {
            return $this->response(0, ["messages" => array(), 'errors' => [$th->getMessage()]]);
        }
    }
    public function most_visited_countries(Request $request)
    {
        try {
            $countries = Country::get();
            if ($countries) {
                $countries = CountryResource::collection($countries);
            }
            return $this->response(1, ["messages" => [], "errors" => [], "data" => $countries]);
        } catch (\Throwable $th) {
            return $this->response(0, ["messages" => array(), 'errors' => [$th->getMessage()]]);
        }
    }
}
