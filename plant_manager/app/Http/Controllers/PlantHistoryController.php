<?php

namespace App\Http\Controllers;

use App\Models\Plant;
use App\Models\PlantHistory;
use Illuminate\Http\Request;

class PlantHistoryController extends Controller
{
    /**
     * Afficher les infos diverses pour une plante
     */
    public function index(Plant $plant)
    {
        $histories = $plant->histories()->orderBy('created_at', 'desc')->paginate(15);
        return view('plants.histories.index', compact('plant', 'histories'));
    }

    /**
     * Créer une nouvelle entrée d'infos diverses
     */
    public function store(Request $request, Plant $plant)
    {
        $validated = $request->validate([
            'body' => 'required|string|max:144',
        ]);

        $plant->histories()->create($validated);

        if ($request->expectsJson()) {
            return response()->json(['success' => true]);
        }

        return redirect()->back()->with('success', 'Infos Diverses ajoutées avec succès.');
    }

    /**
     * Afficher une entrée spécifique
     */
    public function show(Plant $plant, PlantHistory $plantHistory)
    {
        return view('plants.histories.show', compact('plant', 'plantHistory'));
    }

    /**
     * Modifier une entrée
     */
    public function edit(Plant $plant, PlantHistory $plantHistory)
    {
        return view('plants.histories.edit', compact('plant', 'plantHistory'));
    }

    /**
     * Mettre à jour une entrée
     */
    public function update(Request $request, Plant $plant, PlantHistory $plantHistory)
    {
        $validated = $request->validate([
            'body' => 'required|string|max:144',
        ]);

        $plantHistory->update($validated);

        if ($request->expectsJson()) {
            return response()->json(['success' => true]);
        }

        return redirect()->back()->with('success', 'Infos Diverses mises à jour.');
    }

    /**
     * Supprimer une entrée
     */
    public function destroy(Plant $plant, PlantHistory $plantHistory, Request $request)
    {
        $plantHistory->delete();

        if ($request->expectsJson()) {
            return response()->json(['success' => true]);
        }

        return redirect()->route('plants.show', $plant)->with('success', 'Entrée supprimée.');
    }
}
