<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Models\Country;
use App\Models\Visa;
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
use App\Http\Resources\Api\VisaResource;
use App\Http\Resources\Api\VisaDetailsResource;


class VisaController extends BaseController
{
   
    public function get_visa_by_country($country_id)
    {
        try {
            $visas = Visa::where('country_id',$country_id)->get();
            if ($visas) {
                $visas = VisaResource::collection($visas);
            }
            return $this->response(1, ["messages" => [], "errors" => [], "data" => $visas]);
        } catch (\Throwable $th) {
            return $this->response(0, ["messages" => array(), 'errors' => [$th->getMessage()]]);
        }
    }
    public function get_schengen_visa()
    {
        try {
            $visas = Visa::get();
            if ($visas) {
                $visas = VisaResource::collection($visas);
            }
            return $this->response(1, ["messages" => [], "errors" => [], "data" => $visas]);
        } catch (\Throwable $th) {
            return $this->response(0, ["messages" => array(), 'errors' => [$th->getMessage()]]);
        }
    }
    public function get_visa_by_id($id)
    {
        try {
            $visas = Visa::where('id',$id)->first();
            if ($visas) {
                $visas = new VisaDetailsResource($visas);
                return $this->response(1, ["messages" => [], "errors" => [], "data" => $visas]);
            }else{
                return $this->response(0, ["messages" => array(), 'errors' => ['Visa not found.']]);
            }
         
        } catch (\Throwable $th) {
            return $this->response(0, ["messages" => array(), 'errors' => [$th->getMessage()]]);
        }
    }
}
