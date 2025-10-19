@props(['plant', 'type' => 'watering'])

@php
  $config = match($type) {
    'watering' => [
      'icon' => '💧',
      'title' => 'Dernier arrosage',
      'color' => 'blue',
      'bgColor' => 'bg-blue-50',
      'borderColor' => 'border-blue-400',
      'textColor' => 'text-blue-900',
      'route' => 'plants.watering-history.index',
      'method' => 'wateringHistories',
      'checkboxId' => 'quickWateringCheckboxModal',
      'checkboxOnclick' => 'openQuickWateringModal()',
      'checkboxColor' => 'text-blue-600',
      'focusRing' => 'focus:ring-blue-500',
      'fields' => ['amount' => 'Quantité', 'notes' => 'Notes'],
    ],
    'fertilizing' => [
      'icon' => '🌱',
      'title' => 'Dernière fertilisation',
      'color' => 'green',
      'bgColor' => 'bg-green-50',
      'borderColor' => 'border-green-400',
      'textColor' => 'text-green-900',
      'route' => 'plants.fertilizing-history.index',
      'method' => 'fertilizingHistories',
      'checkboxId' => 'quickFertilizingCheckboxModal',
      'checkboxOnclick' => 'openQuickFertilizingModal()',
      'checkboxColor' => 'text-green-600',
      'focusRing' => 'focus:ring-green-500',
      'fields' => ['fertilizer_type' => 'Type', 'amount' => 'Quantité'],
    ],
    'repotting' => [
      'icon' => '🪴',
      'title' => 'Dernier rempotage',
      'color' => 'amber',
      'bgColor' => 'bg-amber-50',
      'borderColor' => 'border-amber-400',
      'textColor' => 'text-amber-900',
      'route' => 'plants.repotting-history.index',
      'method' => 'reppotingHistories',
      'checkboxId' => 'quickRepottingCheckboxModal',
      'checkboxOnclick' => 'openQuickRepottingModal()',
      'checkboxColor' => 'text-amber-600',
      'focusRing' => 'focus:ring-amber-500',
      'fields' => ['new_pot_size' => 'Nouveau pot', 'soil_type' => 'Terreau'],
    ],
  };
  
  $lastRecord = $plant->{$config['method']}()->latest(
    match($type) {
      'watering' => 'watering_date',
      'fertilizing' => 'fertilizing_date',
      'repotting' => 'repotting_date',
    }
  )->first();
@endphp

<div class="{{ $config['bgColor'] }} p-3 rounded {{ $config['borderColor'] }} border-l-4">
  <x-header-flex
    :show-checkbox="true"
    :checkbox-id="$config['checkboxId']"
    :checkbox-class="$config['checkboxColor'] . ' ' . $config['focusRing']"
    :checkbox-onclick="$config['checkboxOnclick']"
  >
    <a href="{{ route($config['route'], $plant) }}" class="text-sm font-semibold {{ $config['textColor'] }} hover:{{ $config['textColor'] }}/70 hover:underline flex-1">
      {{ $config['icon'] }} {{ $config['title'] }}: 
      @if($lastRecord)
        {{ $lastRecord->{match($type) {
          'watering' => 'watering_date',
          'fertilizing' => 'fertilizing_date',
          'repotting' => 'repotting_date',
        }}->format('d/m/Y') }}
      @else
        —
      @endif
    </a>
  </x-header-flex>
  
  @if($lastRecord)
    <div class="grid grid-cols-2 gap-2">
      @if($type === 'watering' && $lastRecord->amount)
        <p class="text-xs text-gray-600">Quantité : {{ $lastRecord->amount }} ml</p>
      @elseif($type === 'watering' && $lastRecord->notes)
        <p class="text-xs text-gray-600 italic">{{ Str::limit($lastRecord->notes, 40) }}</p>
      @endif
      
      @if($type === 'fertilizing' && $lastRecord->fertilizer_type)
        <p class="text-xs text-gray-600">Type : {{ $lastRecord->fertilizer_type }}</p>
      @elseif($type === 'fertilizing' && $lastRecord->amount)
        <p class="text-xs text-gray-600">Quantité : {{ $lastRecord->amount }} ml</p>
      @endif
      
      @if($type === 'repotting')
        <p class="text-xs text-gray-600">
          @if($lastRecord->old_pot_size)
            {{ $lastRecord->old_pot_size }} → 
          @endif
          {{ $lastRecord->new_pot_size }}
        </p>
        @if($lastRecord->soil_type)
          <p class="text-xs text-gray-600">Terreau : {{ $lastRecord->soil_type }}</p>
        @endif
      @endif
    </div>
  @else
    <p class="text-xs text-gray-600">Aucun enregistrement</p>
  @endif
</div>
