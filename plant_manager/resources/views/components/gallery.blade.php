@props(['plant', 'maxThumbnails' => 2])

@php
  $gallery = $plant->photos->filter(function($p) use ($plant){
    if ($plant->main_photo && $p->filename === $plant->main_photo) return false;
    if (isset($p->is_main) && $p->is_main) return false;
    return true;
  })->values();
@endphp

<div class="border-t pt-2 mt-2">
  <h3 class="font-medium text-xs mb-2 text-center uppercase">Galerie</h3>
  @if($gallery->count())
    <div class="flex justify-center gap-2">
      @for($i = 0; $i < min($maxThumbnails, $gallery->count()); $i++)
        <button type="button" 
               class="gallery-thumbnail"
               data-type="thumbnail"
               data-index="{{ $i }}"
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
