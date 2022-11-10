<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DataProviderController;

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

// Route::get('/', function () {
//     return view('welcome');
// });

/** Data providers */
Route::resource('', DataProviderController::class, [
        'names' => [
            'store' => 'data-provider.create',
            'show' => 'data-provider.show',
            'update' => 'data-provider.update',
            'destroy' => 'data-provider.delete'
        ]
    ])
    ->only([
        'index', 'store', 'show', 'update', 'destroy'
    ]);
Route::get('/{id}', [DataProviderController::class, 'show']);
Route::delete('/{id}', [DataProviderController::class, 'destroy']);
Route::put('/{id}', [DataProviderController::class, 'update']);
