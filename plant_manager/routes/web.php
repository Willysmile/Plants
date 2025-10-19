<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PlantController;
use App\Http\Controllers\PhotoController;
use App\Http\Controllers\WateringHistoryController;
use App\Http\Controllers\FertilizingHistoryController;
use App\Http\Controllers\RepottingHistoryController;
use App\Http\Controllers\SettingsController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::resource('plants', PlantController::class);

// page d'accueil redirige vers l'index des plantes
Route::get('/', function () {
    return redirect()->route('plants.index');
});

// route AJAX pour charger la fiche d'une plante en HTML (modal)
Route::get('plants/{plant}/modal', [PlantController::class, 'modal'])->name('plants.modal');

// route AJAX pour recharger les historiques dans la modal
Route::get('plants/{plant}/histories', [PlantController::class, 'histories'])->name('plants.histories');

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

// Routes pour les paramètres
Route::get('settings', [SettingsController::class, 'index'])->name('settings.index');
Route::put('settings', [SettingsController::class, 'update'])->name('settings.update');
