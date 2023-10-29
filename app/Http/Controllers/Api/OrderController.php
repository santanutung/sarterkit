<?php

namespace App\Http\Controllers\Api;

use App\Models\OrderItem;
use App\Models\Visa;
use App\Models\Order;
use App\Models\Cart;
use App\Models\AddonPackage;

use Illuminate\Http\Request;
use \App\Http\Controllers\Api\BaseController;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use App\Http\Resources\Api\OrderHistoryResource;
use App\Http\Resources\Api\AddonPackageResource;


class OrderController extends BaseController
{
    public function create_order(Request $request)
    {
        try {
            $carts =  Cart::where('user_id', auth()->user()->id)->where('visa_id', "!=", null)->get();

            $total_price = 0;
            $order = new Order;
            $order->user_id = auth()->user()->id;
            $order->name_of_applicant = $request->name_of_applicant;
            $order->date_of_birth = $request->date_of_birth;
            $order->place_of_birth = $request->place_of_birth;
            $order->email_address = $request->email_address;
            $order->phone_number = $request->phone_number;
            $order->marital_status = $request->marital_status;
            $order->address = $request->address;
            $order->passport_number = $request->passport_number;
            $order->date_of_issue_of_passport = $request->date_of_issue_of_passport;
            $order->expiry_date_of_passport = $request->expiry_date_of_passport;
            $order->place_of_issue = $request->place_of_issue;
            $order->purpose_for_travel = $request->purpose_for_travel;
            $order->type_of_entry = $request->type_of_entry;
            $order->travel_date = $request->travel_date;
            $order->length_of_stay = $request->length_of_stay;
            $order->visiting_country = $request->visiting_country;
            $order->present_occupation = $request->present_occupation;
            $order->employer_address = $request->employer_address;
            $order->highest_education_details = $request->highest_education_details;
            $order->name_of_institution = $request->name_of_institution;
            $order->address_of_institution = $request->address_of_institution;
            $order->save();
            foreach ($carts as $key => $cart) {
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
                $order_item =  new OrderItem;
                $order_item->order_id = $order->id;
                $order_item->visa_id =  $visa->id;
                $order_item->addon_packages = $cart->addon_packages;
                if ($cart->addon_packages) {
                    $addon_package_ids = json_decode($cart->addon_packages);
                    if (is_array($addon_package_ids)) {
                        $adddon_packages_total = 0;
                        $adddon_packages = AddonPackage::whereIn('id', $addon_package_ids)->get();
    
                        foreach ($adddon_packages as $key2 => $adddon_package) {
    
    
                            $adddon_packages_total += $adddon_package->price;
    
                            $total_price += $adddon_package->price;
                            $item['adddon_packages'][] = [
                                'id' => $adddon_package->id,
                                'name' => $adddon_package->name,
                                'price' => $adddon_package->price,
                                'created_at' => $adddon_package->created_at,
                                'updated_at' => $adddon_package->updated_at,
                            ];
                        }
                        $order_item->total_amount =    $adddon_packages_total;
                    }
                }
                $order_item->save();
            }
    
            $order->total_amount = $total_price;
            $order->save();

            return $this->response(1, ["messages" => ['order created Successfully'], 'errors' => []]);
        } catch (\Throwable $th) {
            return $this->response(0, ["messages" => array(), 'errors' => [$th->getMessage()]]);
        }

        // $request->
    
    }
    public function order_history(Request $request)
    {
      
        auth()->user()->id;
        $orders=Order::where('user_id',auth()->user()->id)->where('user_id',auth()->user()->id)->get();
        $orders = OrderHistoryResource::collection($orders);
        return    $orders;
        // $request->
    
    }
}
