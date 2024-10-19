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

Route::get(
    '/edit-menu-item/{menuId}',
    [MenuItemsController::class, 'edit']
)->name('menu-items.edit');

Route::put(
    '/edit-menu-item/{menuId}',
    [MenuItemsController::class, 'update']
)->name('menu-items.update');


Route::post(
    '/store_menu_items',
    [MenuItemsController::class, 'store']
)->name('menu-items.store');

Route::post(
    '/menu-items/archive/{menuId}',
    [MenuItemsController::class, 'archive']
)->name('menu-items.archive');

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
    '/orders/pending',
    [OrdersController::class, 'showPendingOrders']
)->name('pending_orders.paginated');

route::get(
    '/orders/{orderId}/order_details',
    [OrdersController::class, 'showOrderDetails']
)->name('order_details.show');

route::get(
    '/orders/confirmed',
    [OrdersController::class, 'showConfirmedOrders']
)->name('confirmed_orders.paginated');

route::get(
    '/orders/on_preparation',
    [OrdersController::class, 'showPreparingOrders']
)->name('on_preparation_orders.paginated');

route::get(
    '/orders/for_delivery',
    [OrdersController::class, 'showforDeliveryOrders']
)->name('for_delivery_orders.paginated');

route::get(
    '/orders/delivered',
    [OrdersController::class, 'showDeliveredOrders']
)->name('delivered_orders.paginated');

Route::patch(
    '/orders/{orderId}/confirm',
    [OrdersController::class, 'confirmOrder']
)->name('orders.confirm');

Route::patch(
    '/orders/{orderId}/prepare',
    [OrdersController::class, 'prepareOrder']
)->name('orders.prepare');

Route::patch(
    '/orders/{orderId}/fordelivery',
    [OrdersController::class, 'forDeliveryOrder']
)->name('orders.fordelivery');

Route::patch(
    '/orders/{orderId}/delivered',
    [OrdersController::class, 'deliveredOrder']
)->name('orders.delivered');

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
route::delete(
    '/administration/staff/remove/{$id}',
    [StaffController::class, 'destroy']
)->name('staff.destroy');

//Archives
Route::get(
    '/archive',
    [ArchiveController::class, 'paginatedData']
)->name('archive.index');

Route::delete('/archive-menu-item/{id}', [MenuItemsController::class, 'archiveMenuItem'])->name('menu-items.archive');
