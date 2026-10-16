<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\UserController;



Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});


Route::get('/checkout', [UserController::class, 'checkout']);
Route::post('/checkout', [UserController::class, 'placeOrder']);


// Shopping Cart
Route::get('/cart', [UserController::class, 'cart']);

Route::post('/cart/add/{id}', [UserController::class, 'addToCart']);

Route::patch('/cart/update/{id}', [UserController::class, 'updateCart']);

Route::delete('/cart/remove/{id}', [UserController::class, 'removeFromCart']);

Route::post('/cart/clear', [UserController::class, 'clearCart']);

Route::get('/contact', [UserController::class, 'contact']);
Route::post('/contact', [UserController::class, 'sendContact']);

Route::get('/', [UserController::class, 'home']);

Route::get('/all-categories', [UserController::class, 'allCategories']);

Route::get('/all-products', [UserController::class, 'allProducts']);

Route::get('/about', [UserController::class, 'about']);

Route::get('/contact', [UserController::class, 'contact']);
 

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