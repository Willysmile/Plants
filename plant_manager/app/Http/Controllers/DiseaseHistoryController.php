<?php

namespace App\Http\Controllers;

use App\Models\Plant;
use App\Models\Disease;
use App\Models\DiseaseHistory;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;

class DiseaseHistoryController extends Controller
{
    /**
     * Afficher tous les historiques de maladies pour une plante.
     */
    public function index(Plant $plant)
    {
        $diseaseHistories = $plant->diseaseHistories()->with('disease')->orderByDesc('detected_at')->get();
        
        return view('plants.disease-history.index', [
            'plant' => $plant,
            'diseaseHistories' => $diseaseHistories,
        ]);
    }

    /**
     * Stocker un nouvel historique de maladie.
     */
    public function store(Request $request, Plant $plant): RedirectResponse
    {
        // Validation flexible pour disease_id (peut être "new" ou un ID)
        $request->validate([
            'disease_id' => ['nullable', 'string'],
            'new_disease_name' => ['nullable', 'string', 'max:100'],
            'description' => ['nullable', 'string', 'max:500'],
            'treatment' => ['nullable', 'string', 'max:500'],
            'detected_at' => ['required', 'date'],
            'treated_at' => ['nullable', 'date', 'after_or_equal:detected_at'],
            'status' => ['required', 'in:detected,treated,cured,recurring'],
        ]);

        // Déterminer le disease_id
        $diseaseId = null;
        
        if ($request->input('disease_id') === 'new') {
            // Créer une nouvelle maladie
            if (empty($request->input('new_disease_name'))) {
                return redirect()->back()->withErrors(['new_disease_name' => 'Le nom de la maladie est requis']);
            }
            $disease = Disease::firstOrCreate(
                ['name' => $request->input('new_disease_name')],
                ['description' => null]
            );
            $diseaseId = $disease->id;
        } else {
            // Utiliser une maladie existante
            $diseaseId = (int) $request->input('disease_id');
        }

        // Vérifier que disease_id est valide
        if (!Disease::find($diseaseId)) {
            return redirect()->back()->withErrors(['disease_id' => 'Maladie invalide']);
        }

        DiseaseHistory::create([
            'plant_id' => $plant->id,
            'disease_id' => $diseaseId,
            'description' => $request->input('description'),
            'treatment' => $request->input('treatment'),
            'detected_at' => $request->input('detected_at'),
            'treated_at' => $request->input('treated_at'),
            'status' => $request->input('status'),
        ]);

        return redirect()->route('plants.show', $plant)->with('success', 'Maladie enregistrée avec succès');
    }

    /**
     * Mettre à jour un historique de maladie.
     */
    public function update(Request $request, Plant $plant, DiseaseHistory $diseaseHistory): RedirectResponse
    {
        $validated = $request->validate([
            'disease_id' => ['required', 'integer', 'exists:diseases,id'],
            'description' => ['nullable', 'string', 'max:500'],
            'treatment' => ['nullable', 'string', 'max:500'],
            'detected_at' => ['required', 'date'],
            'treated_at' => ['nullable', 'date', 'after_or_equal:detected_at'],
            'status' => ['required', 'in:detected,treated,cured,recurring'],
        ]);

        $diseaseHistory->update($validated);

        return redirect()->route('plants.show', $plant)->with('success', 'Maladie mise à jour avec succès');
    }

    /**
     * Supprimer un historique de maladie.
     */
    public function destroy(Plant $plant, DiseaseHistory $diseaseHistory): RedirectResponse
    {
        $diseaseHistory->delete();

        return redirect()->route('plants.show', $plant)->with('success', 'Maladie supprimée avec succès');
    }
}
