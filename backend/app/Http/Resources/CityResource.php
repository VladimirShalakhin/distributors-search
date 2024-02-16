<?php

namespace App\Http\Resources;

use App\Models\City;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin City */
class CityResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $region = $this->whenLoaded('region');

        return [
            'id' => $this->id,
            'name' => $this->name,
            'region' => new RegionResource($region),
            'distributors' => DistributorResource::collection($this->whenLoaded('distributors')),
            'numberOfDistributors' => $this->whenLoaded('distributors', function () {
                return $this->distributors->count();
            }),
        ];
    }
}
