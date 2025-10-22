@props(['plant', 'clickable' => true, 'aspectRatio' => '4/3', 'height' => null, 'maxHeight' => null, 'context' => 'show'])

<div class="rounded overflow-hidden flex-shrink-0" style="display:flex; align-items:center; justify-content:center; background-color:#f8f8f8; width:100%;">
  @if($plant->main_photo)
    @if($clickable)
      <button type="button" onclick="openLightboxGlobal(0)" class="bg-transparent border-0 p-0 flex items-center justify-center" style="width:100%; @if($height)height:{{ $height }};@elseif($maxHeight)max-height:{{ $maxHeight }};@else height:400px;@endif">
        <img id="main-photo-display" 
             src="{{ Storage::url($plant->main_photo) }}" 
             alt="{{ $plant->name }}" 
             data-original-src="{{ Storage::url($plant->main_photo) }}"
             data-type="main-photo"
             style="max-width:100%; height:100%; object-fit:contain; display:block; cursor:pointer;">
      </button>
    @else
      <div style="width:100%; @if($height)height:{{ $height }};@elseif($maxHeight)max-height:{{ $maxHeight }};@else height:400px;@endif display:flex; align-items:center; justify-content:center;">
        <img id="main-photo-display" 
             src="{{ Storage::url($plant->main_photo) }}" 
             alt="{{ $plant->name }}" 
             data-original-src="{{ Storage::url($plant->main_photo) }}"
             data-type="main-photo"
             style="max-width:100%; height:100%; object-fit:contain; display:block;">
      </div>
    @endif
  @elseif($plant->photos->count())
    @if($clickable)
      <button type="button" onclick="openLightboxGlobal(0)" class="bg-transparent border-0 p-0 flex items-center justify-center" style="width:100%; @if($height)height:{{ $height }};@elseif($maxHeight)max-height:{{ $maxHeight }};@else height:400px;@endif">
        <img id="main-photo-display" 
             src="{{ Storage::url($plant->photos->first()->filename) }}" 
             alt="{{ $plant->name }}" 
             data-original-src="{{ Storage::url($plant->photos->first()->filename) }}"
             data-type="main-photo"
             style="max-width:100%; height:100%; object-fit:contain; display:block; cursor:pointer;">
      </button>
    @else
      <div style="width:100%; @if($height)height:{{ $height }};@elseif($maxHeight)max-height:{{ $maxHeight }};@else height:400px;@endif display:flex; align-items:center; justify-content:center;">
        <img id="main-photo-display" 
             src="{{ Storage::url($plant->photos->first()->filename) }}" 
             alt="{{ $plant->name }}" 
             data-original-src="{{ Storage::url($plant->photos->first()->filename) }}"
             data-type="main-photo"
             style="max-width:100%; height:100%; object-fit:contain; display:block;">
      </div>
    @endif
  @else
    <div style="width:100%; @if($height)height:{{ $height }};@elseif($maxHeight)max-height:{{ $maxHeight }};@else height:400px;@endif display:flex; align-items:center; justify-content:center;">
      <x-empty-state message="Pas d'image" height="h-full" />
    </div>
  @endif
</div>
