<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class StorageController extends Controller
{
    /**
     * Serve a file from storage
     */
    public function serve($path)
    {
        $fullPath = storage_path("app/public/{$path}");

        if (!file_exists($fullPath)) {
            abort(404, 'File not found');
        }

        $file = fopen($fullPath, 'r');
        
        return response()->stream(function () use ($file) {
            fpassthru($file);
        }, 200, [
            'Content-Type' => mime_content_type($fullPath),
            'Content-Disposition' => 'inline; filename="' . basename($fullPath) . '"',
        ]);
    }
}
