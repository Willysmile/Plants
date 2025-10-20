<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PlantController;
use App\Http\Controllers\PhotoController;
use App\Http\Controllers\WateringHistoryController;
use App\Http\Controllers\FertilizingHistoryController;
use App\Http\Controllers\RepottingHistoryController;
use App\Http\Controllers\PlantHistoryController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\FertilizerTypeController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Route pour les plantes archivées
Route::get('plants/archived', [PlantController::class, 'archived'])->name('plants.archived');

// Routes pour archiver et restaurer les plantes
Route::post('plants/{plant}/archive', [PlantController::class, 'archive'])->name('plants.archive');
Route::post('plants/{plant}/restore', [PlantController::class, 'restore'])->name('plants.restore');

Route::resource('plants', PlantController::class);

// page d'accueil redirige vers l'index des plantes
Route::get('/', function () {
    return redirect()->route('plants.index');
});

// route AJAX pour charger la fiche d'une plante en HTML (modal)
Route::get('plants/{plant}/modal', [PlantController::class, 'modal'])->name('plants.modal');

// route AJAX pour recharger les historiques dans la modal
Route::get('plants/{plant}/histories', [PlantController::class, 'histories'])->name('plants.histories');

// route AJAX pour générer une référence incrémentée
Route::post('plants/generate-reference', [PlantController::class, 'generateReferenceAPI'])->name('plants.generate-reference');

// route AJAX pour créer un nouveau type d'engrais
Route::post('fertilizer-types', [FertilizerTypeController::class, 'store'])->name('fertilizer-types.store');

// mise à jour de la légende d'une photo (PATCH)
Route::patch('plants/{plant}/photos/{photo}', [PhotoController::class, 'update'])
    ->name('plants.photos.update');

// suppression d'une photo dans la galerie
Route::delete('plants/{plant}/photos/{photo}', [PhotoController::class, 'destroy'])
    ->name('plants.photos.destroy');

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
