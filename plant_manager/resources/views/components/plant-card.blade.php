@props(['plant'])

<article class="bg-white rounded-lg shadow overflow-hidden">
  <div class="w-full h-32 bg-gray-100 overflow-hidden flex items-center justify-center">
    <button
      type="button"
      class="w-full h-full block focus:outline-none flex items-center justify-center"
      data-modal-url="{{ route('plants.modal', $plant) }}"
      aria-label="Ouvrir {{ $plant->name }}"
    >
      @if($plant->main_photo)
        <img src="{{ Storage::url($plant->main_photo) }}" alt="{{ $plant->name }}" class="max-w-full max-h-full object-contain">
      @else
        <x-empty-state message="Pas d'image" height="h-32" />
      @endif
    </button>
  </div>

  <div class="p-1">
    <h3 class="text-xs font-medium text-gray-800 truncate" title="{{ $plant->name }}">{{ $plant->name }}</h3>
    @if($plant->scientific_name)
      <p class="text-xs italic text-gray-600 truncate" title="{{ $plant->scientific_name }}">{{ $plant->scientific_name }}</p>
    @endif
    <div class="mt-1 flex items-center justify-between text-xs text-gray-500">
      <span></span>
      <a href="{{ route('plants.show', $plant) }}" class="text-blue-600 hover:underline font-medium">→ Détails</a>
    </div>
  </div>
</article>
