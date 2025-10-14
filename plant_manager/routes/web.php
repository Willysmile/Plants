<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PlantController;
use App\Http\Controllers\PhotoController;

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

// mise à jour de la légende d'une photo (PATCH)
Route::patch('plants/{plant}/photos/{photo}', [PhotoController::class, 'update'])
    ->name('plants.photos.update');

// suppression d'une photo dans la galerie
Route::delete('plants/{plant}/photos/{photo}', [PhotoController::class, 'destroy'])
    ->name('plants.photos.destroy');