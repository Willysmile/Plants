@props(['plant', 'maxThumbnails' => 2])

@php
  $gallery = $plant->photos->filter(function($p) use ($plant){
    if ($plant->main_photo && $p->filename === $plant->main_photo) return false;
    if (isset($p->is_main) && $p->is_main) return false;
    return true;
  })->values();
@endphp

<h3 class="font-medium text-sm mb-2 text-center uppercase text-gray-700 font-semibold">Galerie</h3>
<div class="border-t pt-4 mt-2 p-4">
  @if($gallery->count())
    <div class="flex justify-center gap-3 flex-wrap">
      @for($i = 0; $i < min($maxThumbnails, $gallery->count()); $i++)
        <button type="button" 
               class="gallery-thumbnail"
               data-type="thumbnail"
               data-index="{{ $i + 1 }}"
               data-original-src="{{ Storage::url($gallery[$i]->filename) }}"
               style="aspect-ratio:1/1; width:120px; height:120px; padding:0; border:0; background:transparent; cursor:pointer; display:flex; align-items:center; justify-content:center; background-color:#fff;" 
               aria-label="Échanger avec la photo principale">
          <img src="{{ Storage::url($gallery[$i]->filename) }}" 
               alt="{{ $gallery[$i]->description ?? $plant->name }}" 
               style="max-width:100%; max-height:100%; object-fit:cover;">
        </button>
      @endfor
    </div>
  @else
    <x-empty-state message="Aucune photo" height="h-20" />
  @endif
</div>
