<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/
/*
Route::get('/distributors/{id}', function (int $id) {
    return new DistributorResource(Distributor::find($id));
});
*/
Route::get('/cities', [\App\Http\Controllers\CityController::class, 'list']);
Route::get('/cities/{city}', [\App\Http\Controllers\CityController::class, 'show']);
Route::get('/distributors/{distributor}', [\App\Http\Controllers\DistributorController::class, 'show']);
Route::get('/regions/{region}', [\App\Http\Controllers\RegionController::class, 'show']);
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
