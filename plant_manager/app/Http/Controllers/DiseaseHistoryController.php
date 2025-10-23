<?php

namespace App\Http\Controllers;

use App\Models\Plant;
use App\Models\DiseaseHistory;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class DiseaseHistoryController extends Controller
{
    /**
     * Stocker un nouvel historique de maladie.
     */
    public function store(Request $request, Plant $plant): JsonResponse
    {
        $request->validate([
            'disease_name' => ['required', 'string', 'max:100'],
            'description' => ['nullable', 'string', 'max:500'],
            'treatment' => ['nullable', 'string', 'max:500'],
            'detected_at' => ['required', 'date'],
            'treated_at' => ['nullable', 'date', 'after_or_equal:detected_at'],
            'status' => ['required', 'in:detected,treated,cured,recurring'],
        ]);

        $disease = DiseaseHistory::create([
            'plant_id' => $plant->id,
            'disease_name' => $request->disease_name,
            'description' => $request->description,
            'treatment' => $request->treatment,
            'detected_at' => $request->detected_at,
            'treated_at' => $request->treated_at,
            'status' => $request->status,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Historique maladie enregistré avec succès',
            'disease' => $disease,
        ]);
    }

    /**
     * Mettre à jour un historique de maladie.
     */
    public function update(Request $request, Plant $plant, DiseaseHistory $diseaseHistory)
    {
        $request->validate([
            'disease_name' => ['required', 'string', 'max:100'],
            'description' => ['nullable', 'string', 'max:500'],
            'treatment' => ['nullable', 'string', 'max:500'],
            'detected_at' => ['required', 'date'],
            'treated_at' => ['nullable', 'date', 'after_or_equal:detected_at'],
            'status' => ['required', 'in:detected,treated,cured,recurring'],
        ]);

        $diseaseHistory->update($request->only([
            'disease_name',
            'description',
            'treatment',
            'detected_at',
            'treated_at',
            'status',
        ]));

        return response()->json([
            'success' => true,
            'message' => 'Maladie mise à jour avec succès',
            'disease' => $diseaseHistory,
        ]);
    }

    /**
     * Supprimer un historique de maladie.
     */
    public function destroy(Plant $plant, DiseaseHistory $diseaseHistory)
    {
        $diseaseHistory->delete();

        return response()->json([
            'success' => true,
            'message' => 'Maladie supprimée avec succès',
        ]);
    }
}
