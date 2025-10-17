<?php

namespace App\Http\Controllers;

use App\Models\ReppotingHistory;
use App\Models\Plant;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ReppotingHistoryController extends Controller
{
    /**
     * Display a listing of the repotting history for a plant.
     */
    public function index(Plant $plant)
    {
        $histories = $plant->reppotingHistories()->latest('repotting_date')->paginate(10);
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
            'repotting_date' => 'required|date_format:Y-m-d\TH:i',
            'old_pot_size' => 'nullable|string|max:255',
            'new_pot_size' => 'required|string|max:255',
            'soil_type' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
        ]);

        $validated['plant_id'] = $plant->id;
        ReppotingHistory::create($validated);

        // Update the plant's last repotting date (convert to proper datetime)
        $plant->update([
            'last_repotting_date' => Carbon::createFromFormat('Y-m-d\TH:i', $validated['repotting_date']),
        ]);

        return redirect()->route('plants.repotting-history.index', $plant)
            ->with('success', 'Rempotage enregistré avec succès.');
    }

    /**
     * Show the form for editing the specified repotting record.
     */
    public function edit(Plant $plant, ReppotingHistory $reppotingHistory)
    {
        return view('plants.repotting-history.edit', compact('plant', 'reppotingHistory'));
    }

    /**
     * Update the specified repotting record in storage.
     */
    public function update(Request $request, Plant $plant, ReppotingHistory $reppotingHistory)
    {
        $validated = $request->validate([
            'repotting_date' => 'required|date_format:Y-m-d\TH:i',
            'old_pot_size' => 'nullable|string|max:255',
            'new_pot_size' => 'required|string|max:255',
            'soil_type' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
        ]);

        $reppotingHistory->update($validated);

        return redirect()->route('plants.repotting-history.index', $plant)
            ->with('success', 'Rempotage mis à jour avec succès.');
    }

    /**
     * Remove the specified repotting record from storage.
     */
    public function destroy(Plant $plant, ReppotingHistory $reppotingHistory)
    {
        $reppotingHistory->delete();

        return redirect()->route('plants.repotting-history.index', $plant)
            ->with('success', 'Rempotage supprimé avec succès.');
    }
}
