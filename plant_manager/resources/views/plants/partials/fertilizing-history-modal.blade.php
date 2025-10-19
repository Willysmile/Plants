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
        <a href="{{ route('plants.fertilizing-history.create', $plant) }}" class="text-xs font-semibold text-green-600 hover:text-green-800 hover:underline ml-2 px-2 py-1 rounded hover:bg-green-100 transition">
            + Fertiliser
        </a>
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
