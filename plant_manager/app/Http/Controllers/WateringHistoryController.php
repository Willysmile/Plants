<?php

namespace App\Http\Controllers;

use App\Models\WateringHistory;
use App\Models\Plant;
use Illuminate\Http\Request;
use Carbon\Carbon;

class WateringHistoryController extends Controller
{
    /**
     * Display a listing of the watering history for a plant.
     */
    public function index(Plant $plant)
    {
        $histories = $plant->wateringHistories()->latest('watering_date')->paginate(10);
        return view('plants.watering-history.index', compact('plant', 'histories'));
    }

    /**
     * Show the form for creating a new watering record.
     */
    public function create(Plant $plant)
    {
        return view('plants.watering-history.create', compact('plant'));
    }

    /**
     * Store a newly created watering record in storage.
     */
    public function store(Request $request, Plant $plant)
    {
        $validated = $request->validate([
            'watering_date' => 'required|date_format:Y-m-d|before_or_equal:today',
            'amount' => 'nullable|string',
            'notes' => 'nullable|string',
        ], [
            'watering_date.before_or_equal' => 'La date ne peut pas être dans le futur.',
        ]);

        $validated['plant_id'] = $plant->id;
        
        WateringHistory::create($validated);

        // Update the plant's last watering date
        $plant->update([
            'last_watering_date' => $validated['watering_date'],
        ]);

        // Return JSON for AJAX requests
        if ($request->expectsJson()) {
            return response()->json(['success' => true], 200);
        }

        return redirect()->route('plants.watering-history.index', $plant)
            ->with('success', 'Arrosage enregistré avec succès.');
    }

    /**
     * Show the form for editing the specified watering record.
     */
    public function edit(Plant $plant, WateringHistory $wateringHistory)
    {
        return view('plants.watering-history.edit', compact('plant', 'wateringHistory'));
    }

    /**
     * Update the specified watering record in storage.
     */
    public function update(Request $request, Plant $plant, WateringHistory $wateringHistory)
    {
        $validated = $request->validate([
            'watering_date' => 'required|date_format:Y-m-d\TH:i',
            'amount' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
        ]);

        $wateringHistory->update($validated);

        return redirect()->route('plants.watering-history.index', $plant)
            ->with('success', 'Arrosage mis à jour avec succès.');
    }

    /**
     * Remove the specified watering record from storage.
     */
    public function destroy(Plant $plant, WateringHistory $wateringHistory)
    {
        $wateringHistory->delete();

        return redirect()->route('plants.watering-history.index', $plant)
            ->with('success', 'Arrosage supprimé avec succès.');
    }
}
