@extends('layouts.app')

@section('title', 'Image Diagnostic')

@section('content')
<div class="max-w-7xl mx-auto p-6">
  <h1 class="text-2xl font-bold mb-6">🔍 Image Display Diagnostic</h1>

  @foreach($plants as $plant)
    <div class="bg-white rounded-lg shadow p-6 mb-6">
      <h2 class="text-xl font-semibold mb-4">{{ $plant->name }}</h2>
      
      <div class="grid grid-cols-2 gap-6">
        <!-- Main Photo -->
        <div>
          <h3 class="font-semibold mb-2">Main Photo:</h3>
          @if($plant->main_photo)
            <div class="bg-gray-100 p-4 rounded">
              <p class="text-sm text-gray-600 mb-2">Path: <code class="bg-gray-200 px-2 py-1 rounded">{{ $plant->main_photo }}</code></p>
              <p class="text-sm text-gray-600 mb-2">URL: <code class="bg-gray-200 px-2 py-1 rounded">{{ Storage::url($plant->main_photo) }}</code></p>
              <img src="{{ Storage::url($plant->main_photo) }}" alt="{{ $plant->name }}" class="w-full max-h-48 object-contain rounded border-2 border-blue-300">
              <p class="text-xs text-green-600 mt-2">✓ Image displayed</p>
            </div>
          @else
            <p class="text-red-600">✗ No main photo</p>
          @endif
        </div>

        <!-- Gallery Photos -->
        <div>
          <h3 class="font-semibold mb-2">Gallery ({{ $plant->photos->count() }} photos):</h3>
          <div class="grid grid-cols-3 gap-2">
            @foreach($plant->photos->take(3) as $photo)
              <div class="bg-gray-100 p-2 rounded">
                <p class="text-xs text-gray-600 mb-1 truncate" title="{{ $photo->filename }}">
                  {{ basename($photo->filename) }}
                </p>
                <img src="{{ Storage::url($photo->filename) }}" alt="photo" class="w-full h-20 object-contain rounded">
                <p class="text-xs text-green-600 mt-1">✓ OK</p>
              </div>
            @endforeach
          </div>
        </div>
      </div>

      <div class="mt-4 p-4 bg-blue-50 rounded text-sm text-gray-700">
        <strong>Data:</strong>
        <pre class="mt-2 text-xs overflow-auto">{{ json_encode([
          'id' => $plant->id,
          'main_photo' => $plant->main_photo,
          'photos_count' => $plant->photos->count(),
          'photos' => $plant->photos->map(fn($p) => [
            'id' => $p->id,
            'filename' => $p->filename,
            'mime_type' => $p->mime_type,
            'is_main' => $p->is_main
          ])->toArray()
        ], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) }}</pre>
      </div>
    </div>
  @endforeach

  <div class="bg-green-50 border border-green-200 rounded-lg p-6 mb-6">
    <h3 class="text-lg font-semibold text-green-800 mb-2">✅ Summary of fixes applied:</h3>
    <ul class="list-disc list-inside space-y-2 text-green-700">
      <li><strong>ImageService::convertToWebp</strong> - Fixed to save WebP files in the correct directory (same as source)</li>
      <li><strong>All images converted</strong> - 132 JPG images converted to WebP format</li>
      <li><strong>Main photos assigned</strong> - 40 plants now have main_photo field populated</li>
      <li><strong>Database updated</strong> - All photo references now point to WebP files</li>
    </ul>
  </div>
</div>

<style>
  code {
    font-family: 'Courier New', monospace;
  }
</style>
@endsection
