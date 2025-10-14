<div class="bg-white rounded-lg shadow-lg overflow-hidden" style="width:900px;height:600px;max-width:calc(100vw - 40px);">
  <div class="h-full grid grid-cols-1 lg:grid-cols-2" style="height:100%;">
    <!-- Colonne gauche : photo principale en haut + galerie en dessous -->
    <div class="flex flex-col p-3" style="overflow:hidden;">
      <div class="rounded overflow-hidden mb-3" style="flex:0 0 60%; min-height:0;">
        @if($plant->main_photo)
          <img src="{{ Storage::url($plant->main_photo) }}" alt="{{ $plant->name }}" style="width:100%;height:100%;object-fit:cover;display:block;">
        @elseif($plant->photos->count())
          <img src="{{ Storage::url($plant->photos->first()->filename) }}" alt="{{ $plant->name }}" style="width:100%;height:100%;object-fit:cover;display:block;">
        @else
          <div class="w-full h-full flex items-center justify-center text-gray-400">Pas d'image</div>
        @endif
      </div>

      <!-- Galerie sous la photo principale, 3 miniatures par ligne, n'affiche pas la main_photo -->
      <div class="pt-2" style="flex:0 0 40%; min-height:0;">
        <h3 class="font-medium text-sm mb-2 text-center">Galerie</h3>

        @php
          $galleryPhotos = $plant->photos->filter(function($p) use ($plant) {
              if ($plant->main_photo && ($p->filename === $plant->main_photo)) return false;
              if (isset($p->is_main) && $p->is_main) return false;
              return true;
          })->values();
          $galleryPhotos = $galleryPhotos->take(12);
          $mainExists = (bool) $plant->main_photo;
        @endphp

               <div class="flex justify-center">
          <div class="grid grid-cols-3 gap-2">
            @foreach($galleryPhotos as $photo)
              @php
                $index = $loop->index + ($mainExists ? 1 : 0);
              @endphp
              <button type="button"
                      class="js-open-lightbox relative rounded overflow-hidden"
                      data-lb-index="{{ $index }}"
                      style="aspect-ratio:1/1; width:100px; height:100px; padding:0; border:0; background:transparent;"
                      aria-label="Ouvrir image">
                <img src="{{ Storage::url($photo->filename) }}" alt="{{ $photo->description ?? $plant->name }}" style="width:100%;height:100%;object-fit:cover;display:block;">
              </button>
            @endforeach
            @if($galleryPhotos->isEmpty())
              <div class="col-span-3 text-center text-sm text-gray-500">Aucune image</div>
            @endif
          </div>
        </div>
      </div>
    </div>

    <!-- Colonne droite : contenu / détails / actions -->
    <div class="p-4 overflow-auto" style="height:100%;">
      <div class="flex items-start justify-between mb-3">
        <div>
          <h2 class="text-xl font-semibold">{{ $plant->name }}</h2>
          @if($plant->scientific_name)
            <div class="text-sm italic text-gray-500 mt-1">{{ $plant->scientific_name }}</div>
          @endif
        </div>

        <div class="flex items-center gap-2">
          <a href="{{ route('plants.show', $plant) }}" class="px-3 py-1 bg-gray-100 rounded text-sm">Voir la fiche</a>
          <a href="{{ route('plants.edit', $plant) }}" class="px-3 py-1 bg-yellow-500 text-white rounded text-sm">Éditer</a>
          <button onclick="window.closeModal()" class="px-3 py-1 bg-gray-200 rounded text-sm">Fermer</button>
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

  <!-- lightbox local (utilise la même logique que dans show) -->
  <div id="modal-lightbox" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,0.85);z-index:9999;align-items:center;justify-content:center;padding:20px;">
    <div style="position:relative;max-width:calc(100vw - 80px);max-height:calc(100vh - 80px);display:flex;flex-direction:column;align-items:center;">
      <button id="modal-lightbox-close" aria-label="Fermer" style="position:absolute;right:-8px;top:-8px;background:#fff;border-radius:6px;padding:6px 8px;border:0;cursor:pointer;z-index:2">✕</button>
      <img id="modal-lightbox-img" src="" alt="" style="max-width:100%;max-height:100%;object-fit:contain;border-radius:6px;box-shadow:0 10px 30px rgba(0,0,0,.6);">
      <div id="modal-lightbox-caption" style="color:#fff;margin-top:8px;text-align:center;max-width:100%;word-break:break-word"></div>
    </div>
  </div>

<script>
  // construction sécurisée du tableau d'images (main en premier si présente)
  window.modalImages = [
    @if($plant->main_photo)
      { url: {{ json_encode(Storage::url($plant->main_photo)) }}, caption: {{ json_encode($plant->name) }} }@if($plant->photos->filter(function($p) use ($plant){ return !($plant->main_photo && $p->filename === $plant->main_photo) && !(isset($p->is_main) && $p->is_main); })->count()),@endif
    @endif

    @foreach($plant->photos as $p)
      @if(!($plant->main_photo && $p->filename === $plant->main_photo) && !(isset($p->is_main) && $p->is_main))
        { url: {{ json_encode(Storage::url($p->filename)) }}, caption: {{ json_encode($p->description ?? '') }} }@if(!$loop->last),@endif
      @endif
    @endforeach
  ];

  // define lightbox opener if not already defined (fallback local)
  if (typeof window.openLightboxModal !== 'function') {
    window.openLightboxModal = function(i){
      if (!window.modalImages || !window.modalImages[i]) return;
      const overlay = document.getElementById('modal-lightbox');
      const img = document.getElementById('modal-lightbox-img');
      const cap = document.getElementById('modal-lightbox-caption');
      const closeBtn = document.getElementById('modal-lightbox-close');
      img.src = window.modalImages[i].url;
      cap.textContent = window.modalImages[i].caption || '';
      overlay.style.display = 'flex';
      document.body.style.overflow = 'hidden';
      function onOverlayClick(e){ if (e.target === overlay) closeLocal(); }
      function onKey(e){ if(e.key === 'Escape') closeLocal(); }
      overlay.addEventListener('click', onOverlayClick, { once: true });
      closeBtn.addEventListener('click', closeLocal, { once: true });
      document.addEventListener('keydown', onKey);
      function closeLocal(){
        overlay.style.display = 'none';
        img.src = '';
        cap.textContent = '';
        document.body.style.overflow = '';
        document.removeEventListener('keydown', onKey);
      }
    };
  }

  // delegation : fonctionne si la partial est injectée dynamiquement
  document.addEventListener('click', function(e){
    const btn = e.target.closest('.js-open-lightbox');
    if (!btn) return;
    const idx = parseInt(btn.dataset.lbIndex, 10);
    if (!Number.isNaN(idx)) window.openLightboxModal(idx);
  });

  // debug helper : vérifie si le script s'exécute
  console.log('[modal] lightbox init, images=', (window.modalImages || []).length);
</script>
</div>