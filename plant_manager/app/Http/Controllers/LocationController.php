<?php

namespace App\Http\Controllers;

use App\Models\Location;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class LocationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $locations = Location::withCount('plants')->orderBy('name')->paginate(15);
        
        return view('locations.index', [
            'locations' => $locations,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('locations.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|unique:locations,name|max:255',
            'description' => 'nullable|string',
            'room' => 'nullable|string|max:255',
            'light_level' => 'nullable|string|max:255',
            'humidity_level' => 'nullable|integer|min:0|max:100',
            'temperature' => 'nullable|numeric|min:-50|max:50',
        ]);

        Location::create($validated);

        return redirect()->route('locations.index')
            ->with('success', 'Emplacement créé avec succès.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Location $location): View
    {
        return view('locations.show', [
            'location' => $location,
            'plants' => $location->plants()->paginate(12),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Location $location): View
    {
        return view('locations.edit', [
            'location' => $location,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Location $location): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|unique:locations,name,' . $location->id . '|max:255',
            'description' => 'nullable|string',
            'room' => 'nullable|string|max:255',
            'light_level' => 'nullable|string|max:255',
            'humidity_level' => 'nullable|integer|min:0|max:100',
            'temperature' => 'nullable|numeric|min:-50|max:50',
        ]);

        $location->update($validated);

        return redirect()->route('locations.show', $location)
            ->with('success', 'Emplacement mis à jour avec succès.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Location $location): RedirectResponse
    {
        if ($location->plants()->count() > 0) {
            return redirect()->route('locations.index')
                ->with('error', 'Impossible de supprimer un emplacement avec des plantes.');
        }

        $location->delete();

        return redirect()->route('locations.index')
            ->with('success', 'Emplacement supprimé avec succès.');
    }
}
