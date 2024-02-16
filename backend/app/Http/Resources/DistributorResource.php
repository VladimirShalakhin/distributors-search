<?php

namespace App\Http\Resources;

use App\Models\Distributor;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin Distributor */
class DistributorResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $city = $this->whenLoaded('city');

        return [
            'id' => $this->id,
            'name' => $this->name,
            'city' => new CityResource($city),
            'web_site' => $this->web_site,
            'email' => $this->email,
            'phone' => $this->phone,
            'address' => $this->address,
        ];
    }
}
