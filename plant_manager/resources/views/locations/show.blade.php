@extends('layouts.app')

@section('title', $location->name)

@section('content')
  <div class="max-w-7xl mx-auto p-6">
    <div class="flex items-center justify-between mb-6">
      <div>
        <h1 class="text-2xl font-semibold">📍 {{ $location->name }}</h1>
        @if($location->room)
          <p class="text-gray-600 mt-1">{{ $location->room }}</p>
        @endif
      </div>
      <div class="flex items-center gap-2">
        <a href="{{ route('locations.edit', $location) }}" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded transition">
          ✏️ Éditer
        </a>
        <a href="{{ route('locations.index') }}" class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-800 rounded transition">
          ← Retour
        </a>
      </div>
    </div>

    <!-- Informations -->
    <div class="grid grid-cols-2 gap-6 mb-6">
      <!-- Description -->
      @if($location->description)
        <div class="bg-white rounded-lg shadow p-6">
          <h3 class="text-sm font-semibold text-gray-700 uppercase mb-2">Description</h3>
          <p class="text-gray-700">{{ $location->description }}</p>
        </div>
      @endif

      <!-- Conditions -->
      <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-sm font-semibold text-gray-700 uppercase mb-4">Conditions</h3>
        <div class="space-y-3">
          @if($location->light_level)
            <div class="flex justify-between items-center">
              <span class="text-gray-600">Lumière:</span>
              <span class="font-medium">{{ $location->light_level }}</span>
            </div>
          @endif
          @if($location->humidity_level !== null)
            <div class="flex justify-between items-center">
              <span class="text-gray-600">Humidité:</span>
              <span class="font-medium">{{ $location->humidity_level }}%</span>
            </div>
          @endif
          @if($location->temperature !== null)
            <div class="flex justify-between items-center">
              <span class="text-gray-600">Température:</span>
              <span class="font-medium">{{ $location->temperature }}°C</span>
            </div>
          @endif
        </div>
      </div>
    </div>

    <!-- Plantes dans cet emplacement -->
    <div class="bg-white rounded-lg shadow">
      <div class="border-b p-6">
        <h2 class="text-lg font-semibold">🌿 Plantes ({{ $plants->total() }})</h2>
      </div>
      
      <div class="grid grid-cols-5 gap-4 p-6">
        @forelse($plants as $plant)
          <article class="bg-gradient-to-br from-green-50 to-emerald-50 rounded-lg shadow-sm hover:shadow-md transition overflow-hidden border border-green-200">
            <!-- Photo -->
            @if($plant->main_photo)
              <div class="h-32 bg-gray-200 overflow-hidden">
                <img src="{{ Storage::url($plant->main_photo) }}" alt="{{ $plant->name }}" class="w-full h-full object-cover">
              </div>
            @else
              <div class="h-32 bg-gradient-to-br from-gray-200 to-gray-300 flex items-center justify-center">
                <span class="text-gray-400 text-3xl">🌱</span>
              </div>
            @endif

            <!-- Info -->
            <div class="p-3">
              <h3 class="font-semibold text-sm text-gray-800 truncate">
                <a href="{{ route('plants.show', $plant) }}" class="hover:text-green-600">{{ $plant->name }}</a>
              </h3>
              @if($plant->scientific_name)
                <p class="text-xs text-gray-600 italic truncate">{{ $plant->scientific_name }}</p>
              @endif
              <p class="text-xs text-gray-500 mt-1">{{ $plant->reference ?? '—' }}</p>
            </div>
          </article>
        @empty
          <div class="col-span-5 py-8 text-center text-gray-500">
            Aucune plante dans cet emplacement
          </div>
        @endforelse
      </div>

      <!-- Pagination -->
      @if($plants->hasPages())
        <div class="border-t p-6">
          {{ $plants->links() }}
        </div>
      @endif
    </div>
  </div>
@endsection
