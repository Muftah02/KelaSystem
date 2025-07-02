<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AssistantController;

Route::middleware('api')->get('/user', function (Request $request) {
    return $request->user();
});

// مسار المساعد الذكي - لا يحتاج middleware api
Route::post('/ask', [AssistantController::class, 'handle'])->middleware('web');
