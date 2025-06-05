<?php

use App\Http\Controllers\Catalogos\AlumnoGrupoController;
use App\Http\Controllers\Catalogos\CiclosController;
use App\Http\Controllers\Catalogos\ConceptosDePagoController;
use App\Http\Controllers\Catalogos\FamiliaController;
use App\Http\Controllers\Catalogos\FamiliaElementController;
use App\Http\Controllers\Catalogos\FamiliaRegistroFiscalController;
use App\Http\Controllers\Catalogos\GrupoNivelController;
use App\Http\Controllers\Catalogos\GruposController;
use App\Http\Controllers\Catalogos\NivelesController;
use App\Http\Controllers\Catalogos\RegimenFiscalController;
use App\Http\Controllers\Catalogos\RegistroFiscalController;
use App\Http\Controllers\Catalogos\UsoCFDIController;
use App\Http\Controllers\User\BulkPermissionsController;
use App\Http\Controllers\User\BulkUserRolesController;
//use App\Http\Controllers\User\UserRoleController;
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

    // USERS ALUMNOS
    Route::get('users.alumnos', [UserController::class, 'alumnosIndex'])->name('users.alumnos');


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

// USO CFDI
    Route::get('usocfdi_list', [UsoCFDIController::class, 'index'])->name('usocfdi.index')->middleware('auth');
    Route::post('usocfdi.store', [UsoCFDIController::class, 'store'])->name('usocfdi.store');
    Route::put('usocfdi.update/{UsoCFDI}', [UsoCFDIController::class, 'update'])->name('usocfdi.update');
    Route::delete('usocfdi.delete/{usocfdiId}', [UsoCFDIController::class, 'destroy'])->name('usocfdi.delete');

// CONCEPTOS DE PAGO
    Route::get('conceptodepago_list', [ConceptosDePagoController::class, 'index'])->name('conceptodepago.index')->middleware('auth');
    Route::post('conceptodepago.store', [ConceptosDePagoController::class, 'store'])->name('conceptodepago.store');
    Route::put('conceptodepago.update/{ConceptoDePago}', [ConceptosDePagoController::class, 'update'])->name('conceptodepago.update');
    Route::delete('conceptodepago.delete/{conceptodepagoId}', [ConceptosDePagoController::class, 'destroy'])->name('conceptodepago.delete');

    // CICLOS
    Route::get('ciclos', [CiclosController::class, 'index'])->name('ciclos.index')->middleware('auth');
    Route::post('ciclo.store', [CiclosController::class, 'store'])->name('ciclo.store');
    Route::put('ciclo.update', [CiclosController::class, 'update'])->name('ciclo.update');
    Route::delete('ciclo.delete/{cicId}', [CiclosController::class, 'destroy'])->name('ciclo.delete');

    // NIVELES
    Route::get('niveles', [NivelesController::class, 'index'])->name('niveles.index')->middleware('auth');
    Route::post('nivel.store', [NivelesController::class, 'store'])->name('nivel.store');
    Route::put('nivel.update', [NivelesController::class, 'update'])->name('nivel.update');
    Route::delete('nivel.delete/{nivId}', [NivelesController::class, 'destroy'])->name('nivel.delete');

    // GRUPOS
    Route::get('grupos', [GruposController::class, 'index'])->name('grupos.index')->middleware('auth');
    Route::post('grupo.store', [GruposController::class, 'store'])->name('grupo.store');
    Route::put('grupo.update', [GruposController::class, 'update'])->name('grupo.update');
    Route::delete('grupo.delete/{gruId}', [GruposController::class, 'destroy'])->name('grupo.delete');

    Route::get('grupos.en.nivel/{combo1_id}/{combo2_id}', [GrupoNivelController::class, 'GruposEnNivel'])->name('grupos.en.nivel');
    Route::post('grupos.agregar.de.nivel', [GrupoNivelController::class, 'agregarItem'])->name('grupos.agregar.de.nivel');
    Route::post('grupos.quitar.de.nivel', [GrupoNivelController::class, 'quitarItem'])->name('grupos.quitar.de.nivel');

    Route::get('alumnos.a.grupo/{combo1_id}/{combo2_id}/{combo3_id}', [AlumnoGrupoController::class, 'index'])->name('alumnos.a.grupo');
    Route::post('alumnos.agregar.a.grupo', [AlumnoGrupoController::class, 'agregarItem'])->name('alumnos.agregar.a.grupo');
    Route::post('alumnos.quitar.a.grupo', [AlumnoGrupoController::class, 'quitarItem'])->name('alumnos.quitar.a.grupo');

    // FAMILIAS
    Route::get('familias', [FamiliaController::class, 'index'])->name('familias.index')->middleware('auth');
    Route::post('familia.store', [FamiliaController::class, 'store'])->name('familia.store');
    Route::put('familia.update', [FamiliaController::class, 'update'])->name('familia.update');
    Route::delete('familia.delete/{famId}', [FamiliaController::class, 'destroy'])->name('familia.delete');

    // FAMILIA - INTEGRANTES
    Route::get('familiaElements/{familia}', [FamiliaElementController::class, 'index'])->name('familiaElements.index')->middleware('auth');
    Route::post('familiaElement.store', [FamiliaElementController::class, 'store'])->name('familiaElement.store');
    Route::delete('familiaElement.delete/{famEleId}', [FamiliaElementController::class, 'destroy'])->name('familiaElement.delete');

    // FAMILIA - INTEGRANTES
    Route::get('familiaRegFis/{familia}', [FamiliaRegistroFiscalController::class, 'index'])->name('familiaRegFis.index')->middleware('auth');
    Route::post('familiaRegFis.store', [FamiliaRegistroFiscalController::class, 'store'])->name('familiaRegFis.store');
    Route::delete('familiaRegFis.delete/{famRegFisId}', [FamiliaRegistroFiscalController::class, 'destroy'])->name('familiaRegFis.delete');



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
