<?php

use App\Http\Controllers\BillController;
use App\Http\Controllers\ContractController;
use App\Http\Controllers\PdfController;
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

// fix "Route [login] not defined."
Route::get('/login', function () {
    return redirect(route('filament.admin.auth.login'));
})->name('login');

Route::middleware('auth')->group(function () {
    Route::get('pdf/{order}', PdfController::class)->name('pdf');
    Route::get('bill', [BillController::class, 'billing'])->name('bill');
    Route::get('billing_room', [BillController::class, 'billing_room'])->name('billing_room');
});
