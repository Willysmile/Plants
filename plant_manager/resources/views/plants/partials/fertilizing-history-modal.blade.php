<!-- Fertilizing History Summary (for modal) -->
<div class="bg-green-50 p-3 rounded border-l-4 border-green-400">
    @php
        $lastFertilizing = $plant->fertilizingHistories()->latest('fertilizing_date')->first();
    @endphp
    
    <div class="flex items-center justify-between mb-2">
        <a href="{{ route('plants.fertilizing-history.index', $plant) }}" class="text-sm font-semibold text-green-900 hover:text-green-700 hover:underline flex-1">
            🌱 Dernière fertilisation : 
            @if($lastFertilizing)
                {{ $lastFertilizing->fertilizing_date->format('d/m/Y') }}
            @else
                —
            @endif
        </a>
        <label class="flex items-center cursor-pointer ml-2">
            <input type="checkbox" id="quickFertilizingCheckboxModal" class="w-4 h-4 text-green-600 rounded focus:ring-green-500" onclick="openQuickFertilizingModal()">
        </label>
    </div>
    
    @if($lastFertilizing)
        <div class="grid grid-cols-2 gap-2">
            @if($lastFertilizing->fertilizer_type)
                <p class="text-xs text-gray-600">Type : {{ $lastFertilizing->fertilizer_type }}</p>
            @endif
            @if($lastFertilizing->amount)
                <p class="text-xs text-gray-600">Quantité : {{ $lastFertilizing->amount }} ml</p>
            @endif
        </div>
    @else
        <p class="text-xs text-gray-600">Aucun enregistrement</p>
    @endif
</div>
