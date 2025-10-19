@props(['plant'])

<article class="bg-white rounded-lg shadow overflow-hidden">
  <div class="w-full h-48 bg-gray-100 overflow-hidden">
    <button
      type="button"
      class="w-full h-full block focus:outline-none"
      data-modal-url="{{ route('plants.modal', $plant) }}"
      aria-label="Ouvrir {{ $plant->name }}"
    >
      @if($plant->main_photo)
        <img src="{{ Storage::url($plant->main_photo) }}" alt="{{ $plant->name }}" class="w-full h-full object-cover">
      @else
        <x-empty-state message="Pas d'image" height="h-48" />
      @endif
    </button>
  </div>

  <div class="p-3">
    <h3 class="text-sm font-medium text-gray-800 truncate" title="{{ $plant->name }}">{{ $plant->name }}</h3>
    <p class="text-xs text-gray-500 mt-1 truncate">{{ $plant->category->name ?? '—' }}</p>
    <div class="mt-3 flex items-center justify-between text-xs text-gray-500">
      <span>Arrosage: {{ \App\Models\Plant::$wateringLabels[$plant->watering_frequency] ?? $plant->watering_frequency }}</span>
      <a href="{{ route('plants.show', $plant) }}" class="text-blue-600 hover:underline">Détails</a>
    </div>
  </div>
</article>
