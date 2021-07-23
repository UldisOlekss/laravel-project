<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CurrencyController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return redirect('/currencies');
});

Route::get('/currencies', [CurrencyController::class, 'index'])->name('currency.index');

Route::get('/currencies/{currency}', [CurrencyController::class, 'show'])->name('currency.show');
