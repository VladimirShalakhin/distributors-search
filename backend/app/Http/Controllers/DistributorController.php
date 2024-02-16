<?php

namespace App\Http\Controllers;

use App\Http\Resources\DistributorResource;
use App\Models\Distributor;
use Illuminate\Http\JsonResponse;

class DistributorController extends Controller
{
    public function show(Distributor $distributor): JsonResponse
    {
        return (new DistributorResource($distributor->loadMissing(['city'])))->response();
    }
}
