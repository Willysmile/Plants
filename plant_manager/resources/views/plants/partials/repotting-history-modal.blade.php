<!-- Repotting History Summary (for modal) -->
<div class="mb-6">
    <h3 class="text-xl font-bold text-amber-600 mb-3">
        🪴 Historique de rempotage
    </h3>
    
    @if($plant->reppotingHistories()->exists())
        <div class="space-y-2 max-h-48 overflow-y-auto">
            @foreach($plant->reppotingHistories()->latest('repotting_date')->take(5) as $history)
                <div class="bg-amber-50 p-3 rounded border-l-4 border-amber-400">
                    <p class="text-sm text-gray-600">{{ $history->repotting_date->format('d/m/Y à H:i') }}</p>
                    <p class="text-sm font-semibold text-gray-700">
                        @if($history->old_pot_size)
                            {{ $history->old_pot_size }} → 
                        @endif
                        {{ $history->new_pot_size }}
                    </p>
                    @if($history->soil_type)
                        <p class="text-sm text-gray-700">{{ $history->soil_type }}</p>
                    @endif
                    @if($history->notes)
                        <p class="text-sm text-gray-600 italic">{{ Str::limit($history->notes, 100) }}</p>
                    @endif
                </div>
            @endforeach
        </div>
        <a href="{{ route('plants.repotting-history.index', $plant) }}" class="text-amber-500 hover:text-amber-700 text-sm mt-2 inline-block">
            Voir tous les rempotages →
        </a>
    @else
        <p class="text-gray-600 text-sm">Aucun historique de rempotage enregistré.</p>
    @endif
</div>
