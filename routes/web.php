<?php

use App\Http\Controllers\ArchiveController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\MenuItemsController;
use App\Http\Controllers\OrdersController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\StaffController;
use Illuminate\Support\Facades\Route;


//login
Route::get('/', function () {
    return view('login');
})->name('login');

Route::get(
    '/login-auth',
    [LoginController::class, 'login']
)->name('login-auth');


Route::get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard');

Route::get('/change_password', function () {
    return view('change-password');
})->name('change-password');

//menu items
Route::get(
    '/menu_items',
    [MenuItemsController::class, 'paginatedData']
)->name('menu-items');

route::get(
    '/add_menu_items',
    [CategoryController::class, 'addMenu']
)->name('add-menu-items');

Route::delete(
    '/menu_items/{key}',
    [MenuItemsController::class, 'destroy']
)->name('delete-menu-item');

Route::post(
    '/store_menu_items',
    [MenuItemsController::class, 'store']
)->name('menu-items.store');

//category
Route::get(
    '/categories',
    [CategoryController::class, 'paginatedData']
)->name('categories.index');

route::post(
    '/categories-add',
    [CategoryController::class, 'store']
)->name('categories.store');

Route::delete(
    '/categories/{id}',
    [CategoryController::class, 'destroy']
)->name('categories.delete');

//Orders
route::get(
    '/orders',
    [OrdersController::class, 'paginatedData']
)->name('orders.paginated');

//Role
route::get(
    '/administration/role',
    [RoleController::class, 'index']
)->name('roles.index');

Route::get('/roles/add', function () {
    return view('roles_update_add');
})->name('roles.create');

Route::post(
    '/roles',
    [RoleController::class, 'store']
)->name('roles.store');

Route::get(
    '/roles/{role}/edit',
    [RoleController::class, 'edit']
)->name('roles.edit');

Route::delete(
    '/roles/{role}/destroy',
    [RoleController::class, 'destroy']
)->name('roles.destroy');

Route::get(
    '/roles/{role}',
    [RoleController::class, 'update']
)->name('roles.update');

//Staff
route::get(
    '/administration/staff',
    [StaffController::class, 'index']
)->name('staff.index');

Route::post('/administration/staff/store', [StaffController::class, 'store'])->name('staff.store');

route::get(
    '/administration/staff/create',
    [RoleController::class, 'addStaff']
)->name('staff.create');

//Archives
Route::get(
    '/archive',
    [ArchiveController::class, 'paginatedData']
)->name('archive.index');

Route::delete('/archive-menu-item/{id}', [MenuItemsController::class, 'archiveMenuItem'])->name('menu-items.archive');
