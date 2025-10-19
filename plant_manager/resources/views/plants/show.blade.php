@extends('layouts.app')

@section('title', $plant->name)

@section('extra-head')
  <link rel="stylesheet" href="https://unpkg.com/lightbox2@2.11.4/dist/css/lightbox.min.css">
@endsection

@section('content')
<div class="h-[98vh] max-w-6xl mx-auto flex flex-col">
  <div class="bg-white rounded-lg shadow flex flex-col flex-grow overflow-hidden">
    <!-- En-tête avec titre et boutons d'action -->
    <div class="flex items-start justify-between p-4 border-b">
      <div class="flex-1">
        <div class="flex items-start gap-4">
          <div>
            @if($plant->scientific_name)
              <h1 class="text-3xl font-semibold italic text-green-700">{{ $plant->scientific_name }}</h1>
              <p class="text-base text-gray-700 mt-2">{{ $plant->name }}</p>
            @else
              <h1 class="text-3xl font-semibold">{{ $plant->name }}</h1>
            @endif
          </div>

          <!-- catégorie à côté du titre -->
          <div class="ml-2 self-center">
            <span class="inline-flex items-center px-3 py-1 rounded-full bg-blue-100 text-blue-800 text-sm font-medium border border-blue-200">
              {{ $plant->category->name ?? '—' }}
            </span>
          </div>
        </div>
      </div>
      <div class="flex items-center gap-2 ml-4">
        <a href="{{ route('plants.edit', $plant) }}" class="px-4 py-2 bg-yellow-500 hover:bg-yellow-600 text-white rounded-md transition">Modifier</a>
        <a href="{{ route('plants.index') }}" class="px-4 py-2 bg-gray-400 hover:bg-gray-500 text-white rounded-md transition">Retour</a>
      </div>
    </div>

    <!-- Contenu principal - occupe les 2/3 supérieurs -->
    <div class="flex-grow overflow-hidden p-4" style="height: 66%;">
      <div class="grid grid-cols-3 gap-6 h-full">
        <!-- Image principale et description - 1/3 de la largeur (col-span-1) -->
        <div class="col-span-1 flex flex-col gap-4 overflow-y-auto pr-4">
          <!-- Photo principale -->
          <x-photo-section :plant="$plant" />

          <!-- Description sous la photo -->
          @if($plant->description)
            <div class="bg-gray-50 p-3 rounded-lg border-l-4 border-green-500">
              <h3 class="text-sm font-semibold text-gray-700 uppercase tracking-wide">Description</h3>
              <p class="mt-2 text-gray-700 leading-relaxed text-sm">{{ $plant->description }}</p>
            </div>
          @endif
        </div>

        <!-- Cartes à droite - 2/3 de la largeur (col-span-2) -->
        <aside class="col-span-2 overflow-y-auto pr-4">
          <div class="grid grid-cols-2 gap-4">
            <!-- Cartes colonne gauche -->
            <div class="space-y-4">
              <!-- Besoins en arrosage et lumière -->
              <div class="bg-yellow-50 p-3 rounded-lg border-l-4 border-yellow-500">
                <div class="text-center">
                  <h3 class="text-sm font-semibold text-gray-700 uppercase tracking-wide">Besoins</h3>
                </div>

                @php
                  $wf = $plant->watering_frequency ?? 3;
                  $lf = $plant->light_requirement ?? 3;
                  $wIcon = \App\Models\Plant::$wateringIcons[$wf] ?? 'droplet';
                  $lIcon = \App\Models\Plant::$lightIcons[$lf] ?? 'sun';
                  $wColor = \App\Models\Plant::$wateringColors[$wf] ?? 'blue';
                  $lColor = \App\Models\Plant::$lightColors[$lf] ?? 'yellow';
                  $wLabel = \App\Models\Plant::$wateringLabels[$wf] ?? 'N/A';
                  $lLabel = \App\Models\Plant::$lightLabels[$lf] ?? 'N/A';
                @endphp

                <div class="mt-3 flex items-center justify-around gap-6">
                  <!-- Arrosage -->
                  <div class="flex flex-col items-center gap-1" title="Arrosage : {{ $wLabel }}">
                    <span class="text-xs text-gray-600 font-medium">Arrosage</span>
                    <i data-lucide="{{ $wIcon }}" class="w-8 h-8 text-{{ $wColor }}"></i>
                    <span class="text-xs text-gray-600">{{ $wLabel }}</span>
                  </div>

                  <!-- Lumière -->
                  <div class="flex flex-col items-center gap-1" title="Lumière : {{ $lLabel }}">
                    <span class="text-xs text-gray-600 font-medium">Lumière</span>
                    <i data-lucide="{{ $lIcon }}" class="w-8 h-8 text-{{ $lColor }}"></i>
                    <span class="text-xs text-gray-600">{{ $lLabel }}</span>
                  </div>
                </div>
              </div>

              @if($plant->temperature_min || $plant->temperature_max || $plant->humidity_level)
                <!-- Température & Humidité -->
                <div class="bg-red-50 p-3 rounded-lg border-l-4 border-red-500 col-span-2">
                  <div class="text-center">
                    <h3 class="text-sm font-semibold text-gray-700 uppercase tracking-wide">Température & Humidité</h3>
                  </div>
                  <div class="mt-3 flex items-center justify-around gap-6">
                    <!-- Température -->
                    <div class="flex flex-col items-center gap-1 min-w-32">
                      <span class="text-xs text-gray-600 font-medium">Température</span>
                      <div class="text-gray-800 text-sm font-medium">
                        @if($plant->temperature_min || $plant->temperature_max)
                          @php
                            $minTemp = $plant->temperature_min ?? '?';
                            $maxTemp = $plant->temperature_max ?? '?';
                          @endphp
                          <div>{{ $minTemp }}°- {{ $maxTemp }}°</div>
                        @else
                          <div class="text-gray-500">—</div>
                        @endif
                      </div>
                    </div>
                    <!-- Humidité -->
                    <div class="flex flex-col items-center gap-1 min-w-32">
                      <span class="text-xs text-gray-600 font-medium">Humidité</span>
                      <div class="text-gray-800 text-sm font-medium">
                        @if($plant->humidity_level)
                          <div>{{ $plant->humidity_level }}%</div>
                        @else
                          <div class="text-gray-500">—</div>
                        @endif
                      </div>
                    </div>
                  </div>
                </div>
              @endif
            </div>

            <!-- Cartes colonne droite -->
            <div class="space-y-4">
              <!-- Historique d'arrosage (petit) -->
              <div class="bg-blue-50 p-3 rounded-lg border-l-4 border-blue-500">
                <div class="flex items-center justify-between">
                  <div class="flex items-center gap-2">
                    <i data-lucide="droplet" class="w-4 h-4 text-blue-600"></i>
                    <h3 class="text-sm font-semibold text-blue-900">Arrosage</h3>
                  </div>
                  <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" id="quickWateringCheckbox" class="w-4 h-4 text-blue-600 rounded focus:ring-blue-500" onclick="openQuickWateringModal()">
                  </label>
                </div>
                @if($plant->last_watering_date)
                  <p class="text-xs text-blue-600 mt-2">Dernier : {{ $plant->last_watering_date->format('d/m/Y H:i') }}</p>
                @else
                  <p class="text-xs text-blue-600 mt-2">Aucun enregistrement</p>
                @endif
                <a href="{{ route('plants.watering-history.index', $plant) }}" class="text-xs text-blue-500 hover:text-blue-700 mt-1 inline-block">Gérer →</a>
              </div>

              <!-- Historique de fertilisation (petit) -->
              <div class="bg-green-50 p-3 rounded-lg border-l-4 border-green-500">
                <div class="flex items-center gap-2">
                  <i data-lucide="leaf" class="w-4 h-4 text-green-600"></i>
                  <h3 class="text-sm font-semibold text-green-900">Fertilisation</h3>
                </div>
                @if($plant->last_fertilizing_date)
                  <p class="text-xs text-green-600 mt-2">Dernier : {{ $plant->last_fertilizing_date->format('d/m/Y H:i') }}</p>
                @else
                  <p class="text-xs text-green-600 mt-2">Aucun enregistrement</p>
                @endif
                <a href="{{ route('plants.fertilizing-history.index', $plant) }}" class="text-xs text-green-500 hover:text-green-700 mt-1 inline-block">Gérer →</a>
              </div>

              <!-- Historique de rempotage (petit) -->
              <div class="bg-amber-50 p-3 rounded-lg border-l-4 border-amber-500">
                <div class="flex items-center gap-2">
                  <i data-lucide="flower-pot" class="w-4 h-4 text-amber-600"></i>
                  <h3 class="text-sm font-semibold text-amber-900">Rempotage</h3>
                </div>
                @if($plant->last_repotting_date)
                  <p class="text-xs text-amber-600 mt-2">Dernier : {{ $plant->last_repotting_date->format('d/m/Y H:i') }}</p>
                @else
                  <p class="text-xs text-amber-600 mt-2">Aucun enregistrement</p>
                @endif
                <a href="{{ route('plants.repotting-history.index', $plant) }}" class="text-xs text-amber-500 hover:text-amber-700 mt-1 inline-block">Gérer →</a>
              </div>
            </div>
          </div>
        </aside>
      </div>
    </div>

    <!-- Galerie - occupe le 1/3 inférieur -->
    <x-gallery :plant="$plant" />
  </div>
</div>

<script>
  window.globalLightboxImages = [
    @if($plant->main_photo)
      { url: {!! json_encode(Storage::url($plant->main_photo)) !!}, caption: {!! json_encode($plant->name) !!} }{{ $plant->photos->count() ? ',' : '' }}
    @endif
    @foreach($plant->photos as $photo)
      { url: {!! json_encode(Storage::url($photo->filename)) !!}, caption: {!! json_encode($photo->description ?? '') !!} }{{ !$loop->last ? ',' : '' }}
    @endforeach
  ];

  // Quick watering modal functions
  function openQuickWateringModal() {
    const checkbox = document.getElementById('quickWateringCheckbox');
    if (checkbox.checked) {
      const now = new Date();
      const dateStr = now.toISOString().slice(0, 16);
      document.getElementById('quickWateringDate').value = dateStr;
      document.getElementById('quickWateringModal').classList.remove('hidden');
      document.getElementById('quickWateringModal').classList.add('flex');
    } else {
      closeQuickWateringModal();
    }
  }

  function closeQuickWateringModal() {
    document.getElementById('quickWateringCheckbox').checked = false;
    document.getElementById('quickWateringModal').classList.add('hidden');
    document.getElementById('quickWateringModal').classList.remove('flex');
  }

  function submitQuickWatering() {
    const form = document.getElementById('quickWateringForm');
    form.submit();
  }

  // Close modal when clicking outside
  document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById('quickWateringModal');
    modal.addEventListener('click', function(e) {
      if (e.target === modal) {
        closeQuickWateringModal();
      }
    });
  });
</script>

<!-- Quick Watering Modal -->
<div id="quickWateringModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
  <div class="bg-white rounded-lg shadow-lg p-6 w-96">
    <div class="flex justify-between items-center mb-4">
      <h2 class="text-xl font-bold text-gray-800">Arrosage rapide</h2>
      <button type="button" onclick="closeQuickWateringModal()" class="text-gray-500 hover:text-gray-700">
        <i data-lucide="x" class="w-5 h-5"></i>
      </button>
    </div>

    <form id="quickWateringForm" action="{{ route('plants.watering-history.store', $plant) }}" method="POST">
      @csrf

      <div class="mb-4">
        <label class="block text-gray-700 font-medium mb-2" for="quickWateringDate">
          Date et heure <span class="text-red-500">*</span>
        </label>
        <input type="datetime-local" id="quickWateringDate" name="watering_date" required class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:border-blue-500">
      </div>

      <div class="mb-6">
        <label class="block text-gray-700 font-medium mb-2" for="quickWateringAmount">
          Quantité
        </label>
        <div class="flex items-center gap-2">
          <input type="number" id="quickWateringAmount" name="amount" placeholder="500" step="50" class="flex-1 px-3 py-2 border border-gray-300 rounded focus:outline-none focus:border-blue-500">
          <span class="text-gray-600 font-medium">ml</span>
        </div>
      </div>

      <div class="flex justify-end gap-3">
        <button type="button" onclick="closeQuickWateringModal()" class="px-4 py-2 bg-gray-300 hover:bg-gray-400 text-gray-800 rounded transition">
          Annuler
        </button>
        <button type="submit" class="px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white rounded transition">
          Enregistrer
        </button>
      </div>
    </form>
  </div>
</div>

@include('partials.lightbox')
@endsection

@section('extra-scripts')
  <script src="https://unpkg.com/lightbox2@2.11.4/dist/js/lightbox.min.js"></script>
  <script>
    // Gestionnaire pour les miniatures de la galerie
    document.addEventListener('DOMContentLoaded', function() {
      document.addEventListener('click', function(e) {
        // Si c'est un bouton avec data-type="thumbnail", échanger avec la photo principale
        if (e.target.closest('[data-type="thumbnail"]')) {
          const btn = e.target.closest('[data-type="thumbnail"]');
          const mainPhoto = document.querySelector('[data-type="main-photo"]');
          
          if (!mainPhoto) return;
          
          const thumbImg = btn.querySelector('img');
          if (!thumbImg) return;
          
          // Échanger les src
          const mainSrc = mainPhoto.src;
          const thumbSrc = thumbImg.src;
          
          mainPhoto.src = thumbSrc;
          thumbImg.src = mainSrc;
          
          // Échanger aussi data-original-src
          const mainOrig = mainPhoto.getAttribute('data-original-src');
          const thumbOrig = btn.getAttribute('data-original-src');
          
          if (mainOrig) mainPhoto.setAttribute('data-original-src', thumbOrig);
          if (thumbOrig) btn.setAttribute('data-original-src', mainOrig);
        }
      });
    });
  </script>
@endsection