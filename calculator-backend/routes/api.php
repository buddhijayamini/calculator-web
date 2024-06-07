<?php

use App\Http\Controllers\API\CalculatorController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

//post route for data save in db
Route::post('/calculate', [CalculatorController::class, 'store']);
