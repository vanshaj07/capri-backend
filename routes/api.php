<?php

use App\Http\Controllers\Api\BlogController;
use App\Http\Controllers\Api\FaqController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Api\TestimonialController;
use Illuminate\Support\Facades\Route;

Route::post('/login', [AuthController::class, 'login']);


Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('testimonials', TestimonialController::class);
    Route::apiResource('faqs', FaqController::class);
    Route::apiResource('blogs', BlogController::class);
});
