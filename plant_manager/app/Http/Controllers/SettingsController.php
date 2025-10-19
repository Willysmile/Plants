<?php

namespace App\Http\Controllers;

use App\Models\Setting;
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
}

