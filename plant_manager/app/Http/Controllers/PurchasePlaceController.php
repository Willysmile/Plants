<?php

namespace App\Http\Controllers;

use App\Models\PurchasePlace;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class PurchasePlaceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $purchasePlaces = PurchasePlace::withCount('plants')->orderBy('name')->paginate(15);
        
        return view('purchase-places.index', [
            'purchasePlaces' => $purchasePlaces,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('purchase-places.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|unique:purchase_places,name|max:255',
            'description' => 'nullable|string',
            'address' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            'website' => 'nullable|url|max:255',
            'type' => 'nullable|string|max:100',
        ]);

        PurchasePlace::create($validated);

        return redirect()->route('purchase-places.index')
            ->with('success', 'Lieu d\'achat créé avec succès.');
    }

    /**
     * Display the specified resource.
     */
    public function show(PurchasePlace $purchasePlace): View
    {
        return view('purchase-places.show', [
            'purchasePlace' => $purchasePlace,
            'plants' => $purchasePlace->plants()->paginate(12),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PurchasePlace $purchasePlace): View
    {
        return view('purchase-places.edit', [
            'purchasePlace' => $purchasePlace,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, PurchasePlace $purchasePlace): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|unique:purchase_places,name,' . $purchasePlace->id . '|max:255',
            'description' => 'nullable|string',
            'address' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            'website' => 'nullable|url|max:255',
            'type' => 'nullable|string|max:100',
        ]);

        $purchasePlace->update($validated);

        return redirect()->route('purchase-places.show', $purchasePlace)
            ->with('success', 'Lieu d\'achat mis à jour avec succès.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PurchasePlace $purchasePlace): RedirectResponse
    {
        if ($purchasePlace->plants()->count() > 0) {
            return redirect()->route('purchase-places.index')
                ->with('error', 'Impossible de supprimer un lieu d\'achat avec des plantes.');
        }

        $purchasePlace->delete();

        return redirect()->route('purchase-places.index')
            ->with('success', 'Lieu d\'achat supprimé avec succès.');
    }
}
