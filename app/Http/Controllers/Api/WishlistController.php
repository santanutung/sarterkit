<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\Wishlist;
use App\Models\Visa;

use App\Models\AddonPackage;
use App\Http\Controllers\Api\BaseController;
use App\Exceptions\ValidationExceptionApi;
use Illuminate\Support\Facades\Validator;

class WishlistController extends BaseController
{
    public function addToWishlist(Request $request)
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

        $wishlist =  Wishlist::where('user_id', auth()->user()->id)->where('visa_id', "=", $visa_id)->first();
        if ($wishlist) {
            $wishlist->addon_packages = json_encode($addon_packages);
            if ($wishlist->save()) {
                return $this->response(1, ["messages" => ['Visa update to the wishlist'], 'errors' => []]);
            } else {
                return $this->response(0, ["messages" => array("Error"), "errors" => array("Something went wrong try again latter.")]);
            }
        } else {
            $wishlist =  new  Wishlist;
            $wishlist->visa_id = $visa_id;
            $wishlist->user_id = auth()->user()->id;
            $wishlist->addon_packages = json_encode($addon_packages);

            if ($wishlist->save()) {
                return $this->response(1, ["messages" => ['Visa added to the wishlist'], 'errors' => []]);
            } else {
                return $this->response(0, ["messages" => array("Error"), "errors" => array("Something went wrong try again latter.")]);
            }
        }
    }

    public function updateWishlist(Request $request)
    {
        // Update the user's wishlist
        // You should implement your own logic here

        return response()->json(['message' => 'Wishlist updated']);
    }

    public function removeFromWishlist($visa_id)
    {
        $wishlist =  Wishlist::where('user_id', auth()->user()->id)->where('visa_id', "=", $visa_id)->first();
        if ($wishlist) {
            if ($wishlist->delete()) {
                return $this->response(1, ["messages" => ['Visa removed from the wishlist'], 'errors' => []]);
            }
            return $this->response(0, ["messages" => array("Error"), "errors" => array("Something went wrong try again latter.")]);
        }
        return $this->response(0, ["messages" => array("Error"), "errors" => array("Visa not found.")]);
    }

   
    public function wishlists()
    {
        // Retrieve the user's cart contents
        // You should implement your own logic here
        $carts =  Wishlist::where('user_id', auth()->user()->id)->where('visa_id', "!=", null)->get();
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


            if ($cart->addon_packages) {
                $addon_package_ids = json_decode($cart->addon_packages);
                if (is_array($addon_package_ids)) {
            
                    $adddon_packages = AddonPackage::whereIn('id', $addon_package_ids)->get();
                    foreach ($adddon_packages as $key2 => $adddon_package) {
                        $total_price += $adddon_package->price;
                        $item['adddon_packages'][] = [
                            'id' => $adddon_package->id,
                            'name' => $adddon_package->name,
                            'price' => $adddon_package->price,
                            'created_at' => $adddon_package->created_at,
                            'updated_at' => $adddon_package->updated_at,
                        ];
                    }
                }
            }






         
            $response[] = $item;
        }
        // $response['total_price'] = $total_price;
        // $response['total_price'] = $total_price;

        return $this->response(1, ["messages" => [''], 'errors' => [], 'cart' => $response,'total_price'=>$total_price]);
    }
}
