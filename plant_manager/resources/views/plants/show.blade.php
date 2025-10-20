@extends('layouts.app')

@section('title', $plant->name)

@section('content')
<div class="h-[98vh] max-w-6xl mx-auto flex flex-col">
  <div class="bg-white rounded-lg shadow flex flex-col flex-grow overflow-hidden" data-modal-plant-id="{{ $plant->id }}">
    <!-- En-tête avec titre et boutons d'action -->
    <div class="flex items-start justify-between p-4 border-b">
      <div class="flex-1">
        <div class="flex items-start gap-4">
          <div>
            @if($plant->scientific_name)
              <h1 class="text-3xl font-semibold italic text-green-700">{{ $plant->scientific_name }}</h1>
            @endif
            <div class="flex gap-2 mt-2 items-center">
              @if($plant->family)
                <p class="text-xs uppercase font-bold text-gray-500 tracking-wide">{{ $plant->family }}</p>
                @if($plant->subfamily)
                  <p class="text-xs font-medium text-gray-600">{{ $plant->subfamily }}</p>
                @endif
              @endif
            </div>
            @if($plant->name)
              <p class="text-base text-gray-700 mt-2">{{ $plant->name }}</p>
            @endif
          </div>
        </div>
      </div>

      <!-- Référence badge réduit -->
      <div class="flex gap-2 ml-4">
        @if($plant->reference)
          <div class="bg-purple-50 px-3 py-2 rounded border border-purple-200">
            <p class="text-xs text-gray-600 font-medium">Référence</p>
            <p class="text-sm text-purple-700 font-mono font-semibold">{{ $plant->reference }}</p>
          </div>
        @endif
      </div>

      <div class="flex items-center gap-2 ml-4">
        <button type="button" onclick="refreshShowPage()" class="px-3 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600 transition flex items-center gap-2" title="Actualiser la page">
          <i data-lucide="refresh-cw" class="w-4 h-4"></i>
        </button>
        @if(!$plant->is_archived)
          <button type="button" onclick="confirmArchive()" class="px-4 py-2 bg-red-500 hover:bg-red-600 text-white rounded-md transition" title="Archiver cette plante">
            📦 Archiver
          </button>
        @else
          <form action="{{ route('plants.restore', $plant) }}" method="POST" style="display: inline;">
            @csrf
            <button type="submit" class="px-4 py-2 bg-green-500 hover:bg-green-600 text-white rounded-md transition">
              ↺ Restaurer
            </button>
          </form>
        @endif
        <a href="{{ route('plants.edit', $plant) }}" class="px-4 py-2 bg-yellow-500 hover:bg-yellow-600 text-white rounded-md transition">Modifier</a>
        <a href="{{ route('plants.index') }}" class="px-4 py-2 bg-gray-400 hover:bg-gray-500 text-white rounded-md transition">Retour</a>
      </div>
    </div>

    <!-- Contenu principal - occupe les 2/3 supérieurs -->
    <div class="flex-grow overflow-hidden p-4" style="height: 66%;">
      <div class="flex gap-6 h-full">
        <!-- Image principale et description - 45% de la largeur -->
        <div class="flex flex-col gap-4 overflow-y-auto pr-4" style="width: 45%; flex-shrink: 0;">
          <!-- Photo principale -->
          <x-photo-section :plant="$plant" />

          <!-- Description sous la photo -->
          @if($plant->description)
            <div class="bg-gray-50 p-3 rounded-lg border-l-4 border-green-500">
              <h3 class="text-sm font-semibold text-gray-700 uppercase tracking-wide">Description</h3>
              <p class="mt-2 text-gray-700 leading-relaxed text-sm break-words">{{ $plant->description }}</p>
            </div>
          @endif

          <!-- Tags -->
          @if($plant->tags->count() > 0)
            <div class="bg-purple-50 p-3 rounded-lg border-l-4 border-purple-500">
              <h3 class="text-sm font-semibold text-gray-700 uppercase tracking-wide">Tags</h3>
              <div class="mt-2 flex flex-wrap gap-2">
                @foreach($plant->tags as $tag)
                  <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800 border border-purple-200">
                    {{ $tag->name }}
                  </span>
                @endforeach
              </div>
            </div>
          @endif
        </div>

        <!-- Cartes à droite - 55% de la largeur -->
        <aside class="overflow-y-auto pr-4 flex-1 flex flex-col">
          <div class="space-y-4 flex-1">
            <!-- Besoins et Température sur la même ligne -->
            <div class="grid grid-cols-2 gap-3">
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
                <div class="bg-red-50 p-3 rounded-lg border-l-4 border-red-500">
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

            <!-- Historiques sur la même ligne (3 colonnes) -->
            <div class="grid grid-cols-3 gap-2">
              <x-history-card :plant="$plant" type="watering" context="show" />
              <x-history-card :plant="$plant" type="fertilizing" context="show" />
              <x-history-card :plant="$plant" type="repotting" context="show" />
            </div>
          </div>

          <!-- Localisation (Emplacement, Date, Lieu d'achat) EN BAS -->
          <div class="grid gap-2 text-xs mt-4" style="grid-template-columns: repeat(auto-fit, minmax(0, 1fr));">
            @if($plant->location)
              <div class="bg-green-50 p-2 rounded border border-green-200">
                <p class="text-gray-600 font-medium text-xs">Emplacement</p>
                <p class="text-green-700 font-semibold">{{ $plant->location }}</p>
              </div>
            @endif
            
            @if($plant->purchase_date)
              <div class="bg-blue-50 p-2 rounded border border-blue-200">
                <p class="text-gray-600 font-medium text-xs">Date d'achat</p>
                <p class="text-blue-700 font-semibold">{{ $plant->formatted_purchase_date ?? $plant->purchase_date }}</p>
              </div>
            @endif
            
            @if($plant->purchase_place)
              <div class="bg-orange-50 p-2 rounded border border-orange-200">
                <p class="text-gray-600 font-medium text-xs">Lieu d'achat</p>
                <p class="text-orange-700 font-semibold">{{ $plant->purchase_place }}</p>
              </div>
            @endif
          </div>

          <!-- Infos Diverses -->
          @include('plants.partials.histories_free')
        </aside>
      </div>
    </div>

    <!-- Modale pour les Infos Diverses (Historique libre) -->
    @if($plant->histories && $plant->histories->count() > 0)
      <div id="free-histories-modal-{{ $plant->id }}" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white rounded-lg shadow-xl max-w-2xl w-full max-h-[80vh] overflow-y-auto m-4">
          <!-- Header -->
          <div class="flex items-center justify-between p-4 border-b sticky top-0 bg-white">
            <h3 class="text-lg font-semibold text-gray-800">Infos Diverses</h3>
            <button type="button" 
                    onclick="document.getElementById('free-histories-modal-{{ $plant->id }}').style.display='none'" 
                    class="text-gray-500 hover:text-gray-700">
              <i data-lucide="x" class="w-5 h-5"></i>
            </button>
          </div>

          <!-- Contenu -->
          <div class="p-4 space-y-3">
            @foreach($plant->histories->sortByDesc('created_at')->take(3) as $history)
              <div class="bg-gray-50 p-3 rounded border border-gray-200">
                <div class="text-xs text-gray-500 font-medium">
                  {{ $history->created_at->format('d/m/Y H:i') }}
                </div>
                <div class="text-sm text-gray-800 mt-2 whitespace-pre-wrap break-words">
                  {{ $history->body }}
                </div>
              </div>
            @endforeach
          </div>
        </div>
      </div>
    @endif

    <!-- Galerie - occupe le 1/3 inférieur -->
    <x-gallery :plant="$plant" :maxThumbnails="99" />
  </div>
</div>

<script>
  @php
    // Filtrer les photos de galerie (exclure la photo principale)
    $galleryPhotos = $plant->photos->filter(function($p) use ($plant){
      if ($plant->main_photo && $p->filename === $plant->main_photo) return false;
      if (isset($p->is_main) && $p->is_main) return false;
      return true;
    })->values();
  @endphp

  window.globalLightboxImages = [
    @if($plant->main_photo)
      { url: {!! json_encode(Storage::url($plant->main_photo)) !!}, caption: {!! json_encode($plant->name) !!} }{{ $galleryPhotos->count() ? ',' : '' }}
    @endif
    @foreach($galleryPhotos as $photo)
      { url: {!! json_encode(Storage::url($photo->filename)) !!}, caption: {!! json_encode($photo->description ?? '') !!} }{{ !$loop->last ? ',' : '' }}
    @endforeach
  ];
  
  // 🔧 FIX: Sauvegarder l'array original pour pouvoir le restaurer lors du déswap
  window.globalLightboxImagesOriginal = JSON.parse(JSON.stringify(window.globalLightboxImages));

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

<!-- Formulaire caché pour l'archivage -->
<form id="archiveForm" action="{{ route('plants.archive', $plant) }}" method="POST" style="display: none;">
  @csrf
  <input type="hidden" name="reason" id="reasonInput" value="">
</form>

<!-- Modale de confirmation d'archivage -->
<div id="archiveModal" class="fixed inset-0 bg-black bg-opacity-50 hidden flex items-center justify-center z-50">
  <div class="bg-white rounded-lg shadow-xl p-6 max-w-md w-full mx-4">
    <h3 class="text-lg font-bold text-gray-800 mb-2">Archiver cette plante ?</h3>
    <p class="text-gray-600 mb-4">
      La plante <strong>{{ $plant->name }}</strong> sera déplacée dans les archives.
      Vous pourrez la restaurer à tout moment.
    </p>
    
    <!-- Raison d'archivage optionnelle -->
    <textarea id="archiveReason" class="w-full border border-gray-300 rounded p-2 mb-4 text-sm" 
              placeholder="Raison optionnelle (ex: Plante morte, Donnée à un ami...)" rows="3"></textarea>
    
    <div class="flex gap-3">
      <button type="button" onclick="cancelArchive()" class="flex-1 px-4 py-2 bg-gray-300 hover:bg-gray-400 text-gray-800 rounded transition">
        Annuler
      </button>
      <button type="button" onclick="submitArchive()" class="flex-1 px-4 py-2 bg-red-500 hover:bg-red-600 text-white rounded transition">
        Archiver
      </button>
    </div>
  </div>
</div>

@endsection

@section('extra-scripts')
  <script src="{{ asset('js/gallery-manager.js') }}"></script>
  <script>
    // Actualiser la page show
    window.refreshShowPage = function() {
      const button = event.currentTarget;
      const icon = button.querySelector('[data-lucide="refresh-cw"]');
      
      // Add spinning animation
      if (icon) {
        icon.style.animation = 'spin 1s linear infinite';
      }
      
      // Reload the page after 500ms
      setTimeout(() => {
        location.reload();
      }, 500);
    };
    
    // Modale d'archivage
    window.confirmArchive = function() {
      document.getElementById('archiveModal').classList.remove('hidden');
    };
    
    window.cancelArchive = function() {
      document.getElementById('archiveModal').classList.add('hidden');
      document.getElementById('archiveReason').value = '';
    };
    
    window.submitArchive = function() {
      const reason = document.getElementById('archiveReason').value;
      const form = document.getElementById('archiveForm');
      const reasonInput = form.querySelector('input[name="reason"]');
      reasonInput.value = reason;
      form.submit();
    };
    
    // Initialiser le gestionnaire de galerie au chargement
    document.addEventListener('DOMContentLoaded', function() {
      if (typeof GalleryManager !== 'undefined') {
        GalleryManager.init();
      }
    });
  </script>
@endsection