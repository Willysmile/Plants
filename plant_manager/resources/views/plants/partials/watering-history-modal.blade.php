<!-- Watering History Summary (for modal) -->
<div class="mb-6">
    <h3 class="text-xl font-bold text-blue-600 mb-3">
        💧 Historique d'arrosage
    </h3>
    
    @if($plant->wateringHistories()->exists())
        <div class="space-y-2 max-h-48 overflow-y-auto">
            @foreach($plant->wateringHistories()->latest('watering_date')->take(5) as $history)
                <div class="bg-blue-50 p-3 rounded border-l-4 border-blue-400">
                    <p class="text-sm text-gray-600">{{ $history->watering_date->format('d/m/Y à H:i') }}</p>
                    @if($history->amount)
                        <p class="text-sm text-gray-700">{{ $history->amount }}</p>
                    @endif
                    @if($history->notes)
                        <p class="text-sm text-gray-600 italic">{{ Str::limit($history->notes, 100) }}</p>
                    @endif
                </div>
            @endforeach
        </div>
        <a href="{{ route('plants.watering-history.index', $plant) }}" class="text-blue-500 hover:text-blue-700 text-sm mt-2 inline-block">
            Voir tous les arrosages →
        </a>
    @else
        <p class="text-gray-600 text-sm">Aucun historique d'arrosage enregistré.</p>
    @endif
</div>
