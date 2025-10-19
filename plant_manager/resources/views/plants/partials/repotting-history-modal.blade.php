<!-- Repotting History Summary (for modal) -->
<div class="bg-amber-50 p-3 rounded border-l-4 border-amber-400">
    @php
        $lastRepotting = $plant->reppotingHistories()->latest('repotting_date')->first();
    @endphp
    
    <div class="flex items-center justify-between mb-2">
        <a href="{{ route('plants.repotting-history.index', $plant) }}" class="flex items-center gap-2 hover:opacity-75">
            <i data-lucide="sprout" class="w-4 h-4 text-amber-600"></i>
            <span class="text-sm font-semibold text-amber-900">Rempotage</span>
        </a>
    </div>
    
    @if($lastRepotting)
        <p class="text-xs text-amber-600 mb-2">Dernier : {{ $lastRepotting->repotting_date->format('d/m/Y') }}</p>
        <div class="space-y-1">
            @if($lastRepotting->old_pot_size || $lastRepotting->new_pot_size)
                @php
                    $oldSize = (float) str_replace(['cm', ' '], '', $lastRepotting->old_pot_size ?? '0');
                    $newSize = (float) str_replace(['cm', ' '], '', $lastRepotting->new_pot_size ?? '0');
                    $arrow = $oldSize < $newSize ? '→' : ($oldSize > $newSize ? '←' : '=');
                @endphp
                @if($oldSize === $newSize && $oldSize > 0)
                    <p class="text-xs text-gray-600">Diamètre du pot : {{ $lastRepotting->new_pot_size }}</p>
                @else
                    <p class="text-xs text-gray-600">{{ $lastRepotting->old_pot_size }} {{ $arrow }} {{ $lastRepotting->new_pot_size }}</p>
                @endif
            @endif
        </div>
    @else
        <p class="text-xs text-amber-600 mb-2">Aucun enregistrement</p>
    @endif
    <button type="button" onclick="openQuickRepottingModalFromModal()" class="text-xs text-amber-500 hover:text-amber-700 mt-2 inline-block font-semibold">Créer →</button>
</div>
