<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use app\Http\Controllers\UserController;
Route::get('/', function () {
    return view('welcome');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});

Route::middleware(['auth'])->group(function () {

    // Display all categories
    Route::get('/admin/allcategories', [
        AdminController::class,
        'allCategories'
    ]);

    // Show add category form
    Route::get('/admin/addcategory', [
        AdminController::class,
        'showAddCategory'
    ]);

    // Save new category
    Route::post('/admin/addcategory', [
        AdminController::class,
        'storeCategory'
    ]);

    // Show edit category form
    Route::get('/admin/editecategry/{id}', [
        AdminController::class,
        'editCategory'
    ]);

    // Update category
    Route::put('/admin/updatecategory/{id}', [
        AdminController::class,
        'updateCategory'
    ]);

    // Delete category
    Route::delete('/admin/deletecategory/{id}', [
        AdminController::class,
        'deleteCategory'
    ]);

 Route::get('/admin/allproducts', [AdminController::class, 'allProducts']);

    Route::get('/admin/addproduct', [AdminController::class, 'showAddProduct']);
    Route::post('/admin/addproduct', [AdminController::class, 'storeProduct']);

    Route::get('/admin/editproduct/{id}', [AdminController::class, 'editProduct']);
    Route::put('/admin/updateproduct/{id}', [AdminController::class, 'updateProduct']);

    Route::delete('/admin/deleteproduct/{id}', [AdminController::class, 'deleteProduct']);

        Route::get('/admin/settings', [AdminController::class, 'showSettings']);
    Route::post('/admin/settings', [AdminController::class, 'updateSettings']); 
Route::post('/admin/settings/reset', [AdminController::class, 'resetSettings']);

});