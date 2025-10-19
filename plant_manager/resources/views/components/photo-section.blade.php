@props(['plant', 'clickable' => true, 'aspectRatio' => '4/3'])

<div class="rounded overflow-hidden flex-shrink-0" style="aspect-ratio:{{ $aspectRatio }}; display:flex; align-items:center; justify-content:center; background-color:#f8f8f8;">
  @if($plant->main_photo)
    @if($clickable)
      <button type="button" onclick="openLightboxGlobal(0)" class="bg-transparent border-0 p-0 w-full flex items-center justify-center">
        <img id="main-photo-display" 
             src="{{ Storage::url($plant->main_photo) }}" 
             alt="{{ $plant->name }}" 
             data-original-src="{{ Storage::url($plant->main_photo) }}"
             data-type="main-photo"
             style="max-width:100%; max-height:100%; object-fit:contain; display:block; cursor:pointer;">
      </button>
    @else
      <img id="main-photo-display" 
           src="{{ Storage::url($plant->main_photo) }}" 
           alt="{{ $plant->name }}" 
           data-original-src="{{ Storage::url($plant->main_photo) }}"
           data-type="main-photo"
           style="max-width:100%; max-height:100%; object-fit:contain; display:block;">
    @endif
  @elseif($plant->photos->count())
    @if($clickable)
      <button type="button" onclick="openLightboxGlobal(0)" class="bg-transparent border-0 p-0 w-full flex items-center justify-center">
        <img id="main-photo-display" 
             src="{{ Storage::url($plant->photos->first()->filename) }}" 
             alt="{{ $plant->name }}" 
             data-original-src="{{ Storage::url($plant->photos->first()->filename) }}"
             data-type="main-photo"
             style="max-width:100%; max-height:100%; object-fit:contain; display:block; cursor:pointer;">
      </button>
    @else
      <img id="main-photo-display" 
           src="{{ Storage::url($plant->photos->first()->filename) }}" 
           alt="{{ $plant->name }}" 
           data-original-src="{{ Storage::url($plant->photos->first()->filename) }}"
           data-type="main-photo"
           style="max-width:100%; max-height:100%; object-fit:contain; display:block;">
    @endif
  @else
    <x-empty-state message="Pas d'image" height="h-full" />
  @endif
</div>
