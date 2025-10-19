<!-- Watering History Summary (for modal) -->
<div class="bg-blue-50 p-3 rounded border-l-4 border-blue-400">
    @php
        $lastWatering = $plant->wateringHistories()->latest('watering_date')->first();
    @endphp
    
    <div class="flex items-center justify-between mb-2">
        <a href="{{ route('plants.watering-history.index', $plant) }}" class="text-sm font-semibold text-blue-900 hover:text-blue-700 hover:underline flex-1">
            💧 Dernier arrosage : 
            @if($lastWatering)
                {{ $lastWatering->watering_date->format('d/m/Y') }}
            @else
                —
            @endif
        </a>
        <a href="{{ route('plants.watering-history.create', $plant) }}" class="text-xs font-semibold text-blue-600 hover:text-blue-800 hover:underline ml-2 px-2 py-1 rounded hover:bg-blue-100 transition">
            + Arroser
        </a>
    </div>
    
    @if($lastWatering)
        <div class="grid grid-cols-2 gap-2">
            @if($lastWatering->amount)
                <p class="text-xs text-gray-600">Quantité : {{ $lastWatering->amount }} ml</p>
            @endif
            @if($lastWatering->notes)
                <p class="text-xs text-gray-600 italic">{{ Str::limit($lastWatering->notes, 40) }}</p>
            @endif
        </div>
    @else
        <p class="text-xs text-gray-600">Aucun enregistrement</p>
    @endif
</div>
