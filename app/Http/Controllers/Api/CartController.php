<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\Visa;

use App\Models\AddonPackage;


use App\Http\Controllers\Api\BaseController;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use App\Http\Resources\Api\CountryResource;
use App\Http\Resources\Api\AddonPackageResource;
use App\Exceptions\ValidationExceptionApi;
use Illuminate\Support\Facades\Validator;

class CartController extends BaseController
{
    public function addToCart(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'visa_id' => 'required',

                'addon_packages' => 'required',
            ]
        );
        if ($validator->fails()) {
            $x = new ValidationExceptionApi($validator);
            $msg = $x->getMessages();
            return $this->response(0, ["messages" => array("Error"), 'errors' => $msg]);
        }

        $visa_id = $request->input('visa_id');
        // $addon_packages = json_decode($request->input('addon_packages'), true);
        $addon_packages = $request->input('addon_packages');

        $cart =  Cart::where('user_id', auth()->user()->id)->where('visa_id', "=", $visa_id)->first();
        if ($cart) {
            $cart->addon_packages = json_encode($addon_packages);
            if ($cart->save()) {
                return $this->response(1, ["messages" => ['Visa update to the cart'], 'errors' => []]);
            } else {
                return $this->response(0, ["messages" => array("Error"), "errors" => array("Something went wrong try again latter.")]);
            }
        } else {
            $cart =  new  Cart;
            $cart->visa_id = $visa_id;
            $cart->user_id = auth()->user()->id;
            $cart->addon_packages = json_encode($addon_packages);

            if ($cart->save()) {
                return $this->response(1, ["messages" => ['Visa added to the cart'], 'errors' => []]);
            } else {
                return $this->response(0, ["messages" => array("Error"), "errors" => array("Something went wrong try again latter.")]);
            }
        }
    }

    public function updateCart(Request $request)
    {
        // Update the user's cart
        // You should implement your own logic here

        return response()->json(['message' => 'Cart updated']);
    }

    public function removeFromCart($visa_id)
    {
        $cart =  Cart::where('user_id', auth()->user()->id)->where('visa_id', "=", $visa_id)->first();
        if ($cart) {
            if ($cart->delete()) {
                return $this->response(1, ["messages" => ['Visa removed from the cart'], 'errors' => []]);
            }
            return $this->response(0, ["messages" => array("Error"), "errors" => array("Something went wrong try again latter.")]);
        }
        return $this->response(0, ["messages" => array("Error"), "errors" => array("Visa not found.")]);
    }

    public function carts()
    {
        // Retrieve the user's cart contents
        // You should implement your own logic here
        $carts =  Cart::where('user_id', auth()->user()->id)->where('visa_id', "!=", null)->get();
        $response = [];
        $total_price = 0;

        foreach ($carts as $key=> $cart) {
            $visa = Visa::where('id', $cart->visa_id)->first();
            $item = [
                'id' => $visa->id,
                'name' => $visa->name,
                'sku' => $visa->sku,
                'country' => $visa->country->name,
                'price' => $visa->price,
                'adddon_packages' => [],
            ];

            $total_price += $item['price'];

            $adddon_packages = AddonPackage::whereIn('id', json_decode($cart->addon_packages))->get();
            foreach ($adddon_packages as $adddon_package) {
                $total_price += $adddon_package->price;
                $item['adddon_packages'][] = [
                    'id' => $adddon_package->id,
                    'name' => $adddon_package->name,
                    'price' => $adddon_package->price,
                    'created_at' => $adddon_package->created_at,
                    'updated_at' => $adddon_package->updated_at,
                ];
            }
         
            $response[] = $item;
        }
        // $response['total_price'] = $total_price;
        // $response['total_price'] = $total_price;

        return $this->response(1, ["messages" => [''], 'errors' => [], 'cart' => $response,'total_price'=>$total_price]);
    }
  
}
