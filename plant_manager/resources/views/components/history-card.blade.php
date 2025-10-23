@props(['plant', 'type' => 'watering', 'context' => 'modal'])

@php
  $settings = \App\Models\Setting::getInstance();
  
  $config = match($type) {
    'watering' => [
      'bg' => 'blue-50',
      'border' => 'blue-500',
      'text' => 'blue-600',
      'dark' => 'blue-900',
      'icon' => 'droplet',
      'label' => 'Arrosage',
      'functionName' => 'Watering',
    ],
    'fertilizing' => [
      'bg' => 'green-50',
      'border' => 'green-500',
      'text' => 'green-600',
      'dark' => 'green-900',
      'icon' => 'leaf',
      'label' => 'Fertilisation',
      'functionName' => 'Fertilizing',
    ],
    'repotting' => [
      'bg' => 'amber-50',
      'border' => 'amber-500',
      'text' => 'amber-600',
      'dark' => 'amber-900',
      'icon' => 'sprout',
      'label' => 'Rempotage',
      'functionName' => 'Repotting',
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
  
  // Build function name based on context
  $functionName = 'openQuick' . $config['functionName'] . 'Modal' . ($context === 'modal' ? 'FromModal' : '') . '()';
  
  // Map color names to Tailwind classes for static compilation
  $bgClass = match($config['bg']) {
    'blue-50' => 'bg-blue-50',
    'green-50' => 'bg-green-50',
    'amber-50' => 'bg-amber-50',
    default => 'bg-blue-50'
  };
  
  $borderClass = match($config['border']) {
    'blue-500' => 'border-blue-500',
    'green-500' => 'border-green-500',
    'amber-500' => 'border-amber-500',
    default => 'border-blue-500'
  };
  
  $textClass = match($config['text']) {
    'blue-600' => 'text-blue-600',
    'green-600' => 'text-green-600',
    'amber-600' => 'text-amber-600',
    default => 'text-blue-600'
  };
  
  $darkClass = match($config['dark']) {
    'blue-900' => 'text-blue-900',
    'green-900' => 'text-green-900',
    'amber-900' => 'text-amber-900',
    default => 'text-blue-900'
  };
@endphp

<div class="p-3 rounded-lg border-l-4 {{ $bgClass }} {{ $borderClass }}">
  <div class="flex items-center gap-2">
    <i data-lucide="{{ $config['icon'] }}" class="w-4 h-4 {{ $textClass }}"></i>
    <a href="{{ $route }}" class="text-sm font-semibold {{ $darkClass }} hover:opacity-75">
      {{ $config['label'] }}
    </a>
  </div>
  
  @if($last)
    <p class="text-xs mt-2 {{ $textClass }}">Dernier : {{ $last->{$dateField}->format('d/m/Y') }}</p>
    
    <div class="space-y-1">
      @if($type === 'watering')
        @if($last->amount)
          <p class="text-xs text-gray-600">Quantité : {{ $last->amount }} ml</p>
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
      @elseif($type === 'repotting')
        @if($last->old_pot_size || $last->new_pot_size)
          <p class="text-xs text-gray-600">
            Pots : {{ $last->old_pot_size }}{{ $last->old_pot_unit ?? $settings->pot_unit }} → {{ $last->new_pot_size }}{{ $last->new_pot_unit ?? $settings->pot_unit }}
          </p>
        @endif
      @endif
    </div>
  @else
    <p @class(['text-xs mt-2', "text-{$config['text']}"])>Aucun enregistrement</p>
  @endif
  
  <button 
    type="button" 
    onclick="{{ $functionName }}"
    @class(['text-xs mt-2 inline-block font-semibold flex items-center gap-1 hover:opacity-75', "text-{$config['text']}"])
  >
    Créer →
  </button>
</div>
