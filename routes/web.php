<?php

use App\Http\Controllers\Catalogos\FamiliaController;
use App\Http\Controllers\Catalogos\RegimenFiscalController;
use App\Http\Controllers\Catalogos\RegistroFiscalController;
use App\Http\Controllers\User\BulkPermissionsController;
use App\Http\Controllers\User\BulkUserRolesController;
use App\Http\Controllers\User\UserRoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

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

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/about', fn () => Inertia::render('About'))->name('about');

    Route::get('users', [UserController::class, 'index'])->name('users.index');
    Route::post('users.store', [UserController::class, 'store'])->name('users.store');
    Route::put('users.update/{user}', [UserController::class, 'update'])->name('users.update');
    Route::delete('users.delete/{user}', [UserController::class, 'destroy'])->name('users.delete');

    // REGISTROS FISCALES
    Route::get('registros_fiscales_list', [RegistroFiscalController::class, 'index'])->name('regfis.index')->middleware('auth');
    Route::post('registros_fiscales.store', [RegistroFiscalController::class, 'store'])->name('regfis.store');
    Route::put('registros_fiscales.update/{RegFis}', [RegistroFiscalController::class, 'update'])->name('regfis.update');
    Route::delete('registros_fiscales.delete/{regfisId}', [RegistroFiscalController::class, 'destroy'])->name('regfis.delete');

// REGIMENES FISCALES
    Route::get('regimenes_fiscales_list', [RegimenFiscalController::class, 'index'])->name('regifis.index')->middleware('auth');
    Route::post('regimenes_fiscales.store', [RegimenFiscalController::class, 'store'])->name('regifis.store');
    Route::put('regimenes_fiscales.update/{RegFis}', [RegimenFiscalController::class, 'update'])->name('regifis.update');
    Route::delete('regimenes_fiscales.delete/{regifisId}', [RegimenFiscalController::class, 'destroy'])->name('regifis.delete');

    // FAMILIAS
    Route::get('familias_list', [FamiliaController::class, 'index'])->name('familias.index')->middleware('auth');
    Route::post('familia.store', [FamiliaController::class, 'store'])->name('familia.store');
    Route::put('familia.update', [FamiliaController::class, 'update'])->name('familia.update');
    Route::delete('familia.delete/{famId}', [FamiliaController::class, 'destroy'])->name('familia.delete');



    Route::put('alumnos/', [UserController::class, 'alumnosIndex'])->name('alumnos.index');

// Vista principal donde se muestra el componente de asignación masiva de roles
    Route::get('/bulk-roles', [BulkUserRolesController::class, 'edit'])->name('bulk.roles.edit');
// Rutas para asignar o quitar roles parcialmente
    Route::post('/bulk-roles/assign-partial', [BulkUserRolesController::class, 'assignPartial'])->name('bulk.roles.assignPartial');
    Route::post('/bulk-roles/remove-partial', [BulkUserRolesController::class, 'removePartial'])->name('bulk.roles.removePartial');

// Vista principal donde se muestra el componente de asignación masiva de permisos
    Route::get('/bulk-permisos', [BulkPermissionsController::class, 'edit'])->name('bulk.permisos.edit');
// Rutas para asignar o quitar roles parcialmente
    Route::post('/bulk-permisos/assign-partial', [BulkPermissionsController::class, 'assignPartial'])->name('bulk.permisos.assignPartial');
    Route::post('/bulk-permisos/remove-partial', [BulkPermissionsController::class, 'removePartial'])->name('bulk.permisos.removePartial');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
