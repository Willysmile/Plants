<div class="bg-white rounded-lg shadow-lg overflow-hidden" style="width:900px;height:750px;max-width:calc(100vw - 40px);" id="plant-modal-{{ $plant->id }}" data-modal-plant-id="{{ $plant->id }}">
  <div class="h-full grid grid-cols-1 lg:grid-cols-2" style="height:100%;">
    <div class="flex flex-col p-3" style="overflow:hidden;">
      <!-- Noms au-dessus de la photo principale -->
      <div class="mb-2">
        <h2 class="text-xl font-semibold">{{ $plant->name }}</h2>
        @if($plant->scientific_name)
        <div class="text-sm italic text-gray-500 mt-1">{{ $plant->scientific_name }}</div>
        @endif
      </div>

      <!-- Photo principale avec conteneur pour centrage -->
      <div class="rounded overflow-hidden mb-3" style="flex:0 0 50%; min-height:0; display:flex; align-items:center; justify-content:center; background-color:#f8f8f8;">
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
        <div class="w-full h-full flex items-center justify-center text-gray-400">Pas d'image</div>
        @endif
      </div>

      <!-- Galerie (5 photos max en grille 3+2) -->
      <div class="pt-2" style="flex:0 0 50%; min-height:0;">
        <h3 class="font-medium text-sm mb-2 text-center">Galerie</h3>

        @php
        $gallery = $plant->photos->filter(function($p) use ($plant){
          if ($plant->main_photo && $p->filename === $plant->main_photo) return false;
          if (isset($p->is_main) && $p->is_main) return false;
          return true;
        })->values();
        $maxGallery = 5;
        @endphp

        <div class="flex justify-center">
          <div id="gallery-grid" class="grid gap-2" style="grid-template-columns: repeat(3, 100px);">
            <!-- Première ligne : 3 photos -->
            @for($i = 0; $i < min(3, $gallery->count()); $i++)
              <button type="button" 
                     class="gallery-thumbnail"
                     data-type="thumbnail"
                     data-index="{{ $i }}"
                     data-original-src="{{ Storage::url($gallery[$i]->filename) }}"
                     style="aspect-ratio:1/1; width:100px; height:100px; padding:0; border:0; background:transparent; cursor:pointer; display:flex; align-items:center; justify-content:center; background-color:#f8f8f8;" 
                     aria-label="Échanger avec la photo principale">
                <img src="{{ Storage::url($gallery[$i]->filename) }}" 
                     alt="{{ $gallery[$i]->description ?? $plant->name }}" 
                     style="max-width:100%; max-height:100%; object-fit:cover; border-radius:6px;">
              </button>
            @endfor

            <!-- Deuxième ligne : 2 photos + éventuellement les 3 points -->
            @for($i = 3; $i < min($maxGallery, $gallery->count()); $i++)
              <button type="button" 
                     class="gallery-thumbnail"
                     data-type="thumbnail" 
                     data-index="{{ $i }}"
                     data-original-src="{{ Storage::url($gallery[$i]->filename) }}"
                     style="aspect-ratio:1/1; width:100px; height:100px; padding:0; border:0; background:transparent; cursor:pointer; display:flex; align-items:center; justify-content:center; background-color:#f8f8f8;" 
                     aria-label="Échanger avec la photo principale">
                <img src="{{ Storage::url($gallery[$i]->filename) }}" 
                     alt="{{ $gallery[$i]->description ?? $plant->name }}" 
                     style="max-width:100%; max-height:100%; object-fit:cover; border-radius:6px;">
              </button>
            @endfor

            <!-- 3 petits points, toujours affichés -->
            <a href="{{ route('plants.show', $plant) }}" 
               style="width:100px; height:100px; padding:0; background:transparent; display:flex; align-items:center; justify-content:center; border-radius:6px; text-decoration:none; transition:all 0.2s;" 
               class="more-photos" 
               aria-label="Voir la galerie complète" 
               onmouseover="this.style.border='1px solid #15803d'; this.querySelector('span').style.color='#15803d';" 
               onmouseout="this.style.border='0'; this.querySelector('span').style.color='#333';">
              <span style="font-size:48px; color:#333; line-height:0.5; transition:color 0.2s;">⋯</span>
            </a>
          </div>
        </div>
      </div>
    </div>

    <div class="p-4 overflow-auto" style="height:100%;">
      <div class="flex items-start justify-between mb-3">
        <div class="flex items-center gap-2">
          <a href="{{ route('plants.show', $plant) }}" class="px-3 py-1 bg-gray-100 rounded text-sm">Voir la fiche</a>
          <a href="{{ route('plants.edit', $plant) }}" class="px-3 py-1 bg-yellow-500 text-white rounded text-sm">Éditer</a>
          <button class="px-3 py-1 bg-gray-200 rounded text-sm modal-close">Fermer</button>
        </div>
      </div>

      @if($plant->description)
      <p class="text-sm text-gray-700 mb-3">{{ $plant->description }}</p>
      @endif

      <div class="mb-4">
        <div class="grid grid-cols-1 gap-2 text-sm text-gray-600">
          <div class="flex items-center gap-2">
            <strong class="w-24 text-gray-700">Catégorie :</strong>
            <div class="text-gray-800">{{ $plant->category->name ?? '—' }}</div>
          </div>
          <div class="flex items-center gap-2">
            <strong class="w-24 text-gray-700">Arrosage :</strong>
            <div class="text-gray-800">{{ \App\Models\Plant::$wateringLabels[$plant->watering_frequency] ?? $plant->watering_frequency }}</div>
          </div>
          <div class="flex items-center gap-2">
            <strong class="w-24 text-gray-700">Lumière :</strong>
            <div class="text-gray-800">{{ \App\Models\Plant::$lightLabels[$plant->light_requirement] ?? $plant->light_requirement }}</div>
          </div>
          <div class="flex items-center gap-2">
            <strong class="w-24 text-gray-700">Tags :</strong>
            <div class="text-gray-800">{{ $plant->tags->pluck('name')->join(', ') ?: '—' }}</div>
          </div>
        </div>
      </div>
    </div>
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
</div>