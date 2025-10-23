<?php

namespace App\Http\Controllers;

use App\Models\Plant;
use App\Models\Tag;
use App\Models\Photo;
use App\Services\PhotoService;
use App\Http\Requests\StorePlantRequest;
use App\Http\Requests\UpdatePlantRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PlantController extends Controller
{
    public function __construct(
        private PhotoService $photoService
    ) {}
    /**
     * Liste paginée des plantes.
     */
    public function index(Request $request)
    {
        $plants = Plant::with(['tags', 'photos'])
            ->where('is_archived', false)
            ->latest('created_at')
            ->get();

        return view('plants.index', compact('plants'));
    }

    /**
     * Affiche les plantes archivées.
     */
    public function archived(Request $request)
    {
        $plants = Plant::where('is_archived', true)
            ->with(['tags', 'photos'])
            ->latest('archived_date')
            ->get();

        return view('plants.archived', compact('plants'));
    }

    /**
     * Formulaire de création.
     */
    public function create()
    {
        $tags = Tag::orderBy('name')->get();
        $locations = \App\Models\Location::orderBy('name')->get();
        $purchasePlaces = \App\Models\PurchasePlace::orderBy('name')->get();

        return view('plants.create', compact('tags', 'locations', 'purchasePlaces'));
    }

    /**
     * Enregistre une nouvelle plante avec galerie.
     */
    public function store(StorePlantRequest $request)
    {
        $data = $request->validated();
        $plant = Plant::create($data);

        // Attacher photo principale
        if ($request->hasFile('main_photo')) {
            $this->photoService->attachMainPhoto($plant, $request->file('main_photo'));
        }

        // Attacher galerie multiple
        if ($request->hasFile('photos')) {
            $this->photoService->attachPhotos($plant, $request->file('photos'));
        }

        // Tags
        if ($request->filled('tags')) {
            $plant->tags()->sync($request->input('tags'));
        }

        return redirect()->route('plants.index')->with('success', 'Plante créée avec succès.');
    }

    /**
     * Affiche le détail d'une plante.
     */
    public function show(\App\Models\Plant $plant)
{
    $plant->load(['tags','photos','parents','daughters','location','purchasePlace']);
    return view('plants.show', compact('plant'));
}
    /**
     * Formulaire d'édition.
     */
    public function edit(Plant $plant)
    {
        $tags = Tag::orderBy('name')->get();
        $locations = \App\Models\Location::orderBy('name')->get();
        $purchasePlaces = \App\Models\PurchasePlace::orderBy('name')->get();

        return view('plants.edit', compact('plant', 'tags', 'locations', 'purchasePlaces'));
    }

    /**
     * Met à jour une plante, gère remplacement de photo et ajout galerie.
     */
    public function update(UpdatePlantRequest $request, Plant $plant)
    {
         $data = $request->validated();

    // remplacement de la photo principale
    if ($request->hasFile('main_photo')) {
        if ($plant->main_photo) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($plant->main_photo);
            $plant->photos()->where('is_main', true)->update(['is_main' => false]);
        }

        $file = $request->file('main_photo');
        $path = $file->store("plants/{$plant->id}", 'public');
        $data['main_photo'] = $path;

        $plant->photos()->create([
            'filename' => $path,
            'mime_type' => $file->getClientMimeType(),
            'size' => $file->getSize(),
            'is_main' => true,
        ]);
    }

    // nouvelles images galerie
    if ($request->hasFile('photos')) {
        foreach ($request->file('photos') as $file) {
            $path = $file->store("plants/{$plant->id}", 'public');
            $plant->photos()->create([
                'filename' => $path,
                'mime_type' => $file->getClientMimeType(),
                'size' => $file->getSize(),
                'is_main' => false,
            ]);
        }
    }

    // Mettre à jour la plante principale
    $plant->update($data);

    // tags
    if ($request->filled('tags')) {
        $plant->tags()->sync($request->input('tags'));
    } else {
        $plant->tags()->detach();
    }

    // Sauvegarde des légendes (photo_descriptions) et suppression optionnelle (photo_delete)
    $photoDescriptions = $request->input('photo_descriptions', []);
    $photoDeletes = $request->input('photo_delete', []);
    if (is_array($photoDescriptions)) {
        foreach ($photoDescriptions as $photoId => $desc) {
            $photo = $plant->photos()->where('id', $photoId)->first();
            if (!$photo) continue;
            // suppression demandée ?
            if (!empty($photoDeletes[$photoId])) {
                // supprime fichier et enregistrement
                \Illuminate\Support\Facades\Storage::disk('public')->delete($photo->filename);
                $photo->delete();
                continue;
            }
            $photo->update(['description' => $desc ?: null]);
        }
    }

    return redirect()->route('plants.show', $plant)->with('success', 'Plante mise à jour avec succès.');
}

    /**
     * Supprime une plante et son dossier de photos.
     */
    public function destroy(Plant $plant)
    {
        // supprimer les fichiers du dossier de la plante
        Storage::disk('public')->deleteDirectory("plants/{$plant->id}");

        // supprimer la plante (la migration/fk doit gérer cascade sur photos si configuré)
        $plant->delete();

        return redirect()->route('plants.index')->with('success', 'Plante supprimée.');
    }
    public function modal(Plant $plant)
    {
        $plant->load([
            'tags',
            'photos',
            'parents',
            'daughters',
            'wateringHistories',
            'fertilizingHistories.fertilizerType',
            'repottingHistories',
        ]);
        // renvoie le partial HTML (non-layout) attendu par le JS
        return view('plants.partials.modal', compact('plant'));
    }

    public function histories(Plant $plant)
    {
        $plant->load([
            'tags',
            'wateringHistories',
            'fertilizingHistories.fertilizerType',
            'repottingHistories',
        ]);
        // retourne le HTML des 3 cartes d'historiques
        return view('plants.partials.histories', compact('plant'));
    }

    /**
     * Archive une plante.
     */
    public function archive(Plant $plant, Request $request)
    {
        $plant->update([
            'is_archived' => true,
            'archived_date' => now(),
            'archived_reason' => $request->input('reason'),
        ]);

        return redirect()->route('plants.index')->with('success', 'Plante archivée avec succès.');
    }

    /**
     * Restaure une plante.
     */
    public function restore(Plant $plant)
    {
        $plant->update([
            'is_archived' => false,
            'archived_date' => null,
            'archived_reason' => null,
        ]);

        return redirect()->route('plants.show', $plant)->with('success', 'Plante restaurée avec succès.');
    }

    /**
     * 🔧 FIX: Génère une référence incrémentée via API
     * Trouve le prochain numéro disponible (y compris soft-deleted)
     */
    public function generateReferenceAPI(Request $request)
    {
        $family = $request->input('family');
        
        if (!$family) {
            return response()->json(['error' => 'Family is required'], 400);
        }

        // Obtenir les 5 premières lettres de la famille en majuscules
        $familyPrefix = strtoupper(substr($family, 0, 5));
        
        // 🔧 FIX: Chercher le MAX numéro existant et ajouter 1 (incluant soft-deleted!)
        // Car la contrainte UNIQUE s'applique même aux soft-deleted
        $maxNumber = Plant::withTrashed()
            ->where('reference', 'like', $familyPrefix . '-%')
            ->get()
            ->map(function($plant) {
                // Extraire le numéro de la référence (ex: "BROME-001" → 1)
                return (int) substr($plant->reference, -3);
            })
            ->max() ?? 0;

        $nextNumber = $maxNumber + 1;

        // Formater la référence
        $reference = $familyPrefix . '-' . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);

        return response()->json(['reference' => $reference]);
    }
}