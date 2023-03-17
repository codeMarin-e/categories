<?php

use App\Http\Controllers\Admin\CategoryController;
use App\Models\Category;

Route::group([
    'controller' => CategoryController::class,
    'middleware' => ['auth:admin', 'can:view,'.Category::class],
    'as' => 'categories.', //naming prefix
    'prefix' => 'categories', //for routes
], function() {
    Route::get('', 'index')->name('index');
    Route::post('', 'store')->name('store')->middleware('can:create,'.Category::class);
    Route::get('create', 'create')->name('create')->middleware('can:create,'.Category::class);
    Route::get('{chCategory}/edit', 'edit')->name('edit');
    Route::get('{chCategory}/move/{direction}', "move")->name('move')->middleware('can:update,chCategory');

    // @HOOK_ROUTES_MODEL

    Route::get('{chCategory}', 'edit')->name('show');
    Route::patch('{chCategory}', 'update')->name('update')->middleware('can:update,chCategory');
    Route::delete('{chCategory}', 'destroy')->name('destroy')->middleware('can:delete,chCategory');

    // @HOOK_ROUTES
});
