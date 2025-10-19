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
               data-index="{{ $i + 1 }}"
               data-original-src="{{ Storage::url($gallery[$i]->filename) }}"
               style="aspect-ratio:1/1; width:70px; height:70px; padding:0; border:0; background:transparent; cursor:pointer; display:flex; align-items:center; justify-content:center; background-color:#f8f8f8;" 
               aria-label="Échanger avec la photo principale">
          <img src="{{ Storage::url($gallery[$i]->filename) }}" 
               alt="{{ $gallery[$i]->description ?? $plant->name }}" 
               style="max-width:100%; max-height:100%; object-fit:cover; border-radius:4px;">
        </button>
      @endfor
    </div>
  @else
    <x-empty-state message="Aucune photo" height="h-20" />
  @endif
</div>
