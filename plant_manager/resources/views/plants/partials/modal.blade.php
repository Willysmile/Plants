<div class="bg-white rounded-lg shadow-lg overflow-hidden" style="width:900px;height:600px;max-width:calc(100vw - 40px);">
  <div class="h-full grid grid-cols-1 lg:grid-cols-2" style="height:100%;">
    <div class="flex flex-col p-3" style="overflow:hidden;">
      <!-- Noms au-dessus de la photo principale -->
      <div class="mb-2">
        <h2 class="text-xl font-semibold">{{ $plant->name }}</h2>
        @if($plant->scientific_name)
          <div class="text-sm italic text-gray-500 mt-1">{{ $plant->scientific_name }}</div>
        @endif
      </div>

      <div class="rounded overflow-hidden mb-3" style="flex:0 0 60%; min-height:0;">
        @if($plant->main_photo)
          <button type="button" onclick="openLightboxGlobal(0)" style="background:none;border:0;padding:0;">
            <img src="{{ Storage::url($plant->main_photo) }}" alt="{{ $plant->name }}" style="width:100%;height:100%;object-fit:cover;display:block;">
          </button>
        @elseif($plant->photos->count())
          <button type="button" onclick="openLightboxGlobal(0)" style="background:none;border:0;padding:0;">
            <img src="{{ Storage::url($plant->photos->first()->filename) }}" alt="{{ $plant->name }}" style="width:100%;height:100%;object-fit:cover;display:block;">
          </button>
        @else
          <div class="w-full h-full flex items-center justify-center text-gray-400">Pas d'image</div>
        @endif
      </div>

      <div class="pt-2" style="flex:0 0 40%; min-height:0;">
        <h3 class="font-medium text-sm mb-2 text-center">Galerie</h3>

        @php
          $gallery = $plant->photos->filter(function($p) use ($plant){
            if ($plant->main_photo && $p->filename === $plant->main_photo) return false;
            if (isset($p->is_main) && $p->is_main) return false;
            return true;
          })->values();
          $lightboxStart = $plant->main_photo ? 1 : 0;
        @endphp

        <div class="flex justify-center">
          <div class="grid grid-cols-3 gap-2">
            @forelse($gallery as $i => $photo)
              <button type="button" onclick="openLightboxGlobal({{ $lightboxStart + $i }})" style="aspect-ratio:1/1; width:100px; height:100px; padding:0; border:0; background:transparent;" aria-label="Ouvrir image">
                <img src="{{ Storage::url($photo->filename) }}" alt="{{ $photo->description ?? $plant->name }}" style="width:100%;height:100%;object-fit:cover;display:block;border-radius:6px;">
              </button>
            @empty
              <div class="col-span-3 text-center text-sm text-gray-500">Aucune image</div>
            @endforelse
          </div>
        </div>
      </div>
    </div>

    <div class="p-4 overflow-auto" style="height:100%;">
      <div class="flex items-start justify-between mb-3">
        <div class="flex items-center gap-2">
          <a href="{{ route('plants.show', $plant) }}" class="px-3 py-1 bg-gray-100 rounded text-sm">Voir la fiche</a>
          <a href="{{ route('plants.edit', $plant) }}" class="px-3 py-1 bg-yellow-500 text-white rounded text-sm">Éditer</a>
          <button onclick="window.closeModal && window.closeModal()" class="px-3 py-1 bg-gray-200 rounded text-sm">Fermer</button>
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