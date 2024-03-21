<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DietterController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/
Route::get('/dietter', [DietterController::class, 'index']);
Route::post('/kCalInput', [DietterController::class, 'eatMasterStore'])->name('kCalInput.submit');
Route::post('/eatingInput', [DietterController::class, 'eatingHistoryStore'])->name('eatingInput.submit');
Route::post('/judge', [DietterController::class, 'judge'])->name('judge.submit');


Route::get('/', function () {
    return view('welcome');
});
