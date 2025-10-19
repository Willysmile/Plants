@props(['plant', 'history' => null, 'type' => 'watering', 'fertilizerTypes' => null])

@php
  $isEdit = $history !== null;
  
  // Get fertilizer types if not provided
  if ($type === 'fertilizing' && $fertilizerTypes === null) {
    $fertilizerTypes = \App\Models\FertilizerType::all();
  }
  
  $config = match($type) {
    'watering' => [
      'title' => $isEdit ? 'Modifier arrosage' : 'Nouvel arrosage',
      'route_store' => 'plants.watering-history.store',
      'route_update' => 'plants.watering-history.update',
      'route_index' => 'plants.watering-history.index',
      'date_field' => 'watering_date',
      'fields' => [
        'watering_date' => ['label' => 'Date d\'arrosage', 'type' => 'date', 'required' => true],
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
        'fertilizing_date' => ['label' => 'Date de fertilisation', 'type' => 'date', 'required' => true],
        'fertilizer_type_id' => ['label' => 'Type d\'engrais', 'type' => 'select', 'grid' => 'col'],
        'amount' => ['label' => 'Quantité', 'type' => 'number', 'suffix' => 'ml', 'grid' => 'col'],
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
        'repotting_date' => ['label' => 'Date du rempotage', 'type' => 'date', 'required' => true],
        'old_pot_size' => ['label' => 'Ancien pot', 'type' => 'text', 'suffix' => 'cm', 'grid' => 'col'],
        'new_pot_size' => ['label' => 'Nouveau pot', 'type' => 'text', 'suffix' => 'cm', 'grid' => 'col'],
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

  @php
    $currentGrid = null;
    $gridCount = 0;
  @endphp

  @foreach($config['fields'] as $fieldName => $fieldConfig)
    @php
      $isGridCol = ($fieldConfig['grid'] ?? null) === 'col';
      $suffix = $fieldConfig['suffix'] ?? null;
      
      // Gérer l'ouverture/fermeture du grid
      if ($isGridCol && $currentGrid === null) {
        $currentGrid = true;
        $gridCount = 0;
        echo '<div class="grid grid-cols-2 gap-4">';
      } elseif (!$isGridCol && $currentGrid === true) {
        $currentGrid = false;
        echo '</div>';
      }
      
      if ($isGridCol) {
        $gridCount++;
      }
    @endphp

    <div>
      <label class="block text-gray-700 font-bold mb-2" for="{{ $fieldName }}">
        {{ $fieldConfig['label'] }}
        @if(($fieldConfig['required'] ?? false)) <span class="text-red-500">*</span> @endif
      </label>

      @if($fieldConfig['type'] === 'textarea')
        <textarea class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:border-blue-500 @error($fieldName) border-red-500 @enderror" 
          id="{{ $fieldName }}" 
          name="{{ $fieldName }}" 
          rows="4">{{ old($fieldName, $history?->{$fieldName} ?? '') }}</textarea>
      @elseif($fieldConfig['type'] === 'select')
        <div class="space-y-2">
          <select class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:border-blue-500 @error($fieldName) border-red-500 @enderror" 
            id="{{ $fieldName }}" 
            name="{{ $fieldName }}">
            <option value="">-- Sélectionner --</option>
            @foreach($fertilizerTypes as $ftype)
              <option value="{{ $ftype->id }}" @selected(old($fieldName, $history?->fertilizer_type_id ?? '') == $ftype->id)>{{ $ftype->name }}</option>
            @endforeach
          </select>
          
          <!-- Add new fertilizer type section -->
          <div class="flex gap-2">
            <input type="text" 
              id="newFertilizerTypeName" 
              placeholder="Nouveau type..." 
              class="flex-1 px-3 py-2 border border-gray-300 rounded focus:outline-none focus:border-green-500 text-sm">
            <button type="button" 
              onclick="addNewFertilizerType(event)" 
              class="px-3 py-2 bg-green-600 text-white rounded hover:bg-green-700 text-sm font-medium">
              +
            </button>
          </div>
          <p class="text-xs text-gray-500">Ou créez un nouveau type ci-dessus</p>
        </div>
      @elseif($fieldConfig['type'] === 'number')
        <div class="flex items-center gap-2">
          <input class="flex-1 px-3 py-2 border border-gray-300 rounded focus:outline-none focus:border-blue-500 @error($fieldName) border-red-500 @enderror" 
            type="number" 
            step="0.1"
            id="{{ $fieldName }}" 
            name="{{ $fieldName }}" 
            value="{{ old($fieldName, $history?->{$fieldName} ?? '') }}"
            @if(($fieldConfig['required'] ?? false)) required @endif>
          @if($suffix)
            <span class="text-gray-600 font-medium min-w-fit">{{ $suffix }}</span>
          @endif
        </div>
      @elseif($fieldConfig['type'] === 'date')
        <input class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:border-blue-500 @error($fieldName) border-red-500 @enderror" 
          type="date" 
          id="{{ $fieldName }}" 
          name="{{ $fieldName }}" 
          value="{{ old($fieldName, $history?->{$fieldName}?->format('Y-m-d') ?? '') }}"
          @if(($fieldConfig['required'] ?? false)) required @endif>
      @else
        <div class="flex items-center gap-2">
          <input class="flex-1 px-3 py-2 border border-gray-300 rounded focus:outline-none focus:border-blue-500 @error($fieldName) border-red-500 @enderror" 
            type="{{ $fieldConfig['type'] }}" 
            id="{{ $fieldName }}" 
            name="{{ $fieldName }}" 
            value="{{ old($fieldName, $history?->{$fieldName} ?? '') }}"
            @if(($fieldConfig['required'] ?? false)) required @endif>
          @if($suffix)
            <span class="text-gray-600 font-medium min-w-fit">{{ $suffix }}</span>
          @endif
        </div>
      @endif

      @error($fieldName)
        <span class="text-red-500 text-sm">{{ $message }}</span>
      @enderror
    </div>
  @endforeach

  @php
    if ($currentGrid === true) {
      echo '</div>';
    }
  @endphp

  <div class="flex gap-2 mt-6">
    <button type="submit" class="px-4 py-2 bg-blue-500 hover:bg-blue-700 text-white font-bold rounded">
      {{ $isEdit ? 'Modifier' : 'Créer' }}
    </button>
    <a href="{{ route($config['route_index'], $plant) }}" class="px-4 py-2 bg-gray-200 hover:bg-gray-300 rounded">Annuler</a>
  </div>
</form>

<script>
  async function addNewFertilizerType(event) {
    event.preventDefault();
    const input = document.getElementById('newFertilizerTypeName');
    const name = input.value.trim();
    
    if (!name) {
      alert('Veuillez entrer un nom pour le type d\'engrais');
      return;
    }
    
    try {
      const response = await fetch('/fertilizer-types', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
          'X-Requested-With': 'XMLHttpRequest',
        },
        body: JSON.stringify({ name })
      });
      
      if (!response.ok) {
        throw new Error('Erreur lors de la création');
      }
      
      const data = await response.json();
      
      // Add option to select
      const select = document.getElementById('fertilizer_type_id');
      const option = new Option(data.name, data.id);
      select.appendChild(option);
      select.value = data.id;
      
      // Clear input
      input.value = '';
      
      alert('Type d\'engrais créé avec succès !');
    } catch (error) {
      console.error('Error:', error);
      alert('Erreur lors de la création du type d\'engrais');
    }
  }
</script>
