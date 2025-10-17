<!-- Fertilizing History Summary (for modal) -->
<div class="mb-6">
    <h3 class="text-xl font-bold text-green-600 mb-3">
        🌱 Historique de fertilisation
    </h3>
    
    @if($plant->fertilizingHistories()->exists())
        <div class="space-y-2 max-h-48 overflow-y-auto">
            @foreach($plant->fertilizingHistories()->latest('fertilizing_date')->take(5) as $history)
                <div class="bg-green-50 p-3 rounded border-l-4 border-green-400">
                    <p class="text-sm text-gray-600">{{ $history->fertilizing_date->format('d/m/Y à H:i') }}</p>
                    @if($history->fertilizer_type)
                        <p class="text-sm font-semibold text-gray-700">{{ $history->fertilizer_type }}</p>
                    @endif
                    @if($history->amount)
                        <p class="text-sm text-gray-700">{{ $history->amount }}</p>
                    @endif
                    @if($history->notes)
                        <p class="text-sm text-gray-600 italic">{{ Str::limit($history->notes, 100) }}</p>
                    @endif
                </div>
            @endforeach
        </div>
        <a href="{{ route('plants.fertilizing-history.index', $plant) }}" class="text-green-500 hover:text-green-700 text-sm mt-2 inline-block">
            Voir toutes les fertilisations →
        </a>
    @else
        <p class="text-gray-600 text-sm">Aucun historique de fertilisation enregistré.</p>
    @endif
</div>
