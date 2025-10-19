<!-- Watering History Summary (for modal) -->
<div class="bg-blue-50 p-3 rounded border-l-4 border-blue-400">
    @php
        $lastWatering = $plant->wateringHistories()->latest('watering_date')->first();
    @endphp
    
    <div class="flex items-center justify-between mb-2">
        <div class="flex items-center gap-2">
            <i data-lucide="droplet" class="w-4 h-4 text-blue-600"></i>
            <span class="text-sm font-semibold text-blue-900">Arrosage</span>
        </div>
    </div>
    
    @if($lastWatering)
        <p class="text-xs text-blue-600 mb-2">Dernier : {{ $lastWatering->watering_date->format('d/m/Y') }}</p>
        <div class="space-y-1">
            @if($lastWatering->amount)
                <p class="text-xs text-gray-600">Quantité : {{ $lastWatering->amount }}</p>
            @endif
        </div>
    @else
        <p class="text-xs text-blue-600 mb-2">Aucun enregistrement</p>
    @endif
    <button type="button" onclick="openQuickWateringModalFromModal()" class="text-xs text-blue-500 hover:text-blue-700 mt-2 inline-block font-semibold">+ Créer →</button>
</div>
