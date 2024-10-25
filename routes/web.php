<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\VozidlaController;
use App\Http\Controllers\PlanController;
use App\Http\Controllers\ZavadyController;
use App\Http\Controllers\WelcomeController;
use App\Http\Controllers\ZaznamyController;
use App\Http\Controllers\UzivateleController;
use App\Http\Controllers\DispecinkController;
use App\Http\Controllers\SpojeController;
use App\Http\Controllers\LinkyController;
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

Route::middleware(['auth', 'admin'])->group( function (){
    Route::get('/uzivatele', [UzivateleController::class, 'index'])->name('uzivatele');
    Route::delete('/uzivatele', [UzivateleController::class, 'deleteUzivatel'])->name('uzivatel.delete');
    Route::get('/uzivatel-edit/{id}', [UzivateleController::class, 'editUzivatel'])->name('uzivatel.edit');
    Route::post('/uzivatele', [UzivateleController::class, 'saveUzivatel'])->name('uzivatel.save');
});

Route::middleware(['auth', 'spravce'])->group( function (){
    Route::get('/linky', [LinkyController::class, 'index'])->name('linky');
    Route::post('/linky', [LinkyController::class, 'createLinka'])->name('linka.create');
    Route::post('/getLinkyData', [LinkyController::class, 'getLinkyData'])->name('getLinkyData');
    Route::delete('/linky', [LinkyController::class, 'deleteLinka'])->name('linka.delete');
    Route::delete('/linky/zastavka', [LinkyController::class, 'deleteZastavka'])->name('zastavka.delete');
    Route::post('/linky/zastavka', [LinkyController::class, 'addZastavka'])->name('zastavka.add');
    Route::get('/spoje', [SpojeController::class, 'index'])->name('spoje');
    Route::post('/spoje', [SpojeController::class, 'createSpoj'])->name('spoj.create');
    Route::post('/getSpojeData', [SpojeController::class, 'getSpojeData'])->name('getSpojeData');
    Route::delete('/spoje', [SpojeController::class, 'deleteSpoj'])->name('spoj.delete');
    Route::get('/spoj-edit/{id}', [SpojeController::class, 'editSpoj'])->name('spoj.edit');
    Route::post('/spoje/save', [SpojeController::class, 'saveSpoj'])->name('spoj.save');
    Route::get('/vozidla', [VozidlaController::class, 'index'])->name('vozidla');
    Route::post('/getVozidloData', [VozidlaController::class, 'getVozidloData'])->name('getVozidloData');
    Route::post('/vozidla/vozidlo', [VozidlaController::class, 'createVozidlo'])->name('vozidlo.create');
    Route::post('/vozidla/pozadavek', [VozidlaController::class, 'createPozadavek'])->name('pozadavek.create');
    Route::delete('/vozidla/vozidlo', [VozidlaController::class, 'deleteVozidlo'])->name('vozidlo.delete');
});

Route::middleware(['auth', 'technik'])->group( function (){
    Route::get('/zaznamy', [ZaznamyController::class, 'index']);
    Route::post('/zaznamy', [ZaznamyController::class, 'createZaznam'])->name('zaznam.create');
});

Route::middleware(['auth', 'dispecer'])->group( function (){
    Route::get('/dispecink', [DispecinkController::class, 'index'])->name('dispecink');
    Route::post('/getDispecinkData', [DispecinkController::class, 'getDispecinkData'])->name('getDispecinkData');
    Route::post('/addVozidlo', [DispecinkController::class, 'addVozidlo'])->name('vozidlo.add');
    Route::post('/addRidic', [DispecinkController::class, 'addRidic'])->name('ridic.add');
});

Route::middleware(['auth', 'ridic'])->group( function (){
    Route::get('/plan', [PlanController::class, 'index']);
    Route::get('/zavady', [ZavadyController::class, 'index'])->name('zavady');
    Route::post('/zavady', [ZavadyController::class, 'createZavada'])->name('zavada.create');
});

Route::get('/', [WelcomeController::class, 'index']);
Route::post('/', [WelcomeController::class, 'findSpoj'])->name('spoj.find');
Route::get('/spoj-detail/{id}', [WelcomeController::class, 'getDetail'])->name('spoj.detail');
Route::get('/ucet', function () { return view('account'); });

Route::get('/is', function () {
    return view('is');
})->middleware(['auth', 'verified'])->name('is');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
