<?php

namespace App\Http\Controllers;

use App\Models\FertilizerType;
use Illuminate\Http\Request;

class FertilizerTypeController extends Controller
{
    /**
     * Store a newly created fertilizer type (AJAX).
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:fertilizer_types,name',
        ]);

        $fertilizerType = FertilizerType::create($validated);

        return response()->json($fertilizerType, 201);
    }
}
