<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TokenizerController;

Route::get('/', [TokenizerController::class,'index']);
Route::post('/tokenize', [TokenizerController::class, 'tokenize'])->name('tokenize');
