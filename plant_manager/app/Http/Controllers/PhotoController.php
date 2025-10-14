<?php

namespace App\Http\Controllers;

use App\Models\Photo;
use App\Models\Plant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PhotoController extends Controller
{
    /**
     * Met à jour la description / légende d'une photo.
     */
    public function update(Plant $plant, Photo $photo, Request $request)
    {
        if ($photo->plant_id !== $plant->id) {
            abort(404);
        }

        $data = $request->validate([
            'description' => ['nullable', 'string', 'max:1000'],
        ]);

        $photo->update(['description' => $data['description'] ?? null]);

        // Si requête AJAX/JSON, retourner JSON utile au front
        if ($request->wantsJson() || $request->ajax() || $request->header('Accept') === 'application/json') {
            return response()->json([
                'ok' => true,
                'photo_id' => $photo->id,
                'description' => $photo->description,
            ]);
        }

        return redirect()->back()->with('success', 'Légende enregistrée.');
    }

    /**
     * Supprimer une photo d'une plante.
     */
    public function destroy(Plant $plant, Photo $photo)
    {
        if ($photo->plant_id !== $plant->id) {
            abort(404);
        }

        if ($photo->filename) {
            Storage::disk('public')->delete($photo->filename);
        }

        $photo->delete();

        return redirect()->back()->with('success', 'Photo supprimée.');
    }
}