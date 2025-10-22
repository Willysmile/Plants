<?php

Route::get('/image-diagnostic', function () {
    $plants = \App\Models\Plant::with('photos')
        ->limit(5)
        ->get();

    return view('image-diagnostic', compact('plants'));
})->middleware('auth');
