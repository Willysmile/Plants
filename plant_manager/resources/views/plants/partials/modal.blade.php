<div class="bg-white rounded-lg shadow-lg overflow-hidden" style="width:900px;height:750px;max-width:calc(100vw - 40px);" id="plant-modal-{{ $plant->id }}" data-modal-plant-id="{{ $plant->id }}">
  <div class="h-full flex flex-col">
    <!-- En-tête -->
    <div class="flex items-center justify-between p-3 border-b">
      <div class="flex items-center gap-3">
        <div>
          <h2 class="text-lg font-semibold">{{ $plant->name }}</h2>
          @if($plant->scientific_name)
            <div class="text-sm italic text-gray-500">{{ $plant->scientific_name }}</div>
          @endif
        </div>
        <!-- Catégorie à côté du titre -->
        <span class="inline-flex items-center px-3 py-1 rounded-full bg-blue-100 text-blue-800 text-xs font-medium border border-blue-200">
          {{ $plant->category->name ?? '—' }}
        </span>
      </div>
      <div class="flex items-center gap-2">
        <a href="{{ route('plants.show', $plant) }}" class="px-3 py-1 bg-gray-100 rounded text-sm">Voir</a>
        <a href="{{ route('plants.edit', $plant) }}" class="px-3 py-1 bg-yellow-500 text-white rounded text-sm">Éditer</a>
        <button class="px-3 py-1 bg-gray-200 rounded text-sm modal-close">Fermer</button>
      </div>
    </div>

    <!-- Contenu principal -->
    <div class="flex-1 overflow-hidden flex p-3 gap-4">
      <!-- Colonne gauche (1/2) : Photo + Description + Galerie -->
      <div class="w-1/2 flex flex-col gap-3 overflow-y-auto pr-2">
        <!-- Photo principale -->
        <div class="rounded overflow-hidden flex-shrink-0" style="aspect-ratio:4/3; display:flex; align-items:center; justify-content:center; background-color:#f8f8f8;">
          @if($plant->main_photo)
            <img id="main-photo-display" 
                 src="{{ Storage::url($plant->main_photo) }}" 
                 alt="{{ $plant->name }}" 
                 data-original-src="{{ Storage::url($plant->main_photo) }}"
                 data-type="main-photo"
                 style="max-width:100%; max-height:100%; object-fit:contain; display:block; cursor:pointer;">
          @elseif($plant->photos->count())
            <img id="main-photo-display" 
                 src="{{ Storage::url($plant->photos->first()->filename) }}" 
                 alt="{{ $plant->name }}" 
                 data-original-src="{{ Storage::url($plant->photos->first()->filename) }}"
                 data-type="main-photo"
                 style="max-width:100%; max-height:100%; object-fit:contain; display:block; cursor:pointer;">
          @else
            <x-empty-state message="Pas d'image" height="h-full" />
          @endif
        </div>

        <!-- Description -->
        @if($plant->description)
          <div class="bg-gray-50 p-3 rounded-lg border-l-4 border-green-500 text-sm">
            <h3 class="text-xs font-semibold text-gray-700 uppercase tracking-wide text-center">Description</h3>
            <p class="mt-2 text-gray-700 leading-relaxed text-xs">{{ $plant->description }}</p>
          </div>
        @endif

        <!-- Historiques (3 cartes) - Affichées horizontalement -->
        <div class="grid grid-cols-3 gap-2" id="modal-histories-container-{{ $plant->id }}">
          @include('plants.partials.watering-history-modal')
          @include('plants.partials.fertilizing-history-modal')
          @include('plants.partials.repotting-history-modal')
        </div>
      </div>

      <!-- Colonne droite (1/2) : Cartes en 2 colonnes + Galerie fixe en bas -->
      <div class="w-1/2 flex flex-col">
        <!-- Cartes scrollables -->
        <div class="overflow-y-auto pr-2 flex-1">
          <div class="grid grid-cols-2 gap-3">
            <!-- Besoins -->
            <div class="bg-yellow-50 p-2 rounded-lg border-l-4 border-yellow-500">
            <div class="text-center">
              <h3 class="text-xs font-semibold text-gray-700 uppercase tracking-wide">Besoins</h3>
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

            <div class="mt-2 flex items-center justify-around gap-3">
              <div class="flex flex-col items-center gap-1">
                <span class="text-xs text-gray-600">Arrosage</span>
                <i data-lucide="{{ $wIcon }}" class="w-5 h-5 text-{{ $wColor }}"></i>
                <span class="text-xs text-gray-600">{{ $wLabel }}</span>
              </div>

              <div class="flex flex-col items-center gap-1">
                <span class="text-xs text-gray-600">Lumière</span>
                <i data-lucide="{{ $lIcon }}" class="w-5 h-5 text-{{ $lColor }}"></i>
                <span class="text-xs text-gray-600">{{ $lLabel }}</span>
              </div>
            </div>
          </div>

          <!-- Tags -->
          <div class="bg-purple-50 p-2 rounded-lg border-l-4 border-purple-500">
            <div class="text-center">
              <h3 class="text-xs font-semibold text-gray-700 uppercase tracking-wide">Tags</h3>
            </div>
            <div class="mt-2 text-center text-xs text-gray-800">{{ $plant->tags->pluck('name')->join(', ') ?: '—' }}</div>
          </div>

          <!-- Température & Humidité -->
          @if($plant->temperature_min || $plant->temperature_max || $plant->humidity_level)
            <div class="bg-red-50 p-2 rounded-lg border-l-4 border-red-500">
              <div class="text-center">
                <h3 class="text-xs font-semibold text-gray-700 uppercase tracking-wide">Température</h3>
              </div>
              <div class="mt-2 flex flex-col items-center gap-1">
                <span class="text-xs text-gray-600">Valeurs</span>
                <div class="text-gray-800 text-xs font-medium">
                  @if($plant->temperature_min || $plant->temperature_max)
                    @php
                      $minTemp = $plant->temperature_min ?? '?';
                      $maxTemp = $plant->temperature_max ?? '?';
                    @endphp
                    {{ $minTemp }}°C-{{ $maxTemp }}°C
                  @else
                    —
                  @endif
                </div>
              </div>
            </div>
          @endif

          <!-- Humidité -->
          @if($plant->humidity_level)
            <div class="bg-cyan-50 p-2 rounded-lg border-l-4 border-cyan-500">
              <div class="text-center">
                <h3 class="text-xs font-semibold text-gray-700 uppercase tracking-wide">Humidité</h3>
              </div>
              <div class="mt-2 flex flex-col items-center gap-1">
                <span class="text-xs text-gray-600">Taux</span>
                <div class="text-gray-800 text-xs font-medium">{{ $plant->humidity_level }}%</div>
              </div>
            </div>
          @endif

          <!-- Notes -->
          @if($plant->notes)
            <div class="bg-indigo-50 p-2 rounded-lg border-l-4 border-indigo-500 col-span-2">
              <div class="text-center">
                <h3 class="text-xs font-semibold text-gray-700 uppercase tracking-wide">Notes</h3>
              </div>
              <p class="mt-2 text-gray-700 leading-relaxed text-xs">{{ $plant->notes }}</p>
            </div>
          @endif

          <!-- Date d'achat -->
          @if($plant->purchase_date)
            <div class="bg-teal-50 p-2 rounded-lg border-l-4 border-teal-500 col-span-2">
              <div class="text-center">
                <h3 class="text-xs font-semibold text-gray-700 uppercase tracking-wide">Date d'achat</h3>
              </div>
              <div class="mt-2 text-center text-xs font-medium text-gray-800">{{ $plant->formatted_purchase_date }}</div>
            </div>
          @endif
          </div>
        </div>

        <!-- Galerie fixe en bas -->
        @php
          $gallery = $plant->photos->filter(function($p) use ($plant){
            if ($plant->main_photo && $p->filename === $plant->main_photo) return false;
            if (isset($p->is_main) && $p->is_main) return false;
            return true;
          })->values();
          $maxGallery = 2;
        @endphp

        <div class="border-t pt-2 mt-2">
          <h3 class="font-medium text-xs mb-2 text-center uppercase">Galerie</h3>
          @if($gallery->count())
            <div class="flex justify-center gap-2">
              @for($i = 0; $i < min($maxGallery, $gallery->count()); $i++)
                <button type="button" 
                       class="gallery-thumbnail"
                       data-type="thumbnail"
                       data-index="{{ $i + 1 }}"
                       data-original-src="{{ Storage::url($gallery[$i]->filename) }}"
                       style="aspect-ratio:1/1; width:70px; height:70px; padding:0; border:0; background:transparent; cursor:pointer; display:flex; align-items:center; justify-content:center; background-color:#f8f8f8;" 
                       aria-label="Échanger avec la photo principale">
                  <img src="{{ Storage::url($gallery[$i]->filename) }}" 
                       alt="{{ $gallery[$i]->description ?? $plant->name }}" 
                       style="max-width:100%; max-height:100%; object-fit:cover; border-radius:4px;">
                </button>
              @endfor

              <!-- Points toujours affichés -->
              <a href="{{ route('plants.show', $plant) }}" 
                 style="width:70px; height:70px; padding:0; background:transparent; display:flex; align-items:center; justify-content:center; border-radius:4px; text-decoration:none; transition:all 0.2s;" 
                 class="more-photos" 
                 aria-label="Voir la galerie complète" 
                 onmouseover="this.style.border='1px solid #15803d'; this.querySelector('span').style.color='#15803d';" 
                 onmouseout="this.style.border='0'; this.querySelector('span').style.color='#999';">
                <span style="font-size:32px; color:#999; line-height:0.5; transition:color 0.2s;">⋯</span>
              </a>
            </div>
          @else
            <div class="flex items-center justify-center h-20 text-gray-400">
              <div class="text-center">
                <svg class="w-8 h-8 mx-auto mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
                <p class="text-xs">Aucune photo</p>
              </div>
            </div>
          @endif
        </div>
      </div>
    </div>

    <!-- Quick Entry Modals (extracted to separate components) -->
    <x-quick-watering-modal :plant="$plant" />
    <x-quick-fertilizing-modal :plant="$plant" />
    <x-quick-repotting-modal :plant="$plant" />
  </div>

  <script type="application/json" data-lightbox-images>
[
  @if($plant->main_photo)
    { "url": {!! json_encode(Storage::url($plant->main_photo)) !!}, "caption": {!! json_encode($plant->name) !!} }@if($gallery->count()),@endif
  @endif
  @foreach($gallery as $p)
    { "url": {!! json_encode(Storage::url($p->filename)) !!}, "caption": {!! json_encode($p->description ?? '') !!} }@if(!$loop->last),@endif
  @endforeach
]
  </script>
  
  <!-- Lucide Icons -->
  <script src="https://unpkg.com/lucide@latest"></script>
  <script>
    // Initialiser les icônes quand la modale est chargée
    if (typeof lucide !== 'undefined') {
      lucide.createIcons();
    }
  </script>

</div>
</div>