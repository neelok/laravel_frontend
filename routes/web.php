<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\UrlScrapper;
use App\Http\Controllers\DataTableController;
use App\Http\Controllers\Delete2Controller;
use App\Http\Controllers\CommunicateController;
use App\Http\Controllers\ParsingController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|DataTableController
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/', [AuthenticatedSessionController::class, 'create']);
Route::get('/test', [UrlScrapper::class, 'callfordata']);
Route::get('/data/{id}', function($id){
    $res = (new DataTableController)->fetchdata($id);
    // return $id;
    return $res;
})->name('datatable');



Route::get('/put', [CommunicateController::class, 'sendgooddeals']);
Route::get('/test_trims', [ParsingController::class, 'bmyo']);


Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');

require __DIR__.'/auth.php';
