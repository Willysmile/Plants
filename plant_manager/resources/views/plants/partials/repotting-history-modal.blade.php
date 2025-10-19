<!-- Repotting History Summary (for modal) -->
<div class="bg-amber-50 p-3 rounded border-l-4 border-amber-400">
    @php
        $lastRepotting = $plant->reppotingHistories()->latest('repotting_date')->first();
    @endphp
    
    <div class="flex items-center justify-between mb-2">
        <a href="{{ route('plants.repotting-history.index', $plant) }}" class="text-sm font-semibold text-amber-900 hover:text-amber-700 hover:underline flex-1">
            🪴 Dernier rempotage : 
            @if($lastRepotting)
                {{ $lastRepotting->repotting_date->format('d/m/Y') }}
            @else
                —
            @endif
        </a>
        <a href="{{ route('plants.repotting-history.create', $plant) }}" class="text-xs font-semibold text-amber-600 hover:text-amber-800 hover:underline ml-2 px-2 py-1 rounded hover:bg-amber-100 transition">
            + Rempoter
        </a>
    </div>
    
    @if($lastRepotting)
        <div class="grid grid-cols-2 gap-2">
            <p class="text-xs text-gray-600">
                @if($lastRepotting->old_pot_size)
                    {{ $lastRepotting->old_pot_size }} → 
                @endif
                {{ $lastRepotting->new_pot_size }}
            </p>
            @if($lastRepotting->soil_type)
                <p class="text-xs text-gray-600">Terreau : {{ $lastRepotting->soil_type }}</p>
            @endif
        </div>
    @else
        <p class="text-xs text-gray-600">Aucun enregistrement</p>
    @endif
</div>
