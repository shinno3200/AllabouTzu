<?php
use App\Http\Controllers\DietterController;
use App\Http\Controllers\TSI\TSIController;
use App\Http\Controllers\Persona\PersonarsController;
use App\Http\Controllers\ProfileController;
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

Route::get('/', 'PageController@home')->name('home');
Route::get('/profile', 'PageController@profile')->name('profile');
Route::get('/songs', 'PageController@songs')->name('songs');
Route::get('/movie', 'PageController@movie')->name('movie');
Route::get('/goods', 'PageController@goods')->name('goods');
Route::get('/photo', 'PageController@photo')->name('photo');


Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

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