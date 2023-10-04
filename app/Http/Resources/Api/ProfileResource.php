<?php

namespace App\Http\Resources\Api;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\Category;

class ProfileResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {

        return [

            "id" => $this->id,
            "name" => $this->name,
            "role_id" => $this->role_id,
            "email" => $this->email,
            "image" => $this->image ? $this->image : 'images/profile-photo.png',
            "phone" => $this->phone,
            "pincode" => $this->pincode,
            "address" => $this->address,
            // "wallet_balance" => $this->wallet_balance,
            // "refer_id" => $this->refer_id,
        ];
    }
}
