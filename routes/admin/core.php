<?php
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\OrderController;


Route::controller(CustomerController::class)->middleware('can:view_customer')->prefix('customer')->name('customer.')->group(function () {
    Route::get('/', 'index')->name('index');
    Route::get('get-data', 'getData')->name('getdata');
    Route::post('create', 'createData')->name('create');
    Route::post('{id}/update', 'updateData')->name('update');
    Route::delete('{id}/delete', 'deleteData')->name('delete');
});

Route::controller(OrderController::class)->middleware('can:view_order')->prefix('order')->name('order.')->group(function () {
    Route::get('/', 'index')->name('index');
    Route::get('get-data', 'getData')->name('getdata');
    Route::get('create', 'createPage')->name('createPage');
    Route::post('create', 'createData')->name('create');
    Route::post('{id}/update', 'updateData')->name('update');
    Route::delete('{id}/delete', 'deleteData')->name('delete');
});