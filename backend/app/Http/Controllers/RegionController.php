<?php

namespace App\Http\Controllers;

use App\Http\Resources\RegionResource;
use App\Models\Region;
use Illuminate\Http\JsonResponse;

class RegionController extends Controller
{
    public function show(Region $region): JsonResponse
    {
        return (new RegionResource($region->loadMissing(['cities', 'distributors'])))->response();
    }
}
