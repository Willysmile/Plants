@extends('layouts.app')

@section('title', 'Statistiques')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-8">
  <!-- En-tête -->
  <div class="mb-8">
    <h1 class="text-4xl font-bold text-gray-800 mb-2">📊 Statistiques</h1>
    <p class="text-gray-600">Vue d'ensemble de votre collection de plantes</p>
  </div>

  <!-- Cartes principales -->
  <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
    <!-- Total des plantes -->
    <div class="bg-gradient-to-br from-green-50 to-green-100 p-6 rounded-lg shadow border-l-4 border-green-500">
      <div class="flex items-center justify-between">
        <div>
          <p class="text-gray-600 text-sm font-medium">Plantes actives</p>
          <p class="text-3xl font-bold text-green-700">{{ $activePlants }}</p>
        </div>
        <i data-lucide="leaf" class="w-12 h-12 text-green-300"></i>
      </div>
    </div>

    <!-- Plantes archivées -->
    <div class="bg-gradient-to-br from-gray-50 to-gray-100 p-6 rounded-lg shadow border-l-4 border-gray-500">
      <div class="flex items-center justify-between">
        <div>
          <p class="text-gray-600 text-sm font-medium">Plantes archivées</p>
          <p class="text-3xl font-bold text-gray-700">{{ $archivedPlants }}</p>
        </div>
        <i data-lucide="archive" class="w-12 h-12 text-gray-300"></i>
      </div>
    </div>

    <!-- Maladies actives -->
    <div class="bg-gradient-to-br from-red-50 to-red-100 p-6 rounded-lg shadow border-l-4 border-red-500">
      <div class="flex items-center justify-between">
        <div>
          <p class="text-gray-600 text-sm font-medium">Maladies actives</p>
          <p class="text-3xl font-bold text-red-700">{{ $activeDiseases->sum('count') }}</p>
        </div>
        <i data-lucide="alert-circle" class="w-12 h-12 text-red-300"></i>
      </div>
    </div>

    <!-- Emplacements -->
    <div class="bg-gradient-to-br from-blue-50 to-blue-100 p-6 rounded-lg shadow border-l-4 border-blue-500">
      <div class="flex items-center justify-between">
        <div>
          <p class="text-gray-600 text-sm font-medium">Emplacements</p>
          <p class="text-3xl font-bold text-blue-700">{{ $plantsByLocation->count() }}</p>
        </div>
        <i data-lucide="map-pin" class="w-12 h-12 text-blue-300"></i>
      </div>
    </div>
  </div>

  <!-- Grille de contenu -->
  <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <!-- Colonne gauche -->
    <div class="lg:col-span-2 space-y-8">
      <!-- Plantes par famille -->
      <div class="bg-white p-6 rounded-lg shadow">
        <h2 class="text-xl font-bold text-gray-800 mb-4 flex items-center gap-2">
          <i data-lucide="tree-pine" class="w-5 h-5"></i>
          Top 10 Familles
        </h2>
        <div class="space-y-3">
          @forelse($plantsByFamily as $family)
            <div class="flex items-center justify-between">
              <div class="flex-1">
                <p class="font-medium text-gray-700 italic">{{ $family->family ?: 'Non spécifiée' }}</p>
                <div class="w-full bg-gray-200 rounded-full h-2 mt-1">
                  <div class="bg-green-500 h-2 rounded-full" style="width: {{ ($family->total / $plantsByFamily->first()->total) * 100 }}%"></div>
                </div>
              </div>
              <span class="ml-4 font-bold text-gray-800 min-w-8 text-right">{{ $family->total }}</span>
            </div>
          @empty
            <p class="text-gray-500 text-center py-4">Aucune donnée</p>
          @endforelse
        </div>
      </div>

      <!-- Derniers arrosages -->
      <div class="bg-white p-6 rounded-lg shadow">
        <h2 class="text-xl font-bold text-gray-800 mb-4 flex items-center gap-2">
          <i data-lucide="droplet" class="w-5 h-5 text-blue-500"></i>
          Derniers arrosages
        </h2>
        <div class="space-y-2">
          @forelse($lastWatering as $plant)
            <div class="flex items-center justify-between p-3 bg-blue-50 rounded border-l-2 border-blue-500">
              <a href="{{ route('plants.show', $plant) }}" class="text-blue-600 hover:underline font-medium">
                {{ $plant->name }}
              </a>
              <span class="text-sm text-gray-600">{{ $plant->watering_date->format('d/m/Y') }}</span>
            </div>
          @empty
            <p class="text-gray-500 text-center py-4">Aucun arrosage enregistré</p>
          @endforelse
        </div>
      </div>

      <!-- Dernières fertilisations -->
      <div class="bg-white p-6 rounded-lg shadow">
        <h2 class="text-xl font-bold text-gray-800 mb-4 flex items-center gap-2">
          <i data-lucide="leaf" class="w-5 h-5 text-green-500"></i>
          Dernières fertilisations
        </h2>
        <div class="space-y-2">
          @forelse($lastFertilizing as $plant)
            <div class="flex items-center justify-between p-3 bg-green-50 rounded border-l-2 border-green-500">
              <a href="{{ route('plants.show', $plant) }}" class="text-green-600 hover:underline font-medium">
                {{ $plant->name }}
              </a>
              <span class="text-sm text-gray-600">{{ $plant->fertilizing_date->format('d/m/Y') }}</span>
            </div>
          @empty
            <p class="text-gray-500 text-center py-4">Aucune fertilisation enregistrée</p>
          @endforelse
        </div>
      </div>
    </div>

    <!-- Colonne droite -->
    <div class="space-y-8">
      <!-- Plantes par emplacement -->
      <div class="bg-white p-6 rounded-lg shadow">
        <h2 class="text-xl font-bold text-gray-800 mb-4 flex items-center gap-2">
          <i data-lucide="map-pin" class="w-5 h-5"></i>
          Par emplacement
        </h2>
        <div class="space-y-2">
          @forelse($plantsByLocation as $location)
            <div class="flex items-center justify-between p-3 bg-gray-50 rounded">
              <span class="font-medium text-gray-700">{{ $location->name }}</span>
              <span class="bg-blue-500 text-white px-3 py-1 rounded-full text-sm font-bold">
                {{ $location->plants_count }}
              </span>
            </div>
          @empty
            <p class="text-gray-500 text-center py-4">Aucun emplacement</p>
          @endforelse
        </div>
      </div>

      <!-- Besoins en arrosage -->
      <div class="bg-white p-6 rounded-lg shadow">
        <h2 class="text-xl font-bold text-gray-800 mb-4">💧 Besoins en arrosage</h2>
        <div class="space-y-2">
          @php
            $wateringLabels = [
              1 => 'Très rare',
              2 => 'Rare',
              3 => 'Modéré',
              4 => 'Régulier',
              5 => 'Très régulier'
            ];
          @endphp
          @forelse($wateringFrequencies as $freq)
            <div class="flex items-center justify-between">
              <span class="text-gray-600">{{ $wateringLabels[$freq->watering_frequency] ?? 'Inconnu' }}</span>
              <span class="font-bold text-gray-800">{{ $freq->total }}</span>
            </div>
          @empty
            <p class="text-gray-500 text-center py-2 text-sm">Aucune donnée</p>
          @endforelse
        </div>
      </div>

      <!-- Top Tags -->
      <div class="bg-white p-6 rounded-lg shadow">
        <h2 class="text-xl font-bold text-gray-800 mb-4">🏷️ Tags populaires</h2>
        <div class="flex flex-wrap gap-2">
          @forelse($topTags->take(10) as $tag)
            <span class="bg-purple-100 text-purple-800 px-3 py-1 rounded-full text-sm font-medium">
              {{ $tag->name }}
              <span class="text-purple-600 font-bold">{{ $tag->plants_count }}</span>
            </span>
          @empty
            <p class="text-gray-500 text-sm">Aucun tag</p>
          @endforelse
        </div>
      </div>
    </div>
  </div>

  <!-- Section maladies -->
  <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mt-8">
    <!-- Maladies actives -->
    <div class="bg-white p-6 rounded-lg shadow">
      <h2 class="text-xl font-bold text-gray-800 mb-4 flex items-center gap-2">
        <i data-lucide="alert-circle" class="w-5 h-5 text-red-500"></i>
        Maladies actives
      </h2>
      <div class="space-y-2">
        @forelse($activeDiseases as $disease)
          <div class="flex items-center justify-between p-3 bg-red-50 rounded border-l-2 border-red-500">
            <div>
              <p class="font-medium text-gray-700">{{ $disease->name }}</p>
              <p class="text-xs text-gray-600">
                @if($disease->status === 'detected')
                  🔴 Détectée
                @elseif($disease->status === 'treated')
                  🟡 Traitée
                @elseif($disease->status === 'recurring')
                  🔄 Récurrente
                @endif
              </p>
            </div>
            <span class="font-bold text-red-700">{{ $disease->count }}</span>
          </div>
        @empty
          <p class="text-gray-500 text-center py-4">✅ Aucune maladie active</p>
        @endforelse
      </div>
    </div>

    <!-- Plantes à arroser -->
    <div class="bg-white p-6 rounded-lg shadow">
      <h2 class="text-xl font-bold text-gray-800 mb-4 flex items-center gap-2">
        <i data-lucide="alert-triangle" class="w-5 h-5 text-orange-500"></i>
        À arroser bientôt
      </h2>
      <div class="space-y-2">
        @forelse($plantsDueForWatering as $plant)
          <div class="p-3 bg-orange-50 rounded border-l-2 border-orange-500">
            <a href="{{ route('plants.show', $plant) }}" class="text-orange-600 hover:underline font-medium">
              {{ $plant->name }}
            </a>
          </div>
        @empty
          <p class="text-gray-500 text-center py-4">✅ Toutes les plantes sont à jour</p>
        @endforelse
      </div>
    </div>
  </div>

  <!-- Retour -->
  <div class="mt-8 text-center">
    <a href="{{ route('plants.index') }}" class="text-blue-600 hover:underline font-medium">
      ← Retour à la collection
    </a>
  </div>
</div>

@include('partials.lightbox')
@endsection

@section('extra-scripts')
<script>
  document.addEventListener('DOMContentLoaded', function() {
    if (typeof lucide !== 'undefined') {
      lucide.createIcons();
    }
  });
</script>
@endsection
