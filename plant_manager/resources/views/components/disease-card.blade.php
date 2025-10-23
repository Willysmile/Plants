@props(['plant', 'context' => 'modal'])

@php
  $lastDisease = $plant->diseaseHistories()->latest('detected_at')->first();
  $allDiseases = $plant->diseaseHistories()->get();
  
  $statusColors = [
    'detected' => ['bg' => 'red-50', 'border' => 'red-500', 'text' => 'red-600', 'dark' => 'red-900', 'badge' => 'red-100 text-red-800', 'icon' => 'alert-circle'],
    'treated' => ['bg' => 'yellow-50', 'border' => 'yellow-500', 'text' => 'yellow-600', 'dark' => 'yellow-900', 'badge' => 'yellow-100 text-yellow-800', 'icon' => 'activity'],
    'cured' => ['bg' => 'green-50', 'border' => 'green-500', 'text' => 'green-600', 'dark' => 'green-900', 'badge' => 'green-100 text-green-800', 'icon' => 'check-circle'],
    'recurring' => ['bg' => 'orange-50', 'border' => 'orange-500', 'text' => 'orange-600', 'dark' => 'orange-900', 'badge' => 'orange-100 text-orange-800', 'icon' => 'repeat'],
  ];
@endphp

<div class="bg-red-50 p-3 rounded-lg border-l-4 border-red-500">
  <div class="flex items-center gap-2">
    <i data-lucide="bug" class="w-4 h-4 text-red-600"></i>
    <span class="text-sm font-semibold text-red-900">Maladies</span>
  </div>
  
  @if($lastDisease)
    <p class="text-xs text-red-600 mt-2">Dernière : {{ $lastDisease->detected_at->format('d/m/Y') }}</p>
    <p class="text-xs text-gray-700 font-medium">{{ $lastDisease->disease->name }}</p>
    <span class="inline-block text-xs px-2 py-0.5 rounded mt-1 {{ $statusColors[$lastDisease->status]['badge'] }}">
      {{ $lastDisease->status_label }}
    </span>
  @else
    <p class="text-xs text-red-600 mt-2">Aucune maladie détectée</p>
  @endif
  
  @if($allDiseases->count() > 0)
    <button 
      type="button" 
      onclick="@if($context === 'modal') openDiseasesModalFromModal({{ $plant->id }}) @else openDiseasesModal({{ $plant->id }}) @endif"
      class="text-xs text-red-600 hover:text-red-900 mt-2 inline-block font-semibold flex items-center gap-1">
      <i data-lucide="eye" class="w-3 h-3"></i>
      Voir ({{ $allDiseases->count() }})
    </button>
  @endif

  <button 
    type="button" 
    onclick="openAddDiseaseModal({{ $plant->id }})"
    class="text-xs text-red-600 hover:text-red-900 mt-2 inline-block font-semibold flex items-center gap-1 ml-2">
    <i data-lucide="plus" class="w-3 h-3"></i>
    Ajouter
  </button>
</div>