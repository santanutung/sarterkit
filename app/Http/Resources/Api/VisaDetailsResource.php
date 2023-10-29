<?php

namespace App\Http\Resources\Api;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\AddonPackage;

class VisaDetailsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $AddonPackages = AddonPackage::get();
        if ($AddonPackages) {
            $AddonPackages = AddonPackageResource::collection($AddonPackages);
        }

        return [
            "id" => $this->id,
            "name" => $this->name,
            "sku" => $this->sku,
            "price" => $this->price,
            "country" => $this->country->name,
            "image" => $this->image,
            "addon-packages" => $AddonPackages,
        ];
    }
}
