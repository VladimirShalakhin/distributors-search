<?php

namespace App\Http\Resources;

use App\Models\Region;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin Region */
class RegionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->region_name,
            'cities' => $this->whenLoaded('cities', function () {
                return $this->cities->transform(fn ($city) => ['id' => $city->id, 'name' => $city->name]);
            }),
            'numberOfDistributors' => $this->whenLoaded('distributors', function () {
                return $this->distributors->count();
            }),
            'distributors' => DistributorResource::collection($this->whenLoaded('distributors')),
        ];
    }
}
