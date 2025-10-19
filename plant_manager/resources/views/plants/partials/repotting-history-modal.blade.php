<!-- Repotting History Summary (for modal) -->
<div class="bg-amber-50 p-3 rounded border-l-4 border-amber-400">
    @php
        $lastRepotting = $plant->repottingHistories()->latest('repotting_date')->first();
    @endphp
    
    <div class="flex items-center justify-between mb-2">
        <div class="flex items-center gap-2">
            <i data-lucide="sprout" class="w-4 h-4 text-amber-600"></i>
            <span class="text-sm font-semibold text-amber-900">Rempotage</span>
        </div>
        <label class="flex items-center gap-2 cursor-pointer">
            <input type="checkbox" id="quickRepottingCheckbox" class="w-4 h-4 text-amber-600 rounded focus:ring-amber-500" onclick="openQuickRepottingModalFromModal(this)">
        </label>
    </div>
    
    @if($lastRepotting)
        <p class="text-xs text-amber-600 mb-2">Dernier : {{ $lastRepotting->repotting_date->format('d/m/Y H:i') }}</p>
        <div class="grid grid-cols-2 gap-2">
            @if($lastRepotting->old_pot_size)
                <p class="text-xs text-gray-600">De : {{ $lastRepotting->old_pot_size }} cm</p>
            @endif
            @if($lastRepotting->new_pot_size)
                <p class="text-xs text-gray-600">Vers : {{ $lastRepotting->new_pot_size }} cm</p>
            @endif
        </div>
    @else
        <p class="text-xs text-amber-600 mb-2">Aucun enregistrement</p>
    @endif
    <a href="{{ route('plants.repotting-history.index', $plant) }}" class="text-xs text-amber-500 hover:text-amber-700 mt-1 inline-block">Gérer →</a>
</div>
