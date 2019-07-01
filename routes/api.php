<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('login', 'PassportController@login');
Route::post('register', 'PassportController@register');

Route::middleware(['auth:api'])->group(function () {
    Route::get('user', 'PassportController@details');

    // Get a transaction
    Route::get('transaction/{transaction_id}/{customer_id}', 'TransactionController@view')->name('viewTransaction');

    // Get all transactions for a customer
    Route::get('transaction', 'TransactionController@show')->name('showTransaction');

    // Create a transaction
    Route::post('transaction/create', 'TransactionController@create')->name('createTransaction');

    // Update a transaction
    Route::put('transaction/{transaction_id}/update', 'TransactionController@update');

    // Delete a transaction
    Route::delete('transaction/{transaction_id}/delete', 'TransactionController@delete');
});