<?php

use App\Http\Controllers\PlantController;
use App\Http\Controllers\PhotoController;
use App\Http\Controllers\WateringHistoryController;
use App\Http\Controllers\FertilizingHistoryController;
use App\Http\Controllers\RepottingHistoryController;
use App\Http\Controllers\PlantHistoryController;
use App\Http\Controllers\DiseaseHistoryController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\PurchasePlaceController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\FertilizerTypeController;
use App\Http\Controllers\BackupController;
use App\Http\Controllers\TagController;
use App\Http\Controllers\StatisticsController;
use App\Http\Controllers\Admin\UserApprovalController;
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
Route::middleware(['auth', 'verified', 'check.approval'])->group(function () {
    // Statistiques
    Route::get('statistics', [StatisticsController::class, 'index'])->name('statistics.index');

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
    Route::resource('plants.disease-history', DiseaseHistoryController::class, ['only' => ['index', 'store', 'update', 'destroy']]);

    // Gestion des types d'engrais
    Route::resource('fertilizer-types', FertilizerTypeController::class);

    // Gestion des emplacements et lieux d'achat
    Route::resource('locations', LocationController::class);
    Route::resource('purchase-places', PurchasePlaceController::class);

    // Routes pour les paramètres
    Route::get('settings', [SettingsController::class, 'index'])->name('settings.index');
    Route::put('settings', [SettingsController::class, 'update'])->name('settings.update');
    Route::get('settings/references', [SettingsController::class, 'references'])->name('settings.references');

    // Routes admin pour les tags (admin-only)
    Route::middleware(['admin'])->group(function () {
        Route::resource('admin/tags', TagController::class, ['names' => [
            'index' => 'tags.index',
            'create' => 'tags.create',
            'store' => 'tags.store',
            'edit' => 'tags.edit',
            'update' => 'tags.update',
            'destroy' => 'tags.destroy',
        ]]);
        
        // Créer une catégorie de tags
        Route::post('admin/tags-category', [TagController::class, 'storeCategory'])->name('tags.store-category');
        
        // Supprimer une catégorie entière (tous les tags)
        Route::delete('admin/tags-category/{tagCategory}', [TagController::class, 'destroyCategory'])->name('tags.destroy-category');

        // Gestion des approbations d'utilisateurs
        Route::get('admin/users/approval', [UserApprovalController::class, 'index'])->name('admin.users.approval');
        Route::post('admin/users/{user}/approve', [UserApprovalController::class, 'approve'])->name('admin.users.approve');
        Route::post('admin/users/{user}/reject', [UserApprovalController::class, 'reject'])->name('admin.users.reject');
        Route::delete('admin/users/{user}', [UserApprovalController::class, 'destroy'])->name('admin.users.destroy');
    });

    // Routes pour les sauvegardes et exports (admin-only)
    Route::middleware(['admin'])->prefix('settings/backups')->name('backups.')->group(function () {
        Route::get('/', [BackupController::class, 'index'])->name('index');
        Route::post('/export', [BackupController::class, 'export'])->name('export');
        Route::post('/upload', [BackupController::class, 'uploadBackup'])->name('upload');
        Route::get('/download/{filename}', [BackupController::class, 'download'])->name('download');
        Route::delete('/{filename}', [BackupController::class, 'delete'])->name('delete');
        Route::post('/delete-multiple', [BackupController::class, 'deleteMultiple'])->name('delete-multiple');
        
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

// Image diagnostic route (development/testing)
Route::get('/image-diagnostic', function () {
    $plants = \App\Models\Plant::with('photos')
        ->limit(5)
        ->get();
    return view('image-diagnostic', compact('plants'));
})->middleware(['auth', 'verified'])->name('image-diagnostic');

// Serve files from storage (bypass Laravel's public storage)
Route::get('/storage/{path}', [StorageController::class, 'serve'])
    ->where('path', '.*')
    ->name('storage.serve');

// Flash session messages via AJAX
Route::post('/session/flash', function (\Illuminate\Http\Request $request) {
    $type = $request->input('type', 'info');
    $message = $request->input('message', '');
    
    session()->flash($type, $message);
    
    return response()->json(['success' => true]);
})->middleware(['auth', 'verified'])->name('session.flash');

require __DIR__.'/auth.php';
