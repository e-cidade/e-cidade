<?php

use App\Http\Controllers\AuditController;
use App\Http\Controllers\IptuFotosController;
use Illuminate\Support\Facades\Route;


Route::group(['middleware' => ['legacySession', 'authEcidadeUser', 'auth.basic'], 'prefix' => 'web'], function () {
    Route::get('/welcome', function () {
        return view('modelo');
    })->name('welcome');
    Route::post('iptufoto/upload', [IptuFotosController::class, 'upload'])->name('iptufotos-upload');
    Route::get('iptufoto/list/{matric}', [IptuFotosController::class, 'list'])->name('iptufotos-list');
    Route::post('iptufoto/update', [IptuFotosController::class, 'update'])->name('iptufotos-update');
    Route::delete('iptufoto/delete/{id}/{matric}', [IptuFotosController::class, 'delete'])->name('iptufotos-delete');
    Route::get('iptufoto/show/{id}', [IptuFotosController::class, 'show'])->name('iptufotos-show');

    //audit
    Route::get('/audits', [AuditController::class, 'index'])->name('audits.index');
    
    require base_path('routes/modules/patrimonial/patrimonial.php');
});

Route::fallback(function () {
    abort(404);
});
