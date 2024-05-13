<?php

use App\Http\Controllers\DietterController;
use App\Http\Controllers\TSI\TSIController;
use App\Http\Controllers\Persona\PersonarsController;
use Illuminate\Support\Facades\Route;

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

Route::get('/tsi', [TSIController::class, 'index']);
Route::post('/tsi/search', [TSIController::class, 'search'])->name('search.submit');

Route::get('/personars', [PersonarsController::class, 'index']);
Route::post('/personars/add', [PersonarsController::class, 'store'])->name('add.submit');

Route::get('/', function () {
    return view('welcome');
});
