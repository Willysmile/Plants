<!-- Fertilizing History Summary (for modal) -->
<div class="bg-green-50 p-3 rounded border-l-4 border-green-400">
    @php
        $lastFertilizing = $plant->fertilizingHistories()->latest('fertilizing_date')->first();
    @endphp
    
    <div class="flex items-center justify-between mb-2">
        <div class="flex items-center gap-2">
            <i data-lucide="leaf" class="w-4 h-4 text-green-600"></i>
            <span class="text-sm font-semibold text-green-900">Fertilisation</span>
        </div>
        <button type="button" onclick="openQuickFertilizingModalFromModal()" class="px-2 py-1 bg-green-500 text-white rounded text-sm hover:bg-green-600">+</button>
    </div>
    
    @if($lastFertilizing)
        <p class="text-xs text-green-600 mb-2">Dernier : {{ $lastFertilizing->fertilizing_date->format('d/m/Y') }}</p>
        <div class="grid grid-cols-2 gap-2">
            @if($lastFertilizing->fertilizer_type)
                <p class="text-xs text-gray-600">Type : {{ $lastFertilizing->fertilizer_type }}</p>
            @endif
            @if($lastFertilizing->amount)
                <p class="text-xs text-gray-600">Quantité : {{ $lastFertilizing->amount }} ml</p>
            @endif
        </div>
    @else
        <p class="text-xs text-green-600 mb-2">Aucun enregistrement</p>
    @endif
    <a href="{{ route('plants.fertilizing-history.index', $plant) }}" class="text-xs text-green-500 hover:text-green-700 mt-1 inline-block">Gérer →</a>
</div>
