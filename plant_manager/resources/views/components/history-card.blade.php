@props(['plant', 'type' => 'watering', 'context' => 'modal'])

@php
  $config = match($type) {
    'watering' => [
      'bg' => 'blue-50',
      'border' => 'blue-500',
      'text' => 'blue-600',
      'dark' => 'blue-900',
      'icon' => 'droplet',
      'label' => 'Arrosage',
    ],
    'fertilizing' => [
      'bg' => 'green-50',
      'border' => 'green-500',
      'text' => 'green-600',
      'dark' => 'green-900',
      'icon' => 'leaf',
      'label' => 'Fertilisation',
    ],
    'repotting' => [
      'bg' => 'amber-50',
      'border' => 'amber-500',
      'text' => 'amber-600',
      'dark' => 'amber-900',
      'icon' => 'sprout',
      'label' => 'Rempotage',
    ],
  };
  
  // Get the last record
  if ($type === 'watering') {
    $last = $plant->wateringHistories()->latest('watering_date')->first();
    $dateField = 'watering_date';
    $route = route('plants.watering-history.index', $plant);
  } elseif ($type === 'fertilizing') {
    $last = $plant->fertilizingHistories()->latest('fertilizing_date')->first();
    $dateField = 'fertilizing_date';
    $route = route('plants.fertilizing-history.index', $plant);
  } else {
    $last = $plant->repottingHistories()->latest('repotting_date')->first();
    $dateField = 'repotting_date';
    $route = route('plants.repotting-history.index', $plant);
  }
@endphp

<div class="bg-{{ $config['bg'] }} p-3 rounded@if($context === 'modal') rounded-none@else-lg@endif border-l-4 border-{{ $config['border'] }}">
  <div class="flex items-center gap-2">
    <i data-lucide="{{ $config['icon'] }}" class="w-4 h-4 text-{{ $config['text'] }}"></i>
    <a href="{{ $route }}" class="text-sm font-semibold text-{{ $config['dark'] }} hover:opacity-75">
      {{ $config['label'] }}
    </a>
  </div>
  
  @if($last)
    <p class="text-xs text-{{ $config['text'] }} mt-2">Dernier : {{ $last->{$dateField}->format('d/m/Y') }}</p>
    
    @if($context === 'show')
      <div class="space-y-1">
        @if($type === 'watering')
          @if($last->amount)
            <p class="text-xs text-gray-600">Quantité : {{ $last->amount }} ml</p>
          @endif
          @if($last->notes)
            <p class="text-xs text-gray-600">Notes : {{ $last->notes }}</p>
          @endif
        @elseif($type === 'fertilizing')
          @if($last->fertilizerType)
            <p class="text-xs text-gray-600">Type : {{ $last->fertilizerType->name }}</p>
          @endif
          @if($last->amount)
            <p class="text-xs text-gray-600">
              Quantité : {{ $last->amount }}
              @if($last->fertilizerType)
                {{ $last->fertilizerType->unit === 'ml' ? 'ml' : ($last->fertilizerType->unit === 'g' ? 'g' : '') }}
              @else
                ml
              @endif
            </p>
          @endif
          @if($last->notes)
            <p class="text-xs text-gray-600">Notes : {{ $last->notes }}</p>
          @endif
        @elseif($type === 'repotting')
          @if($last->old_pot_size || $last->new_pot_size)
            <p class="text-xs text-gray-600">Pots : {{ $last->old_pot_size }} → {{ $last->new_pot_size }}</p>
          @endif
          @if($last->notes)
            <p class="text-xs text-gray-600">Notes : {{ $last->notes }}</p>
          @endif
        @endif
      </div>
    @endif
  @else
    <p class="text-xs text-{{ $config['text'] }} mt-2">Aucun enregistrement</p>
  @endif
  
  <button 
    type="button" 
    onclick="openQuick{{ ucfirst($type) }}Modal@if($context === 'modal')FromModal@endif()"
    class="text-xs text-{{ $config['text'] }} hover:text-{{ $config['dark'] }} mt-2 inline-block font-semibold flex items-center gap-1"
  >
    <i data-lucide="plus-circle" class="w-3 h-3"></i>
    Créer →
  </button>
</div>
    </div>
  @else
    <p class="text-xs text-gray-600">Aucun enregistrement</p>
  @endif
</div>
