@props(['plant', 'history' => null, 'type' => 'watering'])

@php
  $isEdit = $history !== null;
  
  $config = match($type) {
    'watering' => [
      'title' => $isEdit ? 'Modifier arrosage' : 'Nouvel arrosage',
      'route_store' => 'plants.watering-history.store',
      'route_update' => 'plants.watering-history.update',
      'route_index' => 'plants.watering-history.index',
      'date_field' => 'watering_date',
      'fields' => [
        'watering_date' => ['label' => 'Date et heure d\'arrosage', 'type' => 'datetime-local', 'required' => true],
        'amount' => ['label' => 'Quantité (ex: 500ml, 1L)', 'type' => 'text'],
        'notes' => ['label' => 'Notes', 'type' => 'textarea'],
      ],
    ],
    'fertilizing' => [
      'title' => $isEdit ? 'Modifier fertilisation' : 'Nouvelle fertilisation',
      'route_store' => 'plants.fertilizing-history.store',
      'route_update' => 'plants.fertilizing-history.update',
      'route_index' => 'plants.fertilizing-history.index',
      'date_field' => 'fertilizing_date',
      'fields' => [
        'fertilizing_date' => ['label' => 'Date et heure de fertilisation', 'type' => 'datetime-local', 'required' => true],
        'fertilizer_type' => ['label' => 'Type d\'engrais', 'type' => 'text'],
        'amount' => ['label' => 'Quantité (ml)', 'type' => 'number'],
        'notes' => ['label' => 'Notes', 'type' => 'textarea'],
      ],
    ],
    'repotting' => [
      'title' => $isEdit ? 'Modifier rempotage' : 'Nouveau rempotage',
      'route_store' => 'plants.repotting-history.store',
      'route_update' => 'plants.repotting-history.update',
      'route_index' => 'plants.repotting-history.index',
      'date_field' => 'repotting_date',
      'fields' => [
        'repotting_date' => ['label' => 'Date et heure du rempotage', 'type' => 'datetime-local', 'required' => true],
        'old_pot_size' => ['label' => 'Ancien pot (taille)', 'type' => 'text'],
        'new_pot_size' => ['label' => 'Nouveau pot (taille)', 'type' => 'text'],
        'soil_type' => ['label' => 'Type de terreau', 'type' => 'text'],
        'notes' => ['label' => 'Notes', 'type' => 'textarea'],
      ],
    ],
  };
  
  $action = $isEdit 
    ? route($config['route_update'], [$plant, $history]) 
    : route($config['route_store'], $plant);
  
  $method = $isEdit ? 'PATCH' : 'POST';
@endphp

<form action="{{ $action }}" method="POST" class="space-y-4">
  @csrf
  @if($isEdit) @method($method) @endif

  <div class="mb-4">
    <label class="block text-gray-700 font-bold mb-2">Plante : {{ $plant->name }}</label>
  </div>

  @foreach($config['fields'] as $fieldName => $fieldConfig)
    <div class="mb-4">
      <label class="block text-gray-700 font-bold mb-2" for="{{ $fieldName }}">
        {{ $fieldConfig['label'] }}
        @if(($fieldConfig['required'] ?? false)) <span class="text-red-500">*</span> @endif
      </label>

      @if($fieldConfig['type'] === 'textarea')
        <textarea class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:border-blue-500 @error($fieldName) border-red-500 @enderror" 
          id="{{ $fieldName }}" 
          name="{{ $fieldName }}" 
          rows="4">{{ old($fieldName, $history?->{$fieldName} ?? '') }}</textarea>
      @elseif($fieldConfig['type'] === 'number')
        <input class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:border-blue-500 @error($fieldName) border-red-500 @enderror" 
          type="number" 
          step="0.1"
          id="{{ $fieldName }}" 
          name="{{ $fieldName }}" 
          value="{{ old($fieldName, $history?->{$fieldName} ?? '') }}"
          @if(($fieldConfig['required'] ?? false)) required @endif>
      @elseif($fieldConfig['type'] === 'datetime-local')
        <input class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:border-blue-500 @error($fieldName) border-red-500 @enderror" 
          type="datetime-local" 
          id="{{ $fieldName }}" 
          name="{{ $fieldName }}" 
          value="{{ old($fieldName, $history?->{$fieldName}?->format('Y-m-d\TH:i') ?? '') }}"
          data-default-local
          @if(($fieldConfig['required'] ?? false)) required @endif>
      @else
        <input class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:border-blue-500 @error($fieldName) border-red-500 @enderror" 
          type="{{ $fieldConfig['type'] }}" 
          id="{{ $fieldName }}" 
          name="{{ $fieldName }}" 
          value="{{ old($fieldName, $history?->{$fieldName} ?? '') }}"
          @if(($fieldConfig['required'] ?? false)) required @endif>
      @endif

      @error($fieldName)
        <span class="text-red-500 text-sm">{{ $message }}</span>
      @enderror
    </div>
  @endforeach

  <div class="flex gap-2 mt-6">
    <button type="submit" class="px-4 py-2 bg-blue-500 hover:bg-blue-700 text-white font-bold rounded">
      {{ $isEdit ? 'Modifier' : 'Créer' }}
    </button>
    <a href="{{ route($config['route_index'], $plant) }}" class="px-4 py-2 bg-gray-200 hover:bg-gray-300 rounded">Annuler</a>
  </div>
</form>
