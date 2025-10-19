@props(['plant', 'histories', 'type' => 'watering'])

@php
  $config = match($type) {
    'watering' => [
      'title' => 'Historique d\'arrosage',
      'route_create' => 'plants.watering-history.create',
      'route_edit' => 'plants.watering-history.edit',
      'route_destroy' => 'plants.watering-history.destroy',
      'date_field' => 'watering_date',
      'fields' => ['amount', 'notes'],
      'labels' => ['amount' => 'Quantité', 'notes' => 'Notes'],
    ],
    'fertilizing' => [
      'title' => 'Historique de fertilisation',
      'route_create' => 'plants.fertilizing-history.create',
      'route_edit' => 'plants.fertilizing-history.edit',
      'route_destroy' => 'plants.fertilizing-history.destroy',
      'date_field' => 'fertilizing_date',
      'fields' => ['fertilizer_type', 'amount', 'notes'],
      'labels' => ['fertilizer_type' => 'Type', 'amount' => 'Quantité', 'notes' => 'Notes'],
    ],
    'repotting' => [
      'title' => 'Historique de rempotage',
      'route_create' => 'plants.repotting-history.create',
      'route_edit' => 'plants.repotting-history.edit',
      'route_destroy' => 'plants.repotting-history.destroy',
      'date_field' => 'repotting_date',
      'fields' => ['old_pot_size', 'new_pot_size', 'soil_type', 'notes'],
      'labels' => ['old_pot_size' => 'Ancien pot', 'new_pot_size' => 'Nouveau pot', 'soil_type' => 'Terreau', 'notes' => 'Notes'],
    ],
  };
@endphp

<div class="grid gap-4">
  @if($histories->count())
    @foreach($histories as $history)
      <div class="bg-white rounded-lg shadow-md p-4">
        <div class="flex justify-between items-start">
          <div class="flex-1">
            <p class="text-sm text-gray-500">{{ $history->{$config['date_field']}->format('d/m/Y') }}</p>
            
            @foreach($config['fields'] as $field)
              @if($history->{$field} ?? null)
                <p class="text-gray-700 text-sm">
                  <span class="font-semibold">{{ $config['labels'][$field] ?? $field }} :</span> 
                  {{ $history->{$field} }}
                </p>
              @endif
            @endforeach
          </div>

          <div class="space-x-2 flex-shrink-0">
            <a href="{{ route($config['route_edit'], [$plant, $history]) }}" class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-1 px-3 rounded text-sm inline-block">
              Éditer
            </a>
            <form action="{{ route($config['route_destroy'], [$plant, $history]) }}" method="POST" class="inline">
              @csrf
              @method('DELETE')
              <button type="submit" class="bg-red-500 hover:bg-red-700 text-white font-bold py-1 px-3 rounded text-sm" onclick="return confirm('Êtes-vous sûr ?')">
                Supprimer
              </button>
            </form>
          </div>
        </div>
      </div>
    @endforeach
  @else
    <div class="bg-gray-100 border border-gray-300 text-gray-700 px-4 py-3 rounded">
      Aucun enregistrement pour le moment.
    </div>
  @endif
</div>
