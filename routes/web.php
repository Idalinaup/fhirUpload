<?php

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

Route::get('/', function () {
    return view('welcome');
});

//Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
use App\Http\Controllers\ArtifactController;


Route::get('/artifacts', [ArtifactController::class, 'index'])->name('artifacts.index');
Route::post('/artifacts/upload', [ArtifactController::class, 'upload'])->name('artifacts.upload');
Route::post('/artifacts/generate', [ArtifactController::class, 'generateForm'])->name('artifacts.generate');
Route::post('/artifacts/generate/FHIRQuestionnaire', [ArtifactController::class, 'parseFHIRQuestionnaire'])->name('artifacts.generateView');
Route::get('/artifacts/generate/test', [ArtifactController::class, 'Testparse'])->name('artifacts.Testparse');
