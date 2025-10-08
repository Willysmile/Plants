<?php

namespace App\Http\Controllers;

use App\Models\Plant;
use App\Models\Tag;
use App\Models\Category;
use App\Http\Requests\StorePlantRequest;
use App\Http\Requests\UpdatePlantRequest;
use Illuminate\Http\Request;

class PlantController extends Controller
{
    /**
     * Affiche la liste paginée des plantes.
     */
    public function index(Request $request)
    {
        $plants = Plant::with(['category', 'tags', 'photos'])
            ->latest('created_at')
            ->paginate(12);

        return view('plants.index', compact('plants'));
    }

    /**
     * Affiche le formulaire de création.
     */
    public function create()
    {
        $categories = Category::orderBy('name')->get();
        $tags = Tag::orderBy('name')->get();

        return view('plants.create', compact('categories', 'tags'));
    }

    /**
     * Enregistre une nouvelle plante.
     */
    public function store(StorePlantRequest $request)
    {
        $data = $request->validated();
    $plant = Plant::create($data);

    // Gérer la photo principale
    if ($request->hasFile('main_photo')) {
        $file = $request->file('main_photo');
        $path = $file->store('plants', 'public');
        $plant->update(['main_photo' => $path]);
    }

    // Gérer les tags
    if ($request->filled('tags')) {
        $plant->tags()->sync($request->input('tags'));
    }

    return redirect()->route('plants.index')->with('success', 'Plante créée avec succès.');
    }

    /**
     * Affiche le détail d'une plante.
     */
    public function show(Plant $plant)
    {
        $plant->load(['category','tags','photos','parents','daughters']);
        return view('plants.show', compact('plant'));
    }

    /**
     * Formulaire d'édition.
     */
    public function edit(Plant $plant)
    {
        $categories = Category::orderBy('name')->get();
        $tags = Tag::orderBy('name')->get();

        return view('plants.edit', compact('plant','categories','tags'));
    }

    /**
     * Met à jour une plante existante.
     */
    public function update(UpdatePlantRequest $request, Plant $plant)
    {
        $data = $request->validated();
    
    // Si une nouvelle photo principale est téléchargée
    if ($request->hasFile('main_photo')) {
        // Supprimer l'ancienne photo si elle existe
        if ($plant->main_photo) {
            Storage::disk('public')->delete($plant->main_photo);
        }
        
        $file = $request->file('main_photo');
        $path = $file->store('plants', 'public');
        $data['main_photo'] = $path;
    }
    
    $plant->update($data);

    // Gérer les tags
    if ($request->filled('tags')) {
        $plant->tags()->sync($request->input('tags'));
    } else {
        $plant->tags()->detach();
    }

    return redirect()->route('plants.show', $plant)->with('success', 'Plante mise à jour avec succès.');
    }

    /**
     * Supprime une plante.
     */
    public function destroy(Plant $plant)
    {
        $plant->delete();
        return redirect()->route('plants.index')->with('success', 'Plante supprimée.');
    }
}
