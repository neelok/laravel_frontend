<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\UrlScrapper;
use App\Http\Controllers\DataTableController;

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

// This is my sign in page
Route::get('/', [AuthenticatedSessionController::class, 'create']);


// This is the for test scraping call 
// Route::get('/test', [ParsingController::class, 'cleandb']); 

// This one fills up the data for live view ... return the data for the ajax call
Route::get('/data/{id}', function($id){
    $res = (new DataTableController)->fetchdata($id);
    // return $id;
    return $res;
});

// after sign in this page shows up
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');

require __DIR__.'/auth.php';
