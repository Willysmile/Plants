<?php

use App\Http\Controllers\PlantController;
use App\Http\Controllers\PhotoController;
use App\Http\Controllers\WateringHistoryController;
use App\Http\Controllers\FertilizingHistoryController;
use App\Http\Controllers\RepottingHistoryController;
use App\Http\Controllers\PlantHistoryController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\FertilizerTypeController;
use App\Http\Controllers\BackupController;
use Illuminate\Support\Facades\Route;

// Home redirect
Route::get('/', function () {
    return auth()->check() ? redirect()->route('plants.index') : redirect()->route('login');
})->name('home');

// Dashboard redirect to plants
Route::get('/dashboard', function () {
    return redirect()->route('plants.index');
})->middleware(['auth', 'verified'])->name('dashboard');

// Routes protégées par authentification
Route::middleware(['auth', 'verified'])->group(function () {
    // Route pour les plantes archivées
    Route::get('plants/archived', [PlantController::class, 'archived'])->name('plants.archived');

    // Routes pour archiver et restaurer les plantes
    Route::post('plants/{plant}/archive', [PlantController::class, 'archive'])->name('plants.archive');
    Route::post('plants/{plant}/restore', [PlantController::class, 'restore'])->name('plants.restore');

    // Ressource plants
    Route::resource('plants', PlantController::class);

    // Routes AJAX
    Route::get('plants/{plant}/modal', [PlantController::class, 'modal'])->name('plants.modal');
    Route::get('plants/{plant}/histories', [PlantController::class, 'histories'])->name('plants.histories');
    Route::post('plants/generate-reference', [PlantController::class, 'generateReferenceAPI'])->name('plants.generate-reference');
    Route::post('fertilizer-types', [FertilizerTypeController::class, 'store'])->name('fertilizer-types.store');

    // Gestion des photos
    Route::patch('plants/{plant}/photos/{photo}', [PhotoController::class, 'update'])->name('plants.photos.update');
    Route::delete('plants/{plant}/photos/{photo}', [PhotoController::class, 'destroy'])->name('plants.photos.destroy');

    // Routes imbriquées pour les historiques
    Route::resource('plants.watering-history', WateringHistoryController::class);
    Route::resource('plants.fertilizing-history', FertilizingHistoryController::class);
    Route::resource('plants.repotting-history', RepottingHistoryController::class);
    Route::resource('plants.histories', PlantHistoryController::class);

    // Gestion des types d'engrais
    Route::resource('fertilizer-types', FertilizerTypeController::class);

    // Routes pour les paramètres
    Route::get('settings', [SettingsController::class, 'index'])->name('settings.index');
    Route::put('settings', [SettingsController::class, 'update'])->name('settings.update');
    Route::get('settings/references', [SettingsController::class, 'references'])->name('settings.references');

    // Routes pour les sauvegardes et exports (admin-only)
    Route::middleware(['admin'])->prefix('settings/backups')->name('backups.')->group(function () {
        Route::get('/', [BackupController::class, 'index'])->name('index');
        Route::post('/export', [BackupController::class, 'export'])->name('export');
        Route::get('/download/{filename}', [BackupController::class, 'download'])->name('download');
        Route::delete('/{filename}', [BackupController::class, 'delete'])->name('delete');
        
        // Import routes
        Route::post('/preview', [BackupController::class, 'importPreview'])->name('preview');
        Route::post('/import', [BackupController::class, 'import'])->name('import');
        Route::get('/info', [BackupController::class, 'getBackupInfo'])->name('info');
        
        // Reset & Recovery routes
        Route::get('/deleted-items', [BackupController::class, 'getDeletedItems'])->name('deleted-items');
        Route::post('/reset-preview', [BackupController::class, 'resetPreview'])->name('reset-preview');
        Route::post('/reset', [BackupController::class, 'reset'])->name('reset');
        Route::post('/recover', [BackupController::class, 'recover'])->name('recover');
        Route::get('/audit-logs', [BackupController::class, 'getAuditLogs'])->name('audit-logs');
    });
});

require __DIR__.'/auth.php';
