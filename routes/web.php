<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TokenizerController;
use App\Http\Controllers\WordController;

Route::get('/', [TokenizerController::class,'index'])->name('home');
Route::post('/tokenize', [TokenizerController::class, 'tokenize'])->name('tokenize')->middleware('clear.session');
Route::post('/save-word', [WordController::class, 'saveWord'])->name('save.word');
Route::delete('/delete-word/{word}', [WordController::class, 'deleteWord'])->name('delete.word');
Route::delete('/delete-my-word/{word}', [WordController::class, 'deleteMyWord'])->name('delete.my.word');
Route::get('/my-words', [WordController::class, 'showSavedWords'])->name('my.words');
route::get('/mcq-test', [WordController::class, 'startMcqTest'])->name('mcq.test');
route::post('/submit-mcq', [WordController::class, 'submitMcq'])->name('submitMcq');
Route::post('/stop-test', [WordController::class, 'stopTest'])->name('stopTest');
Route::get('/saved', [TokenizerController::class, 'showTokenizedWords'])->name('tokenize.show');
Route::get('/deleted', [TokenizerController::class, 'showTokenizedWords'])->name('tokenize.show');