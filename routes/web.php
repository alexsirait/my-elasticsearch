<?php

use App\Http\Controllers\ElasticsearchController;
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

Route::get('/elasticsearch', [ElasticsearchController::class, 'index']);
Route::get('/elasticsearch_data', [ElasticsearchController::class, 'data']);
Route::get('/elasticsearch_insert', [ElasticsearchController::class, 'insert']);
Route::get('/elasticsearch_delete', [ElasticsearchController::class, 'delete']);
Route::get('/elasticsearch_update', [ElasticsearchController::class, 'update']);
Route::get('/elasticsearch_search', [ElasticsearchController::class, 'search']);
Route::get('/elasticsearch_reindex', [ElasticsearchController::class, 'reindex']);
