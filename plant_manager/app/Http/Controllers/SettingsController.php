<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use App\Models\Plant;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    /**
     * Display the settings page
     */
    public function index()
    {
        $settings = Setting::getInstance();
        return view('settings.index', compact('settings'));
    }

    /**
     * Update application settings
     */
    public function update(Request $request)
    {
        $validated = $request->validate([
            'timezone' => 'required|timezone',
            'locale' => 'required|in:fr,en',
            'temperature_unit' => 'required|in:celsius,fahrenheit',
            'pot_unit' => 'required|string|max:10',
        ]);

        // Get or create the single settings row
        $settings = Setting::getInstance();
        $settings->update($validated);

        // Update config for current runtime
        config(['app.timezone' => $validated['timezone']]);
        date_default_timezone_set($validated['timezone']);

        return redirect()->route('settings.index')
            ->with('success', 'Paramètres sauvegardés avec succès.');
    }

    /**
     * Display references management dashboard
     */
    public function references()
    {
        // Toutes les plantes avec référence
        $plantsWithReference = Plant::whereNotNull('reference')
            ->orderBy('reference')
            ->get();
        
        // Grouper par famille (prefix)
        $referencesByFamily = $plantsWithReference->groupBy(function($plant) {
            return substr($plant->reference, 0, 5);
        });
        
        // Références orphelines (supprimées mais utilisées)
        $usedReferences = Plant::whereNotNull('reference')->pluck('reference')->toArray();
        
        // Trouver les trous dans la numérotation
        $orphanedReferences = [];
        foreach ($referencesByFamily as $prefix => $plants) {
            $numbers = [];
            foreach ($plants as $plant) {
                $num = (int) substr($plant->reference, -3);
                $numbers[] = $num;
            }
            
            sort($numbers);
            $maxNum = end($numbers);
            
            for ($i = 1; $i <= $maxNum; $i++) {
                if (!in_array($i, $numbers)) {
                    $orphanedReferences[] = $prefix . '-' . str_pad($i, 3, '0', STR_PAD_LEFT);
                }
            }
        }
        
        $stats = [
            'total' => count($plantsWithReference),
            'orphaned' => count($orphanedReferences),
            'families' => count($referencesByFamily),
        ];
        
        return view('settings.references', compact('referencesByFamily', 'orphanedReferences', 'stats'));
    }
}

