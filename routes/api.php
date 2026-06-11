<?php

use App\Http\Controllers\Api\PakasirController;
use Illuminate\Support\Facades\Route;

Route::post('/pakasir-webhook', [PakasirController::class, 'webhook']);
Route::get('/pakasir-redirect', [PakasirController::class, 'redirectBack']);
