<?php

namespace App\Http\Controllers;

use App\Models\RepottingHistory;
use App\Models\Plant;
use Illuminate\Http\Request;
use Carbon\Carbon;

class RepottingHistoryController extends Controller
{
    /**
     * Display a listing of the repotting history for a plant.
     */
    public function index(Plant $plant)
    {
        $histories = $plant->repottingHistories()->latest('repotting_date')->paginate(10);
        return view('plants.repotting-history.index', compact('plant', 'histories'));
    }

    /**
     * Show the form for creating a new repotting record.
     */
    public function create(Plant $plant)
    {
        return view('plants.repotting-history.create', compact('plant'));
    }

    /**
     * Store a newly created repotting record in storage.
     */
    public function store(Request $request, Plant $plant)
    {
        $validated = $request->validate([
            'repotting_date' => 'required|date',
            'old_pot_size' => 'nullable|string|max:255',
            'new_pot_size' => 'required|string|max:255',
            'soil_type' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
        ]);

        $validated['plant_id'] = $plant->id;
        RepottingHistory::create($validated);

        // Update the plant's last repotting date
        $plant->update([
            'last_repotting_date' => $validated['repotting_date'],
        ]);

        return redirect()->route('plants.repotting-history.index', $plant)
            ->with('success', 'Rempotage enregistré avec succès.');
    }

    /**
     * Show the form for editing the specified repotting record.
     */
    public function edit(Plant $plant, RepottingHistory $repottingHistory)
    {
        return view('plants.repotting-history.edit', compact('plant', 'repottingHistory'));
    }

    /**
     * Update the specified repotting record in storage.
     */
    public function update(Request $request, Plant $plant, RepottingHistory $repottingHistory)
    {
        $validated = $request->validate([
            'repotting_date' => 'required|date',
            'old_pot_size' => 'nullable|string|max:255',
            'new_pot_size' => 'required|string|max:255',
            'soil_type' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
        ]);

        $repottingHistory->update($validated);

        return redirect()->route('plants.repotting-history.index', $plant)
            ->with('success', 'Rempotage mis à jour avec succès.');
    }

    /**
     * Remove the specified repotting record from storage.
     */
    public function destroy(Plant $plant, RepottingHistory $repottingHistory)
    {
        $repottingHistory->delete();

        return redirect()->route('plants.repotting-history.index', $plant)
            ->with('success', 'Rempotage supprimé avec succès.');
    }
}
