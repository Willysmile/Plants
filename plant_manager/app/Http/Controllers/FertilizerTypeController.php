<?php

namespace App\Http\Controllers;

use App\Models\FertilizerType;
use Illuminate\Http\Request;

class FertilizerTypeController extends Controller
{
    /**
     * Display a listing of all fertilizer types.
     */
    public function index()
    {
        $fertilizerTypes = FertilizerType::all();
        return view('settings.fertilizer-types.index', compact('fertilizerTypes'));
    }

    /**
     * Show the form for creating a new fertilizer type.
     */
    public function create()
    {
        return view('settings.fertilizer-types.create');
    }

    /**
     * Store a newly created fertilizer type in storage.
     */
    public function store(Request $request)
    {
        // If AJAX request (from modal)
        if ($request->header('X-Requested-With') === 'XMLHttpRequest' || $request->input('_ajax')) {
            $validated = $request->validate([
                'name' => 'required|string|max:255|unique:fertilizer_types,name',
            ]);
            
            $fertilizerType = FertilizerType::create($validated);
            return response()->json($fertilizerType, 201);
        }

        // Normal form submission
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:fertilizer_types,name',
            'description' => 'nullable|string',
            'unit' => 'required|in:ml,g,nombre',
        ]);

        FertilizerType::create($validated);

        return redirect()->route('fertilizer-types.index')
            ->with('success', 'Type d\'engrais créé avec succès.');
    }

    /**
     * Show the form for editing the specified fertilizer type.
     */
    public function edit(FertilizerType $fertilizerType)
    {
        return view('settings.fertilizer-types.edit', compact('fertilizerType'));
    }

    /**
     * Update the specified fertilizer type in storage.
     */
    public function update(Request $request, FertilizerType $fertilizerType)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:fertilizer_types,name,' . $fertilizerType->id,
            'description' => 'nullable|string',
            'unit' => 'required|in:ml,g,nombre',
        ]);

        $fertilizerType->update($validated);

        return redirect()->route('fertilizer-types.index')
            ->with('success', 'Type d\'engrais mis à jour avec succès.');
    }

    /**
     * Remove the specified fertilizer type from storage.
     */
    public function destroy(FertilizerType $fertilizerType)
    {
        // Check if it's used in any fertilizing history
        if ($fertilizerType->fertilizingHistories()->count() > 0) {
            return redirect()->route('fertilizer-types.index')
                ->with('error', 'Impossible de supprimer ce type car il est utilisé dans l\'historique.');
        }

        $fertilizerType->delete();

        return redirect()->route('fertilizer-types.index')
            ->with('success', 'Type d\'engrais supprimé avec succès.');
    }
}
