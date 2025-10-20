<!-- Infos Diverses - Titre + Compteur + Button -->
<div class="mt-4 p-3 bg-gray-50 rounded-lg border border-gray-200">
  <div class="flex items-center justify-between gap-3">
    <div class="flex items-center gap-2">
      <h4 class="text-sm font-semibold text-gray-700 uppercase tracking-wide">Infos Diverses</h4>
      @if($plant->histories && $plant->histories->count() > 0)
        <span class="inline-block bg-gray-200 text-gray-700 text-xs font-bold px-2.5 py-0.5 rounded-full">
          {{ $plant->histories->count() }}
        </span>
      @else
        <span class="inline-block bg-gray-100 text-gray-500 text-xs font-medium px-2.5 py-0.5 rounded-full">
          0
        </span>
      @endif
    </div>
    
    @if($plant->histories && $plant->histories->count() > 0)
      <button type="button" 
              onclick="openFreeHistoriesModal({{ $plant->id }})" 
              class="px-3 py-1 bg-blue-500 hover:bg-blue-600 text-white rounded text-sm transition flex items-center gap-1">
        <i data-lucide="eye" class="w-4 h-4"></i>
        Voir
      </button>
    @endif
  </div>
</div>

<script>
function openFreeHistoriesModal(plantId) {
  // Ouvrir la modale des histories (définie plus loin)
  const modal = document.getElementById('free-histories-modal-' + plantId);
  if (modal) {
    modal.style.display = 'flex';
  }
}
</script>
