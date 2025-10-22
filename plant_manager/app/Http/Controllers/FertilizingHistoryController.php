<?php

namespace App\Http\Controllers;

use App\Models\FertilizingHistory;
use App\Models\Plant;
use Illuminate\Http\Request;
use Carbon\Carbon;

class FertilizingHistoryController extends Controller
{
    /**
     * Display a listing of the fertilizing history for a plant.
     */
    public function index(Plant $plant)
    {
        $histories = $plant->fertilizingHistories()->latest('fertilizing_date')->paginate(10);
        return view('plants.fertilizing-history.index', compact('plant', 'histories'));
    }

    /**
     * Show the form for creating a new fertilizing record.
     */
    public function create(Plant $plant)
    {
        $fertilizerTypes = \App\Models\FertilizerType::all();
        return view('plants.fertilizing-history.create', compact('plant', 'fertilizerTypes'));
    }

    /**
     * Store a newly created fertilizing record in storage.
     */
    public function store(Request $request, Plant $plant)
    {
        $validated = $request->validate([
            'fertilizing_date' => 'required|date_format:Y-m-d|before_or_equal:today',
            'fertilizer_type_id' => 'nullable|exists:fertilizer_types,id',
            'amount' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
        ], [
            'fertilizing_date.before_or_equal' => 'La date ne peut pas être dans le futur.',
        ]);

        $validated['plant_id'] = $plant->id;
        FertilizingHistory::create($validated);

        // Update the plant's last fertilizing date
        $plant->update([
            'last_fertilizing_date' => $validated['fertilizing_date'],
        ]);

        // Return empty response for AJAX requests (no redirect)
        if ($request->header('X-Requested-With') === 'XMLHttpRequest' || $request->input('_ajax')) {
            return response()->json(['success' => true], 200);
        }

        // For normal requests, redirect to show page
        return redirect()->route('plants.show', $plant)
            ->with('success', 'Fertilisation enregistrée avec succès.');
    }

    /**
     * Show the form for editing the specified fertilizing record.
     */
    public function edit(Plant $plant, FertilizingHistory $fertilizingHistory)
    {
        $fertilizerTypes = \App\Models\FertilizerType::all();
        return view('plants.fertilizing-history.edit', compact('plant', 'fertilizingHistory', 'fertilizerTypes'));
    }

    /**
     * Update the specified fertilizing record in storage.
     */
    public function update(Request $request, Plant $plant, FertilizingHistory $fertilizingHistory)
    {
        $validated = $request->validate([
            'fertilizing_date' => 'required|date_format:Y-m-d',
            'fertilizer_type_id' => 'nullable|exists:fertilizer_types,id',
            'amount' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
        ]);

        $fertilizingHistory->update($validated);

        return redirect()->route('plants.fertilizing-history.index', $plant)
            ->with('success', 'Fertilisation mise à jour avec succès.');
    }

    /**
     * Remove the specified fertilizing record from storage.
     */
    public function destroy(Plant $plant, FertilizingHistory $fertilizingHistory)
    {
        $fertilizingHistory->delete();

        return redirect()->route('plants.fertilizing-history.index', $plant)
            ->with('success', 'Fertilisation supprimée avec succès.');
    }
}
