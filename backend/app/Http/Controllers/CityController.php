<?php

namespace App\Http\Controllers;

use App\Http\Requests\CityRequest;
use App\Http\Resources\CityResource;
use App\Models\City;
use Illuminate\Http\JsonResponse;

class CityController extends Controller
{
    public function show(City $city): JsonResponse
    {
        return (new CityResource($city->loadMissing(['distributors', 'region'])))->response();
    }

    public function list(CityRequest $request): JsonResponse
    {
        $name = $request->input('name');

        /** @var City $cities */
        $cities = City::whereRaw('? % name', [$name])->get();
        $cities->loadMissing(['distributors', 'region']);

        return CityResource::collection($cities)->response();
    }
}
